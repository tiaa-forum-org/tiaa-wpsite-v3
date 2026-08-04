# Security Review — tiaa-wpplugin / tiaa-elementor / tiaa-quick-edit

Follow-up to `SECURITY-SCOPE.md`. Each prioritized entry was traced from input
source to sink. Severity reflects **realistic reachability in this stack**, not
just abstract bug class. The "Fix" column distinguishes a mechanical change from
one needing a maintainer decision — the maintainers are volunteers who did not
write this code, so "design decision" items should not be applied blind.

Reviewed at commit state following tiaa-wpplugin v0.0.10.

## Severity summary

| # | Finding | Severity | Fix type |
|---|---|---|---|
| F1 | SQL injection / arbitrary-table read in CSV export | Medium | Small (allowlist + cap check) |
| F2 | Public route proxies arbitrary Discourse posts with admin key (confused deputy) | Medium–High | Design decision |
| F3 | CSRF on screened-email add / delete / import | Medium | Small (add nonces) |
| F4 | Arbitrary file write via `export_file_name` | Low | Design decision (likely delete) |
| F5 | Logout CSRF re-enabled by nonce auto-supply | Low | Design decision (intentional UX) |
| F6 | Public invite endpoint: unauthenticated send-abuse + screened-email enumeration | Low–Medium | Design decision |
| F7 | `tiaa_member` cookie is presentation-only, not an auth control | Informational | No code change; document |
| F8 | Dead `download_log_file` hook; file handler has no explicit cap check | Low | Small (housekeeping) |

No injection, XSS, or auth bypass was found in `tiaa-quick-edit` or in the
front-end output paths (shortcode, funding-color style, forum-button script) —
all are correctly escaped/parameterized. See "Cleared" section at the end.

---

## F1 — SQL injection (arbitrary-table read) in CSV export
**Severity: Medium** · **Fix: Small**

**Path:** `admin/GeneralFileHandler.php:93` (`$_GET['table']`) → `:99`
`output_csv_from_database($table_name)` → `:171-174`:

```php
$full_table_name = $wpdb->prefix . $table_name;
$results = $wpdb->get_results( "SELECT * FROM $full_table_name", ARRAY_A );
```

The `table` GET parameter is concatenated directly into a query. WordPress
cannot parameterize an identifier (`%s` would quote it as a string), so the fix
is an allowlist, not `$wpdb->prepare()`. Consequences:

- **Arbitrary table dump:** `?action=tiaa_secure_file&type=csv&table=users&_wpnonce=…`
  returns `wp_users` as CSV — including `user_pass` hashes and emails.
- **Injection past the table name:** the value is unescaped, so
  `table=users a WHERE 1=1 UNION SELECT …` executes. (`get_results()` runs a
  single statement, so stacked `; DROP` does not apply, but arbitrary read via
  UNION/subquery does.)

**Reachability caveat (why Medium, not Critical):** entry is gated only by a
nonce (`admin_post_tiaa_secure_file`), not by a capability check in the handler.
That nonce is minted only inside `manage_options`-gated admin views, and WP
nonces are bound to the minting user's session — so a lower-privileged user
cannot obtain or reuse one. In practice this is reachable by a `manage_options`
admin. The risk it adds over "admins can do things" is (a) turning a
screened-emails export button into arbitrary DB read for an admin who is not
meant to be a DB superuser, and (b) no defense-in-depth capability check.

**Fix (small, ~5 lines):** allowlist the table, and add an explicit capability
check at the top of `tiaa_serve_file()`:

```php
if ( ! current_user_can( 'manage_options' ) ) { /* 403 */ }
$allowed = [ 'tiaa_screened_emails' ];               // the only table exported today
if ( ! in_array( $table_name, $allowed, true ) ) { /* 400 */ }
```

The single legitimate caller (`screened-emails-view.php:70`) passes a hardcoded
`tiaa_screened_emails`, so the allowlist costs nothing functionally.

---

## F2 — Public route proxies arbitrary Discourse posts with the site's admin key
**Severity: Medium–High** · **Fix: Design decision**

**Path:** route `/tiaa_wpplugin/v1/get_discourse_post` (`lib/TiaaHooks.php:309`,
`permission_callback => __return_true`) → `PluginUtil::get_discourse_post_by_id`
(`lib/PluginUtil.php:163`) → `Discourse::get_discourse_post_by_id`
(`lib/Discourse.php:206-217`):

```php
$apiEndPoint = '/posts/' . $post_id . '.json';
return self::getApiResponse($cs['url'], $apiEndPoint, $cs['api_key'], $cs['username'], 'GET');
```

The route is unauthenticated (public by declaration). `post_id` is validated
numeric (so no path injection), but the request to Discourse is made with the
site's **stored API key and username** — i.e. the forum's privileged service
credentials — and the raw `body_response` is returned to the caller
(`Discourse.php:504-512`). This is a classic confused-deputy / IDOR: an
anonymous internet caller can read **any Discourse post by ID**, including posts
in private categories or staff areas, to whatever extent the configured API key
is privileged (TIAA's key is admin-scoped elsewhere in this code — see
`get_recent_members` using `/admin/users/list`).

`option_group` is caller-controlled (`is_string` only) but merely selects which
*stored* connection to use — the target host/key come from saved options, not
the request, so there is no SSRF-to-arbitrary-host here. The exposure is the
unauthenticated read via privileged credentials.

**Why this needs a decision, not a patch:** the intended consumer and intended
visibility of this endpoint aren't documented. Options, in rough order of
preference:
1. If nothing uses it — remove the route (grep found no front-end caller).
2. If it must stay — require a capability (`__return_true` → a real check), or
   validate that the requested post is in a public category before returning it.
3. At minimum, scope the Discourse API key used here to read-only/public.

This is the same open question flagged in the July incident review; it is a
standing exposure independent of that incident.

---

## F3 — CSRF on screened-email add / delete / import
**Severity: Medium** · **Fix: Small**

**Path:** `admin/ScreenedEmailsHandler.php:137` `handle_form_submissions()`,
reached from `render_screened_emails_page()` which *does* gate on
`current_user_can('manage_options')` (`:68`). But three state-changing branches
have **no nonce**:

- add — `:139` `if ( isset($_POST['submit_email']) … ) $wpdb->insert(...)`
- delete — `:153` `if ( isset($_POST['delete_email_id']) ) $wpdb->delete(...)`
- import — `:158` `if ( isset($_POST['import_csv']) && $_FILES['csv_file'] )`

The corresponding forms in `views/screened-emails-view.php` (`:29`, `:50`,
`:115`) contain no `wp_nonce_field()`. Only the *export* branch checks
`check_admin_referer()` (`:190`). So a logged-in admin who visits a malicious
page can be made to silently add or delete screened-email entries (which govern
who is blocked from / invited to Discourse), or trigger a CSV import.

**Not a SQLi and not stored XSS today:** all three DB writes go through
`$wpdb->insert()/delete()` (parameterized), and every value is re-escaped with
`esc_html()` on output in the view (`:107-112`). So the impact is limited to
unauthorized row add/delete/import, not code execution.

**Fix (small):** add `wp_nonce_field()` to each of the three forms and a
matching `check_admin_referer()` at the top of each branch — the export branch
already shows the exact pattern to copy.

**Two adjacent bugs worth fixing in the same pass (not security-critical):**
- Field-name mismatch: the form input is `name="tiaa_csv_file"`
  (`view:52`) but the handler reads `$_FILES['csv_file']` (`handler:159`) — the
  UI import is currently a no-op (an attacker crafting their own POST is
  unaffected, which is why the CSRF note above still stands).
- `notes` (`:147`) and the CSV date columns (`:170-172`) are stored without
  sanitization. Harmless today because output is escaped, but sanitize on write
  for defense-in-depth.

---

## F4 — Arbitrary file write via `export_file_name`
**Severity: Low** · **Fix: Design decision (likely delete the path)**

`admin/ScreenedEmailsHandler.php:194-203`:

```php
$file_name = ! empty($_POST['export_file_name']) ? $_POST['export_file_name'] : '/tmp/screened_emails.csv';
if ( pathinfo($file_name, PATHINFO_EXTENSION) !== 'csv' ) $file_name .= '.csv';
$output = @fopen( $file_name, 'w' );
```

An admin-supplied string is used directly as a filesystem path for writing,
constrained only to a `.csv` extension — so path traversal to any
web-server-writable location is possible (overwrite an existing `.csv`, fill a
volume, write into an upload dir). It is nonce-protected (`:190`) and admin-only,
which keeps severity Low, and the content written is the screened-emails table
(not fully attacker-chosen). The default `/tmp/screened_emails.csv` is itself a
predictable-path smell.

**Decision needed:** this server-side "write a CSV to a path" path appears to be
legacy — there is no form in `screened-emails-view.php` that submits
`export_csv`/`export_file_name`; the actual export users see is the
nonce'd *download* link handled by `GeneralFileHandler` (F1's file). Recommend
removing the `export_csv` branch entirely. If it must stay, drop the
user-supplied path and write to a fixed server location.

---

## F5 — Logout CSRF re-enabled by nonce auto-supply
**Severity: Low** · **Fix: Design decision (appears intentional)**

`lib/TiaaHooks.php:91-106` (`skip_logout_confirmation`, on `login_init`):

```php
if ( $_REQUEST['action'] === 'logout' && ! isset( $_REQUEST['_wpnonce'] ) ) {
    $redirect   = isset($_REQUEST['redirect_to']) ? esc_url_raw($_REQUEST['redirect_to']) : home_url('/');
    $logout_url = html_entity_decode( wp_logout_url( $redirect ) );
    wp_redirect( $logout_url ); exit;
}
```

WordPress deliberately shows a "Do you really want to log out?" interstitial when
a logout link lacks a valid `_wpnonce` — that interstitial is WP's built-in
defense against logout-CSRF. This hook detects the missing nonce and instead
mints a valid logout URL and redirects to complete the logout automatically.
Net effect: any page can force-log-out a member by pointing them at
`/wp-login.php?action=logout` (no nonce needed). Impact is limited to a forced
logout (nuisance), so Low.

**Not an open redirect:** `redirect_to` is passed through `esc_url_raw()` here,
and the *final* post-logout redirect is performed by WordPress core via
`wp_safe_redirect()` (same-host enforced). So the `redirect_to` parameter cannot
be turned into an off-site redirect through this path.

**Decision needed:** this is an intentional UX accommodation for SSO-generated
logout links (documented in the method's own docblock). Accept as-is, or narrow
it to only fire for logout requests that originate from the SSO flow rather than
any nonce-less logout request.

---

## F6 — Public invite endpoint: send-abuse + screened-email enumeration
**Severity: Low–Medium** · **Fix: Design decision**

Route `/tiaa_wpplugin/v1/invite` (`lib/TiaaHooks.php:170`, `__return_true`) is
public by design (it backs an anonymous Elementor "request an invite" form; the
`tiaa-elementor` PHP relay in `tiaa-invite-action.php:113` is entirely commented
out, so the real entry point is this REST route hit directly from
`form-handler.js`). `invite_to_discourse` (`lib/TiaaHooks.php` handler) validates
only that `name` and `email` are present, then calls the Discourse invite API
with the site's credentials. Two abuse vectors:

- **Unauthenticated invite/email send:** anyone can drive the site's Discourse
  into sending invite emails to arbitrary addresses, with no rate limit or
  challenge — usable for spam/abuse under the forum's identity.
- **Screened-email enumeration:** a screened address returns a distinct
  `dropped_email` response (`invite_to_discourse`, screened-email branch),
  letting an anonymous caller test whether any given email is on the screened
  list.

**Decision needed:** the endpoint must stay reachable by anonymous users (that's
the feature), so the answer is throttling/challenge rather than an auth gate —
e.g. a rate limit per IP, a CAPTCHA/hidden-field check on the form, or an origin
check. Also consider returning a uniform response for screened vs. accepted so
membership on the screened list isn't observable.

---

## F7 — `tiaa_member` cookie is presentation-only, not an auth control
**Severity: Informational** · **Fix: No code change — document the boundary**

This directly addresses the review's flagged concern ("the cookie substitutes
for WordPress's own login state"). Traced every use:

- Set in `lib/TiaaMemberCookie.php:44` (value literally `'1'`, `httponly:false`,
  `SameSite=Lax`), and read in exactly two places: `TiaaMemberCookie.php:58` and
  `TiaaHooks.php:71` — both of which do nothing but append a **body CSS class**
  (`tiaa-returning-member` / `tiaa-member`).
- `tiaa-elementor` then toggles `.tiaa-member-only` / `.tiaa-anon-only`
  visibility from that body class in CSS.

It is **never** consulted in any PHP capability, REST permission, or
content-access decision. So although the cookie is trivially spoofable (any
visitor can set `tiaa_member=1`), doing so only flips CSS visibility — it grants
no server-side privilege and is not an auth bypass as the code stands today.

**The real caveat to record:** because `.tiaa-member-only` hides via CSS,
anything placed behind that class is still present in the page HTML for anonymous
visitors — the cookie just controls whether it's *displayed*. That's fine for
cosmetic member/anon UI, but it means these classes must **never** be used to
"protect" sensitive content: CSS-hidden ≠ access-controlled. Worth a one-line
warning in the tiaa-elementor docs next to the utility-class description.

---

## F8 — Dead `download_log_file` hook; file handler lacks explicit cap check
**Severity: Low** · **Fix: Small (housekeeping)**

- `admin/LogSettings.php:64` registers
  `admin_post_download_log_file → handle_download_log_file`, but no
  `handle_download_log_file` method exists in the class — invoking
  `admin-post.php?action=download_log_file` would fatal. Not exploitable; remove
  the stale registration (the working download uses `action=tiaa_secure_file`).
- `GeneralFileHandler::tiaa_serve_file()` relies solely on the nonce for
  authorization with no `current_user_can()` — folded into F1's fix.

---

## Cleared (traced, no issue found)

- **`tiaa-quick-edit/tiaa-quick-edit.php`** — both the `save_post` handler
  (`:203`) and the AJAX handler `tiaa_qe_ajax_get_post_data` (`:266`) check a
  nonce **and** `current_user_can('edit_post', $post_id)` for the *specific*
  post (`:219`, `:270`) — not merely "any logged-in user." DB writes use
  `$wpdb->update()` with format specifiers (parameterized). Inputs use
  `absint()` / `sanitize_textarea_field()`. Priority-6 concern does not
  materialize. Clean.
- **Front-end output in `lib/TiaaSiteSettings.php`** — `[tiaa_stat]` shortcode
  (`:229`) maps `field` to a fixed key set and `esc_html()`s output;
  `output_forum_button_script` (`:286`) uses `esc_js()` on an admin-controlled
  URL; `output_contribute_color_style` (`:317`) sanitizes funding level to a
  four-value allowlist and `esc_attr()`s. No XSS.
- **`lib/Discourse.php` response handling** — `handle_discourse_response`
  (`:454`) returns Discourse's HTTP status/message/body but does **not** echo
  back request headers, so the API key/username are not leaked to REST callers.
  (The confused-deputy exposure in F2 is about *which* credentials are used, not
  a credential leak.)
- **Elementor invite relay** (`tiaa-invite-action.php:113` `run()`) — server-side
  relay body is commented out; only `$ajax_handler->is_success = true` runs. No
  server-side trust of `$record` shape. Real path is the REST route (F6).
