# Security Review Scope — tiaa-wpplugin / tiaa-elementor / tiaa-quick-edit

Index for a follow-up security review. Not an audit — no findings, no severity ratings.

Paths:
- `wp-content/plugins/tiaa-wpplugin/`
- `wp-content/plugins/tiaa-elementor/`
- `wp-content/plugins/tiaa-quick-edit/`

---

## 1. User-input entry points

### REST API routes
| Route | Method | File | permission_callback |
|---|---|---|---|
| `/tiaa_wpplugin/v1/tiaa_discourse_ping` | GET | `tiaa-wpplugin/lib/TiaaHooks.php:142` | `current_user_can('manage_options')` |
| `/tiaa_wpplugin/v1/invite` | POST | `tiaa-wpplugin/lib/TiaaHooks.php:170` | `__return_true` (intentionally public) |
| `/tiaa_wpplugin/v1/get_discourse_post` | GET | `tiaa-wpplugin/lib/TiaaHooks.php:309` | `__return_true` (intentionally public) |

`tiaa-elementor` and `tiaa-quick-edit` register no REST routes.

### admin-post / admin-ajax hooks
| Hook | File | Auth gate |
|---|---|---|
| `admin_post_tiaa_secure_file` | `tiaa-wpplugin/admin/admin.php:49` → `GeneralFileHandler.php` | nonce (`admin_post_tiaa_secure_file`) only — no explicit `current_user_can()` in the handler itself |
| `admin_post_download_log_file` | `tiaa-wpplugin/admin/LogSettings.php:64` | not yet traced |
| `wp_ajax_tiaa_qe_get_post_data` | `tiaa-quick-edit/tiaa-quick-edit.php:265` | no `nopriv` variant registered → requires an authenticated session by default WP behavior |

### Shortcodes
| Shortcode | File | Attributes |
|---|---|---|
| `[tiaa_stat]` | `tiaa-wpplugin/lib/TiaaSiteSettings.php:78` | `field` (members/topics/posts/categories/as_of) |

### `$_POST` / `$_GET` / `$_FILES` processing (admin screens, behind wp-admin)
| File | Inputs consumed | Gate present? |
|---|---|---|
| `tiaa-wpplugin/admin/ScreenedEmailsHandler.php` | `submit_email`, `email`, `notes`, `delete_email_id`, `import_csv` + `$_FILES['csv_file']`, `export_csv`, `export_file_name`, `column_labels` | `current_user_can('manage_options')` at :68; `check_admin_referer` only on the export path (:190) — import/delete/add paths not yet confirmed nonce-checked |
| `tiaa-wpplugin/admin/WelcomeDataHandler.php` | any `$_POST` key matching `/^cron_/`, plus `get_cron_status` | `current_user_can('manage_options')` at :58 |
| `tiaa-wpplugin/admin/GeneralFileHandler.php` | `_wpnonce`, `type` (`log`\|`csv`), `table` (raw GET param passed into `output_csv_from_database($table_name)`) | nonce only, see entry-point table above — **no allow-list visible on `table` at this layer** |
| `tiaa-quick-edit/tiaa-quick-edit.php` | `tiaa_qe_nonce`, `tiaa_menu_order`, `tiaa_post_excerpt`, `post_id` | nonce (`tiaa_qe_save`) + `current_user_can('edit_post', $post_id)` at :219 and :270 |
| `tiaa-wpplugin/admin/FormHelper.php`, `admin/options-page.php` | `tab`, `page` (tab routing only, `sanitize_key()`'d) | implicit — only reachable behind wp-admin's own capability gate on the settings page itself |

### Cookies read
| Cookie | File | Purpose |
|---|---|---|
| `tiaa_member` | `tiaa-wpplugin/lib/TiaaHooks.php:71`, `lib/TiaaMemberCookie.php:40/54/58` | drives `tiaa-member` body class / three-state UI logic |
| `sso` / `sig` (GET, not cookie) | `tiaa-wpplugin/lib/TiaaLoginRedirect.php:102` | dead code per class docblock ("no-op in SSO client mode") — still parses the request |

### Elementor-originated input (indirect)
`tiaa-elementor/form-action/tiaa-invite-action.php` is an Elementor Pro "Form Action" — it receives the submitted form's `$record` server-side inside Elementor's own form-processing pipeline (not a route we register), then relays the data via `wp_remote_post()` to tiaa-wpplugin's own `/invite` REST route (`run()`, :113–144). This is the origin of the `form_fields[...]`-shaped payloads that `TiaaHooks::invite_to_discourse()` parses.

---

## 2. External calls

| Call site | Target | File |
|---|---|---|
| `wp_remote_get` / `wp_remote_post` | Discourse API (ping, invite, get-post-by-id) — central wrapper | `tiaa-wpplugin/lib/Discourse.php:381,384` |
| `wp_remote_post` | This site's own `/wp-json/tiaa_wpplugin/v1/invite` (internal loopback from Elementor form action) | `tiaa-elementor/form-action/tiaa-invite-action.php:144` |

No Stripe / WP SimplePay / Amazon SES / `wp_mail()` calls exist in any of the three plugins — grepped and confirmed. (Two `stripe`-pattern grep hits were false positives: the CSS class `striped` in two admin view files.) Welcome/invite messaging appears to route entirely through the Discourse invite API's `message` parameter, not direct WP email.

---

## 3. Auth / session-sensitive code

| Concern | File | Notes |
|---|---|---|
| `tiaa_member` cookie set/read | `tiaa-wpplugin/lib/TiaaMemberCookie.php` (62 lines), `lib/TiaaHooks.php:70-75` | 1-year cookie, persists after logout by design (see project memory: three-state user model) |
| `tiaa_wp_return_url` cookie | `tiaa-wpplugin/lib/TiaaReturnUrlCookie.php` (68 lines) | written pre-SSO-redirect; consumed by a separate theme repo, not read back in PHP here |
| SSO logout skip | `tiaa-wpplugin/lib/TiaaHooks.php:91-106` (`skip_logout_confirmation`) | trusts `$_REQUEST['redirect_to']` through `esc_url_raw()` then `wp_logout_url()` — not yet checked for open-redirect potential |
| Admin bar / capability gating | `tiaa-wpplugin/lib/TiaaBase.php:106` | `is_user_logged_in() && !current_user_can('administrator')` — hides admin bar for non-admins |
| REST route auth | `tiaa-wpplugin/lib/TiaaHooks.php:142-324` | see REST table above — two of three routes intentionally public |
| Nonce coverage gaps | `ScreenedEmailsHandler.php` | only the CSV-export path (:190) has a confirmed `check_admin_referer()` call; add/delete/import paths not yet verified |
| `edit_post` capability check | `tiaa-quick-edit/tiaa-quick-edit.php:219,270` | per-post capability check present on both the save and AJAX-fetch paths |
| Dormant SSO-provider code | `tiaa-wpplugin/lib/TiaaLoginRedirect.php` | reads `$_GET['sso']`/`$_GET['sig']` but class is documented as a no-op in current SSO-client configuration |

---

## 4. File-by-file index

### tiaa-wpplugin (29 PHP files, 2 JS files)

| File | Lines | What it does |
|---|---|---|
| `tiaa-wpplugin.php` | 65 | Plugin entry point; constants, requires, bootstrap |
| `lib/TiaaBase.php` | 110 | Base class; bootstraps plugin on `init`, admin-bar suppression, auth-cookie lifetime |
| `lib/TiaaHooks.php` | 365 | Registers all 3 REST routes, cron intervals, SSO redirect/logout hooks, member body class |
| `lib/Discourse.php` | 550 | All Discourse API calls (ping, invite, get post, connection options) |
| `lib/PluginUtil.php` | 446 | Shared utility trait: ping handler, get-post handler, logging helpers |
| `lib/WelcomeUtil.php` | 514 | Welcome-message logic, cron scheduling |
| `lib/TiaaSiteSettings.php` | 367 | Site Settings admin tab; `[tiaa_stat]` shortcode; funding-color/forum-button output hooks |
| `lib/ScreenEmailsUtil.php` | 160 | Screened-emails DB logic |
| `lib/options-utilities.php` | 159 | WP options helpers |
| `lib/TiaaMemberCookie.php` | 62 | Sets/reads `tiaa_member` cookie |
| `lib/TiaaReturnUrlCookie.php` | 68 | Writes `tiaa_wp_return_url` cookie pre-SSO |
| `lib/TiaaLoginRedirect.php` | 104 | No-op in current SSO-client mode; kept for reference |
| `admin/settings-validator.php` | 529 | Validation helpers for the Settings API pattern |
| `admin/WelcomeSettings.php` | 501 | Welcome-messages admin tab |
| `admin/GroupInviteSettings.php` | 342 | Group-invite admin tab |
| `admin/InviteSettings.php` | 248 | Invite-settings admin tab |
| `admin/ScreenedEmailsHandler.php` | 239 | Screened-emails CRUD/import/export handling |
| `admin/options-page.php` | 239 | Tab router for the plugin's settings pages |
| `admin/admin-menu.php` | 231 | Registers admin menu + submenu pages, all `manage_options`-gated |
| `admin/LogSettings.php` | 216 | Logging admin tab; registers log-download admin-post hook |
| `admin/ScreenedEmailsSettings.php` | 216 | Screened-emails admin tab (settings registration) |
| `admin/ConnectionSettings.php` | 194 | Discourse connection admin tab |
| `admin/GeneralFileHandler.php` | 194 | Secure file/CSV download via admin-post |
| `admin/FormHelper.php` | 168 | Admin form helper functions, tab detection |
| `admin/views/screened-emails-view.php` | 125 | Screened-emails admin view template |
| `admin/views/welcome-data-view.php` | 123 | Welcome-data admin view template |
| `admin/admin.php` | 98 | Admin bootstrap; script/style enqueue, nonce localization |
| `admin/WelcomeDataHandler.php` | 89 | Cron start/stop/run handling for welcome messages |
| `admin/assets/js/tiaa-admin.js` | 140 | Admin UI JS: ping-test fetch, message-test fetch, screened-emails toggle |
| `admin/assets/js/fetchWithProgress.js` | 68 | File-download-with-progress helper JS |

### tiaa-elementor (3 PHP files, 1 JS file)

| File | Lines | What it does |
|---|---|---|
| `form-action/tiaa-invite-action.php` | 236 | Elementor Pro Form Action; relays form submissions to tiaa-wpplugin's `/invite` REST route |
| `tiaa-elementor.php` | 122 | Plugin entry point; registers the form action, enqueues assets, localizes REST nonce |
| `loop-grid/clickable-cards.php` | 54 | Front-end only — makes Elementor Loop Grid cards fully clickable via CSS/JS overlay |
| `assets/js/form-handler.js` | 219 | Client-side handler for the TIAA form action: submits via `wp.apiFetch`, shows status |

### tiaa-quick-edit (1 PHP file, 2 JS files)

| File | Lines | What it does |
|---|---|---|
| `tiaa-quick-edit.php` | 368 | Adds Sort Order / Excerpt fields to Quick Edit; save handler + AJAX fetch handler; sortable admin column |
| `tiaa-quick-edit-debug.js` | 182 | Optional diagnostic console logging, not for production use per its own docblock |
| `tiaa-quick-edit.js` | 155 | Pre-fills Quick Edit fields via AJAX; updates list-table cell post-save |

---

## 5. Prioritized for deep review

1. **`tiaa-wpplugin/admin/GeneralFileHandler.php` (:77-100, `tiaa_serve_file`)** — a GET parameter (`table`) is passed into `output_csv_from_database($table_name)` with only a nonce check guarding entry; no allow-list visible at this layer. Worth confirming whether the callee constrains `$table_name` to a known-safe set before it reaches SQL.
2. **`tiaa-wpplugin/lib/TiaaHooks.php` (:141-324, all 3 REST routes) + `lib/PluginUtil.php` (handler bodies)** — the only network-facing entry points in the stack that aren't behind wp-admin; two of three are intentionally public. `get_discourse_post_by_id` in particular takes an arbitrary `post_id` and proxies it through the site's own privileged Discourse API credentials with no visibility check — a known open question from the prior incident review.
3. **`tiaa-wpplugin/admin/ScreenedEmailsHandler.php` (:139-210)** — multiple `$_POST`-driven branches (add/delete/import/export); only the export branch has a confirmed `check_admin_referer()` call. Worth confirming CSRF coverage on add/delete/import, and confirming the CSV import path validates row data before DB insert.
4. **`tiaa-wpplugin/lib/TiaaHooks.php:91-106` (`skip_logout_confirmation`)** — takes `$_REQUEST['redirect_to']`, passes it through `esc_url_raw()` into `wp_logout_url()`. Worth confirming `esc_url_raw()` alone is sufficient to prevent an open-redirect via the logout flow, given this fires without a nonce check on the redirect target itself (nonce is checked for the *absence* of `_wpnonce`, not the redirect value).
5. **`tiaa-elementor/form-action/tiaa-invite-action.php` (:113-144, `run()`)** — this is the actual origin of every payload that reaches tiaa-wpplugin's public `/invite` route; worth checking what Elementor-side validation (if any) happens before the internal `wp_remote_post()` relay, since the receiving end trusts the shape of the data.
6. **`tiaa-quick-edit/tiaa-quick-edit.php` (:265, `wp_ajax_tiaa_qe_get_post_data`)** — no `nopriv` hook is registered, but worth confirming the handler itself still checks `current_user_can()` for the *specific* post requested (not just "any logged-in user"), since `wp_ajax_*` alone only requires *a* logged-in session, not a privileged one.
7. **`tiaa-wpplugin/lib/Discourse.php` (550 lines, all outbound API calls)** — largest single file, central point where stored API keys are attached to every outbound request; worth a pass to confirm no response data or error output leaks credentials back to REST callers.
