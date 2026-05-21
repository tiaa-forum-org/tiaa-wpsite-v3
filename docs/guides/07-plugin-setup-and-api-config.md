# Plugin Setup & API Configuration Guide

**For:** Developers setting up a new local or staging environment
**Covers:** tiaa-wpplugin, tiaa-elementor, tiaa-quick-edit, WP-Discourse, and Discourse theme components
**Last updated:** 2026-05-21

---

## Overview

### WordPress plugins

| Plugin | Repo | Purpose |
|--------|------|---------|
| `tiaa-wpplugin` | `github.com/tiaa-forum-org/tiaa-wpplugin` | Discourse API bridge: invites, SSO cookies, welcome messages, site settings |
| `tiaa-elementor` | `github.com/tiaa-forum-org/tiaa-elementor` | Elementor Pro extensions: invite form action, clickable Loop Grid cards |
| `tiaa-quick-edit` | `github.com/tiaa-forum-org/tiaa-quick-edit` | Admin quick-edit UI for post Order field |
| WP-Discourse | WordPress.org plugin directory | SSO Client bridge: logs WP user in via Discourse SSO |

`tiaa-wpsite-v3` is also present in the plugins directory but is **not an active plugin** — it is a docs and assets container only.

### Discourse theme components

| Component | Repo | Purpose |
|-----------|------|---------|
| `TIAA-BrandTheme-v3` | `github.com/tiaa-forum-org/TIAA-BrandTheme-v3` | Brand nav bar above native Discourse header; cookie-based WP return link |
| `TIAA-DiscourseTheme-v1` | `github.com/tiaa-forum-org/TIAA-DiscourseTheme-v1` | Native Discourse header mods: login intercept → WP SSO, signup link, returning-user flash |

### SSO direction

**Discourse is the identity provider. WordPress is a client.**

WP-Discourse runs in **SSO Client** mode. Users authenticate on Discourse; WP-Discourse
validates the response and creates/logs in a matching WP user. The login flow always
starts from WordPress — a click on any `tiaa-sso-trigger` button initiates the handshake.
After WP logs in, `tiaa-wpplugin` redirects to Discourse home so both systems end up
authenticated.

---

## 1. Clone / Pull the Plugins

From inside `wp-content/plugins/`:

```bash
# First time — clone all three active plugins
git clone https://github.com/tiaa-forum-org/tiaa-elementor.git
git clone https://github.com/tiaa-forum-org/tiaa-wpplugin.git
git clone https://github.com/tiaa-forum-org/tiaa-quick-edit.git

# Subsequent sessions — pull latest
git -C tiaa-elementor pull
git -C tiaa-wpplugin pull
git -C tiaa-quick-edit pull
```

Then activate all three in **WordPress Admin → Plugins**.

> **Note:** `tiaa-elementor` supersedes the older `tiaa-elementor-forms-invite-action` plugin.
> If that older plugin is present, deactivate and delete it before activating `tiaa-elementor`.

---

## 2. Install & Activate WP-Discourse

1. WordPress Admin → Plugins → Add New
2. Search for **WP-Discourse**
3. Install and Activate

WP-Discourse is the single source of truth for the Discourse URL. The custom plugins
read from it — they do not store a separate copy.

---

## 3. Configure WP-Discourse

### Connection tab

**Settings → WP Discourse → Connection**

| Field | Value |
|-------|-------|
| Discourse URL | Your Discourse instance URL (e.g. `https://discourse.tiaa-forum.org`) |
| API Key | A Discourse API key with **admin** scope (see Section 5) |
| Publishing Username | The Discourse admin username the API key belongs to |

Save. The Discourse URL set here flows to the GO TO FORUM button, the SSO handshake,
and the post-login redirect.

### SSO Client tab

**Settings → WP Discourse → SSO Client**

| Field | Value |
|-------|-------|
| Enable SSO Client | Checked |
| SSO Secret | A strong random secret — must match the Discourse `discourse_connect_provider_secrets` value (see below) |

### Discourse-side SSO settings

In **Discourse Admin → Settings → Login**:

| Setting | Value |
|---------|-------|
| `enable_discourse_connect_provider` | **On** — makes Discourse act as SSO provider for WP |
| `discourse_connect_provider_secrets` | `your-wp-domain.tld:shared_secret` (one per line; domain must match your WP hostname) |
| `enable_discourse_connect` | **Off** — this makes Discourse a client to WP, which is the wrong direction; must be off |

> **Critical:** If `enable_discourse_connect` is ON, Discourse will try to redirect logins to
> WP as the identity provider, breaking the flow entirely. It must be OFF.

### How SSO works end-to-end

1. User clicks a Sign In / Join button on WordPress (must have CSS class `tiaa-sso-trigger`)
2. JS writes the `tiaa_wp_return_url` cookie (current page URL, 1 hr, cross-subdomain)
3. Browser follows the button href: `/?discourse_sso=1`
4. WP-Discourse `QueryRedirect` intercepts on `parse_query`, builds an SSO payload
   (`nonce` + `return_sso_url`), and redirects to `{discourse_url}/session/sso_provider`
5. Discourse validates the payload against `discourse_connect_provider_secrets`
6. Discourse prompts for login (or uses existing session)
7. Discourse redirects back to `return_sso_url` with `?sso=…&sig=…` appended
8. WP-Discourse SSO Client intercepts at `init` (priority 5), validates the signature,
   creates or updates the WP user, and calls `wp_set_auth_cookie()`
9. `tiaa-wpplugin` applies the `wpdc_sso_client_redirect_after_login` filter and
   redirects to Discourse home — user lands on Discourse, logged into both systems
10. On Discourse, `TIAA-BrandTheme-v3` reads the `tiaa_wp_return_url` cookie and updates
    the HOME link to the page the user came from

**No dedicated WP "landing page" is required.** WP-Discourse intercepts the SSO callback
on any URL that carries `?sso=…&sig=…` — the handshake is URL-agnostic.

### Discourse login from the Discourse side

When a user clicks the login button **directly on Discourse** (not from WordPress):

1. `TIAA-DiscourseTheme-v1` intercepts the click, shows the flash overlay, and redirects
   to `{wp_base_url}/?discourse_sso=1` — the same WP SSO initiation URL
2. From there the flow is identical to steps 4–10 above

This ensures WP and Discourse are both authenticated regardless of where login starts.

---

## 4. Configure tiaa-wpplugin — Connection Tab

**WordPress Admin → TIAA Forum → Connection**

This tab stores Discourse API credentials for invites, welcome messages, and admin lookups.

| Field | Value |
|-------|-------|
| Discourse URL | Same URL as WP-Discourse (e.g. `https://discourse.tiaa-forum.org`) |
| API Key | Admin-scoped Discourse API key |
| API Username | The Discourse admin username |

### Testing the API Connection

After saving, use the **Ping** button. It calls `/site/basic-info.json` on your Discourse
instance and returns basic site info on success.

**If the ping fails:**
- Check that the Discourse URL has no trailing slash issues
- Confirm the API key has not expired or been revoked
- Confirm the username is an active admin in Discourse
- Check the WordPress debug log — the plugin logs all Discourse API calls

---

## 5. Generating a Discourse API Key

In Discourse (admin must be logged in):

1. Go to **Admin → API → Keys → New API Key**
2. **Description**: `WordPress tiaa-wpplugin`
3. **User Level**: `Single User`
4. **User**: your admin username (e.g. `system` or the designated API user)
5. **Scope**: `All` (required for invites, user lookups, welcome messages, admin user listing)
6. Click **Save** — copy the key immediately, it is only shown once

> The invite flow checks `/admin/users/list/all.json` to detect duplicates, which requires admin-level API access.

---

## 6. Configure tiaa-wpplugin — Site Settings Tab

**WordPress Admin → TIAA Forum → Site Settings**

| Field | What It Does |
|-------|-------------|
| Cookie Domain | Shared cookie domain for WP + Discourse subdomains. Must start with a dot. See environments below. Can be overridden via `wp-config.php` constant. |
| Contact Email | Site contact email used in automated messages |
| Funding Level | Controls Contribute button colour: `green` / `yellow` / `red` / `blue` |
| Discourse URL | Read-only — pulled from WP-Discourse. Change it in WP-Discourse, not here. |
| Forum Statistics | Member / topic / post counts on the homepage stats card. Update manually. |
| As of | Date stats were last recorded. Displayed via shortcode. |

### Cookie Domain by environment

| Environment | Cookie Domain | `TIAA_COOKIE_DOMAIN` constant |
|-------------|--------------|-------------------------------|
| Production | `.tiaa-forum.org` | `define( 'TIAA_COOKIE_DOMAIN', '.tiaa-forum.org' );` |
| Test | `.test.tiaa-forum.org` | `define( 'TIAA_COOKIE_DOMAIN', '.test.tiaa-forum.org' );` |
| Dev | `.test` | `define( 'TIAA_COOKIE_DOMAIN', '.test' );` |

Add the constant to `wp-config.php`. When it is present the Site Settings field is
disabled and shows the active value. The leading dot is required for cross-subdomain
cookie sharing between WP and Discourse.

### Forum Statistics Shortcodes

| Shortcode | Outputs |
|-----------|---------|
| `[tiaa_stat field="members"]` | Member count |
| `[tiaa_stat field="topics"]` | Topic count |
| `[tiaa_stat field="posts"]` | Post count |
| `[tiaa_stat field="as_of"]` | "As of" date in the site's WP date format |

All four wrap output in `<span class="tiaa-stats">`. Style via Elementor Site Settings → Custom CSS:

```css
.tiaa-stats { font-weight: 600; }
```

---

## 7. Configure tiaa-wpplugin — Other Tabs

**WordPress Admin → TIAA Forum → [tab name]**

### Signup tab
Settings for Discourse invite emails: default group, custom message, topic ID to include.

### Group Signup tab
Per-group invite overrides. Add group slugs first; each group gets its own Discourse URL,
API key, and message (falls back to Connection tab values if blank).

### Screened Emails tab
Blocks invite submissions from specified email addresses or domains.

### Welcome tab
Configures automatic welcome private messages sent to new Discourse members. Set Discourse
API credentials, the message body, and the WP-Cron schedule.

> ⚠️ **Known issue:** Logger reliability from cron context is not fully audited. Welcome
> messages may not log errors consistently. Test the cron flow before relying on it.

### Logging tab
Controls log verbosity (debug / info / error). Use debug when troubleshooting API calls;
switch back to error for normal operation.

---

## 8. tiaa-elementor — Elementor Invite Form Setup

`tiaa-elementor` has no admin settings page. It activates automatically and its form
action appears in Elementor after the plugin is active.

To set up an invite form:

1. Add a **Form** widget to a page
2. Add an **Email** field (ID must be `email`)
3. Under **Actions After Submit**, add **TiaaInvite**
4. Under the **TiaaInvite** section that appears, set **Fetch timeout** (5000ms default is fine)
5. The form submits to `POST /tiaa_wpplugin/v1/invite` — wired automatically

The form handler JS is only enqueued on pages containing a form widget with the `tiaa`
submit action.

---

## 9. WordPress Button CSS Classes

Set these in Elementor's **Advanced tab → CSS Classes** field. The plugin injects
behaviour via JavaScript based on the class — the `href` is set by code, not manually,
except for SSO trigger buttons (see below).

### Sign In / Join buttons — `tiaa-sso-trigger`

Add `tiaa-sso-trigger` to any Sign In or Join button that should initiate the Discourse
SSO login flow.

**Button `href`:** Set the button link to `/?discourse_sso=1` — do not point it directly
at Discourse. WP-Discourse's `QueryRedirect` intercepts this URL and builds the SSO
handshake automatically.

**What the class does:** When a logged-out user clicks the button, a JS listener (injected
via `wp_footer` by `TiaaReturnUrlCookie`) writes the `tiaa_wp_return_url` cookie with the
current page URL before the browser follows the link. The Discourse brand header reads this
cookie to set the HOME link back to the originating page.

**Only fires for logged-out users** — the script is not enqueued for logged-in users or
admin pages.

**Where to set it:** Elementor → Theme Builder → Header → Sign In / Join button widget →
Advanced tab → CSS Classes → `tiaa-sso-trigger`

### GO TO FORUM button — `tiaa-go-to-forum`

Add to the GO TO FORUM button in the **Header template**. The plugin sets the button's
`href` to the Discourse URL from WP-Discourse via a `wp_footer` script.

**How it works:** The script targets both `.tiaa-go-to-forum a` (Elementor wraps button
classes on a div) and `a.tiaa-go-to-forum`.

**Where to set it:** Elementor → Theme Builder → Header → GO TO FORUM button widget →
Advanced tab → CSS Classes → `tiaa-go-to-forum`

### CONTRIBUTE button — `tiaa-contribute`

Add to the Contribute button in the **Header template**. The plugin reads the **Funding
Level** from Site Settings and outputs an inline `<style>` block in `<head>` setting the
button background colour.

| Funding Level | Background | Text |
|---------------|------------|------|
| Green | `#28a745` | white |
| Yellow | `#ffc107` | `#000000` (black) |
| Red | `#dc3545` | white |
| Blue | `#007bff` | white |

Colour updates immediately when Funding Level is saved — no cache clear needed.

**Where to set it:** Elementor → Theme Builder → Header → Contribute button widget →
Advanced tab → CSS Classes → `tiaa-contribute`

---

## 10. Loop Grid — Clickable Cards

No CSS class needed. `tiaa-elementor` automatically makes all Elementor Loop Grid cards
(`.e-loop-item`) fully clickable by overlaying a transparent anchor linking to the first
`<a>` found inside the card. Applies to all Loop Grids site-wide.

---

## 11. Quick-Edit Plugin (tiaa-quick-edit)

No configuration required — activate and it works. Enhances the WordPress Quick Edit panel
to expose the post **Order** field, used to sequence Hot Topics and Discourse Categories
posts without opening the full editor.

---

## 12. Discourse Theme Components

Both components are installed in Discourse as theme components (not standalone themes) on
top of the active Discourse theme. They must be used together.

### Installing on Discourse

1. **Discourse Admin → Customize → Themes → Install**
2. Choose **From a git repository**
3. Install `TIAA-BrandTheme-v3` (branch: `prod`)
4. Install `TIAA-DiscourseTheme-v1` (branch: `prod`)
5. For each component: go to its settings, enable it, and enable it on the active theme

### TIAA-BrandTheme-v3

Renders the branded navigation bar above the native Discourse header. Handles the
`tiaa_wp_return_url` cookie and login-state-aware nav buttons (anon vs. logged-in view
is CSS-only via the `.anon` class Discourse adds to `<html>`).

**Settings to configure** (Discourse Admin → Customize → Themes → TIAA Brand Header → Settings):

| Setting | Dev value | Prod value |
|---------|-----------|------------|
| `wp_base_url` | `http://wp-test.test` | `https://tiaa-forum.org` |
| `use_return_cookie` | `true` | `true` |
| `display_banner_graphic` | `false` | per design |
| `contribute_button_color` | match Site Settings | match Site Settings |
| `contribute_text_color` | match Site Settings | match Site Settings |

> **Keep in sync:** `wp_base_url` must match the same setting in `TIAA-DiscourseTheme-v1`.

### TIAA-DiscourseTheme-v1

Modifies the native Discourse login area. Intercepts the `#login-button` click, shows
a branded flash overlay, and redirects to `{wp_base_url}/?discourse_sso=1` instead of
native Discourse login — ensuring both systems are logged in via the SSO Client flow.
Also injects a signup link into the Discourse login area pointing to `/join` on WordPress.

**Settings to configure** (Discourse Admin → Customize → Themes → TIAA Discourse Header → Settings):

| Setting | Dev value | Prod value |
|---------|-----------|------------|
| `wp_base_url` | `http://wp-test.test` | `https://tiaa-forum.org` |
| `signup_path` | `/join` | `/join` |
| `login_screen_graphic` | upload graphic or leave empty | upload graphic |
| `login_title` | `Sign in to TIAA Forum` | per copy |
| `signup_title` | `Join TIAA Forum` | per copy |
| `welcome_back_title` | `Welcome back` | per copy |

> ⚠️ **Untested:** The returning-user detection (`localStorage` flag set after SSO callback)
> was written assuming Discourse would land with `?sso=&sig=` params in the URL. In the
> current flow, those params are on the WP callback URL, not on Discourse. The flag may
> never be set by `isSsoReturn()`. Future work: set the flag on click (before redirect)
> so returning users are detected on their next login regardless of landing URL.

---

## 13. Verifying the Full Setup

Work through this checklist after initial setup:

### Plugins
- [ ] `tiaa-wpplugin`, `tiaa-elementor`, `tiaa-quick-edit`, and WP-Discourse all active
- [ ] No PHP errors shown on the Plugins screen

### WP-Discourse
- [ ] Settings → WP Discourse → Connection: Discourse URL saved and correct
- [ ] Settings → WP Discourse → SSO Client: **Enable SSO Client** checked
- [ ] Settings → WP Discourse → SSO Client: SSO Secret set (matches Discourse `discourse_connect_provider_secrets`)

### Discourse SSO settings
- [ ] `enable_discourse_connect_provider` is **On**
- [ ] `discourse_connect_provider_secrets` contains `{your-wp-hostname}:{secret}` with the correct domain and secret
- [ ] `enable_discourse_connect` is **Off**

### tiaa-wpplugin admin
- [ ] TIAA Forum → Connection: Discourse URL, API Key, Username saved
- [ ] Connection Ping returns success
- [ ] TIAA Forum → Site Settings: Cookie Domain set correctly for this environment (leading dot required)
- [ ] TIAA Forum → Site Settings: Discourse URL field shows the correct URL (pulled from WP-Discourse)

### WordPress front-end buttons
- [ ] Sign In / Join button `href` is `/?discourse_sso=1` (not a Discourse URL directly)
- [ ] Sign In / Join button has CSS class `tiaa-sso-trigger` (Advanced tab → CSS Classes)
- [ ] GO TO FORUM button has CSS class `tiaa-go-to-forum`
- [ ] Viewing the homepage (logged out): GO TO FORUM button `href` matches Discourse URL
- [ ] Contribute button has CSS class `tiaa-contribute`
- [ ] Viewing the homepage: Contribute button background colour matches Funding Level

### SSO login flow (from WordPress)
- [ ] Click Sign In on WP → redirected to Discourse login
- [ ] Log in on Discourse → redirected back (briefly) through WP
- [ ] Land on Discourse home, logged into Discourse
- [ ] Visit WordPress — confirm WP session cookie is set (logged in on WP too)
- [ ] `tiaa_wp_return_url` cookie is present and holds the originating WP page URL
- [ ] Discourse brand header HOME link reflects the cookie value

### SSO login flow (from Discourse)
- [ ] Click Login button on Discourse → flash overlay appears → redirected to WP `/?discourse_sso=1`
- [ ] Log in on Discourse → land on Discourse home, logged into both systems

### Logout
- [ ] Click Logout on WP — confirm no "Do you really want to log out?" confirmation page
- [ ] Confirm Discourse session is also ended (requires WP-Discourse → SSO Client → Sync Logout ON)

### Discourse theme components
- [ ] TIAA-BrandTheme-v3 installed, enabled on active theme, `wp_base_url` set for environment
- [ ] TIAA-DiscourseTheme-v1 installed, enabled on active theme, `wp_base_url` set for environment
- [ ] Brand nav bar visible above native Discourse header
- [ ] Login button click on Discourse redirects to WP SSO (not native Discourse login modal)

### Invite form
- [ ] Invite form (if present): submitting a test email returns success without a console error
- [ ] Quick Edit available on Posts screen for Order field

---

## 14. Known Issues & Future Work

### Returning-user detection (TIAA-DiscourseTheme-v1)
`isSsoReturn()` detects `?sso=&sig=` params in the Discourse URL to set the returning-user
`localStorage` flag. In the current flow these params land on the WP callback URL (processed
by WP-Discourse), not on Discourse. **The flag is likely never set by this mechanism.**
Workaround / future fix: set the flag on the login button click (before redirect) so the
flag is present on the user's next visit regardless of where the SSO callback lands.

### Welcome message cron reliability
The logger (`TIAAFile.php`) is not reliably initialized in all cron call paths. Welcome
message delivery failures may not be logged. Audit the cron execution path before relying
on welcome messages in production.

### WelcomeSettings array field coercion
`WelcomeSettings.php` uses `validate_options` instead of `validate_options_blank_ok`
because the `group_list` array field gets coerced to a string under the blank-ok validator.
Side effect: Discourse credentials must be re-entered in WelcomeSettings if they are
blanked. The `validator()` method in `FormHandler` needs a deeper audit.

### TiaaLoginRedirect
`TiaaLoginRedirect.php` is a no-op in SSO Client mode. It was written when WP was the SSO
provider and intercepted the Discourse callback at `template_redirect`. WP-Discourse SSO
Client now handles the callback at `init` (priority 5) and redirects before
`template_redirect` ever fires, so this class never activates. Safe to remove if the
provider model never returns.

### Logout sync
Bidirectional logout (logging out of one system logs out of the other) requires
**WP-Discourse → SSO Client → Sync Logout** to be enabled. Not confirmed tested
end-to-end in the current configuration.

---

## 15. Troubleshooting

**Sign In button does not initiate SSO / stays on WordPress**
- Confirm the button `href` is `/?discourse_sso=1` — not a Discourse URL
- Confirm WP-Discourse SSO Client is enabled (Settings → WP Discourse → SSO Client)
- Confirm `enable_discourse_connect_provider` is ON in Discourse admin
- Confirm `enable_discourse_connect` is OFF in Discourse admin

**SSO redirects back to WP but WP is not logged in**
- Check that `discourse_connect_provider_secrets` matches the WP-Discourse SSO Secret exactly
- Confirm `enable_discourse_connect` is OFF (if ON, Discourse is in client mode, not provider mode)
- Check WordPress debug log for signature validation errors

**After SSO login, user stays on WordPress instead of going to Discourse**
- `TiaaHooks::redirect_after_sso_login` filter requires `TiaaSiteSettings::get_discourse_url()` to return a value
- Confirm WP-Discourse has a valid Discourse URL saved under Connection
- Confirm `tiaa-wpplugin` initialises at `init` priority 3 (check `TiaaBase.php`) — it must run before WP-Discourse SSO Client fires at priority 5

**Login button on Discourse opens native login modal instead of redirecting to WP**
- Confirm TIAA-DiscourseTheme-v1 is installed and enabled on the active Discourse theme
- Confirm `wp_base_url` is set correctly in the component settings
- Check browser console for JS errors

**GO TO FORUM button href is empty or wrong**
- Verify WP-Discourse is active and Discourse URL is saved under Connection
- Verify the button has CSS class `tiaa-go-to-forum`
- Check browser console for JS errors in `wp_footer`

**tiaa_wp_return_url cookie not being written / HOME link not updating on Discourse**
- Confirm the Sign In / Join button has CSS class `tiaa-sso-trigger`
- Confirm Cookie Domain in Site Settings (or `wp-config.php`) matches the environment with a leading dot
- Confirm TIAA-BrandTheme-v3 `use_return_cookie` is enabled
- In dev: confirm `isSafeDomain()` in `tiaa-brand-header.js` trusts your dev hostname (`wp-test.test`, `discourse-dev.test`) — the code was updated 2026-05-21 to use `.test` TLDs

**Invite form returns an error**
- Check TIAA Forum → Connection credentials and run the Ping test
- Check WordPress debug log — the plugin logs all Discourse API calls at debug level
- Confirm the API key has admin scope (required for invite + duplicate detection)

**Cookie domain issues (cross-subdomain cookies not working)**
- The leading dot is required: `.tiaa-forum.org` not `tiaa-forum.org`
- Dev: `.test` — Test: `.test.tiaa-forum.org` — Production: `.tiaa-forum.org`
- Use `wp-config.php` constant rather than the admin field for dev/test environments

**Plugin not loading / PHP error on activation**
- Requires PHP 8.2+ and WordPress 6.5+
- Confirm `vendor_prefixed/autoload.php` is present inside `tiaa-wpplugin` (it is committed to the repo)