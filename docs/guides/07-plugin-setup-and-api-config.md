# Plugin Setup & API Configuration Guide

**For:** Developers setting up a new local or staging environment
**Covers:** tiaa-wpplugin, tiaa-elementor, tiaa-quick-edit, and WP-Discourse configuration

---

## Overview

Three custom plugins must be present and active alongside the WP-Discourse plugin:

| Plugin | Repo | Purpose |
|--------|------|---------|
| `tiaa-wpplugin` | `github.com/tiaa-forum-org/tiaa-wpplugin` | Discourse API integration, invites, welcome messages, SSO cookies, site settings |
| `tiaa-elementor` | `github.com/tiaa-forum-org/tiaa-elementor` | Elementor invite form action, clickable Loop Grid cards |
| `tiaa-quick-edit` | `github.com/tiaa-forum-org/tiaa-quick-edit` | Quick-edit enhancements for WP admin post lists |
| WP-Discourse | WordPress.org plugin directory | SSO bridge between WordPress and Discourse |

---

## 1. Clone / Pull the Plugins

From inside `wp-content/plugins/`:

```bash
# First time — clone all three
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

WP-Discourse is the single source of truth for the Discourse URL. The custom plugins read from it — they do not store a separate copy.

---

## 3. Configure WP-Discourse Connection

**Settings → WP Discourse → Connection**

| Field | Value |
|-------|-------|
| Discourse URL | Your Discourse instance URL (e.g. `https://forum.tiaa-forum.org`) |
| API Key | A Discourse API key with **admin** scope (see Section 5 for how to generate) |
| Publishing Username | The Discourse admin username the API key belongs to |

Save. The Discourse URL set here is used throughout the site — including the GO TO FORUM button and post-SSO login redirects.

---

## 4. Configure tiaa-wpplugin — Connection Tab

**Settings → TIAA WP Plugin → Connection tab**

This tab stores the primary Discourse API credentials used by most plugin features (invites, welcome messages, admin lookups).

| Field | Value |
|-------|-------|
| Discourse URL | Same URL as WP-Discourse (e.g. `https://forum.tiaa-forum.org`) |
| API Key | Admin-scoped Discourse API key |
| API Username | The Discourse admin username |

### Testing the API Connection

After saving, use the **Ping** button on the Connection tab. It calls `/site/basic-info.json` on your Discourse instance. A successful ping returns basic site info (name, description, etc.) and confirms the URL, key, and username are all valid.

**If the ping fails:**
- Check that the Discourse URL has no trailing slash issues
- Confirm the API key has not expired or been revoked in Discourse
- Confirm the username is an active admin user in Discourse
- Check WordPress debug log for the error message (the plugin logs to the standard WP debug log)

---

## 5. Generating a Discourse API Key

In Discourse (admin must be logged in):

1. Go to **Admin → API → Keys → New API Key**
2. Set **Description**: `WordPress tiaa-wpplugin`
3. Set **User Level**: `Single User`
4. Set **User**: your admin username (e.g. `system` or the designated API user)
5. Set **Scope**: `All` (required for invites, user lookups, welcome messages, and admin user listing)
6. Click **Save** — copy the key immediately, it is only shown once

> The invite flow also checks `/admin/users/list/all.json` to detect duplicate invites, which requires admin-level API access.

---

## 6. Configure tiaa-wpplugin — Site Settings Tab

**Settings → TIAA WP Plugin → Site Settings tab**

| Field | What It Does                                                                                                                                                                                             |
|-------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Cookie Domain | Shared cookie domain for WP + Discourse subdomains. Must start with a dot: `.tiaa-forum.org` (prod), `.test.tiaa-forum.org` (staging), `.local` (dev). Can be overridden in `wp-config.php` (see below). |
| Contact Email | Site contact email used in automated messages                                                                                                                                                            |
| Funding Level | Controls Contribute button colour: `green` (healthy), `yellow` (watch), `red` (critical), `blue` (over reserves)                                                                                         |
| Discourse URL | Read-only display pulled from WP-Discourse — change it there, not here                                                                                                                                   |
| Forum Statistics | Member / topic / post counts shown on the homepage stats card. Update manually when Discourse numbers change. |
| As of | Date the statistics were last recorded. Displayed via shortcode alongside the counts so visitors know how current the numbers are. |

### Cookie Domain via wp-config.php (recommended for dev)

Add to `wp-config.php` to override the database value without touching the admin screen:

```php
define( 'TIAA_COOKIE_DOMAIN', '.test' );        // local dev
define( 'TIAA_COOKIE_DOMAIN', '.tiaa-forum.org' ); // production
```

When this constant is present, the Site Settings field is disabled and shows the active value.

### Forum Statistics Shortcodes

The four stat values are available as shortcodes for use in any Elementor text or heading widget:

| Shortcode | Outputs |
|-----------|---------|
| `[tiaa_stat field="members"]` | Member count |
| `[tiaa_stat field="topics"]` | Topic count |
| `[tiaa_stat field="posts"]` | Post count |
| `[tiaa_stat field="as_of"]` | "As of" date, formatted using the site's WordPress date format setting |

All four wrap their output in `<span class="tiaa-stats">` so they can be styled consistently. Add a rule to **Elementor → Site Settings → Custom CSS** — for example:

```css
.tiaa-stats { font-weight: 600; }
```

Update the numbers and date in the admin screen and the front end updates automatically — no code change needed.

---

## 7. Configure tiaa-wpplugin — Other Tabs

### Signup Tab
Settings for the Discourse invite email — default group, custom message, topic ID to link in the invite.

### Group Signup Tab
Per-group invite overrides. Add group names first; each group then gets its own URL, API key, and message fields (falls back to Connection tab values if left blank).

### Email Screen Tab
Blocks invite submissions from specified email addresses or domains.

### Welcome Tab
Configures automatic welcome messages sent to new Discourse members via private message. Set the Discourse API credentials, the welcome message body, and the cron schedule.

### Logging Tab
Controls log verbosity (debug / info / error). Use debug level when troubleshooting API calls; switch back to error for normal operation.

---

## 8. tiaa-elementor — Elementor Invite Form Setup

`tiaa-elementor` has no admin settings page. It activates automatically. The invite form action appears in Elementor after the plugin is active.

To set up an invite form in Elementor:

1. Add a **Form** widget to a page
2. Add an **Email** field (ID must be `email`)
3. Under **Actions After Submit**, add **TiaaInvite**
4. Under the **TiaaInvite** section that appears, set **Fetch timeout** (default 5000ms is fine)
5. The form submits to the REST endpoint `/tiaa_wpplugin/v1/invite` — this is wired automatically

The form handler JS (`assets/js/form-handler.js`) is only enqueued on pages that contain a form widget using the `tiaa` submit action.

---

## 9. Button CSS Classes

These CSS classes are set in Elementor's **Advanced tab → CSS Classes** field on the relevant button widget. The plugin injects behaviour via JavaScript based on these classes — no manual `href` needed.

### GO TO FORUM button

**Class:** `tiaa-go-to-forum`

Add this to the GO TO FORUM button widget in the **Header template**. The plugin automatically sets the button's `href` to the Discourse URL configured in WP-Discourse. The script runs on every front-end page footer.

**How it works:** The injected script targets both `.tiaa-go-to-forum a` (Elementor wraps buttons in a div with the class, not the `<a>` directly) and `a.tiaa-go-to-forum`.

**Where to set it:** Elementor → Theme Builder → Header → GO TO FORUM button widget → Advanced tab → CSS Classes → `tiaa-go-to-forum`

### CONTRIBUTE button

**Class:** `tiaa-contribute`

Add this to the Contribute button widget in the **Header template**. The plugin reads the **Funding Level** from Site Settings and outputs an inline `<style>` block in `<head>` on every front-end page, setting the button's background colour automatically.

| Funding Level | Background | Text |
|---------------|------------|------|
| Green | `#28a745` | white (default) |
| Yellow | `#ffc107` | `#000000` (black) |
| Red | `#dc3545` | white (default) |
| Blue | `#007bff` | white (default) |

**How it works:** The plugin targets both `.tiaa-contribute a` (Elementor wraps the class on a div) and `a.tiaa-contribute`. The colour updates immediately when the Funding Level is saved in Site Settings — no code change or cache clear needed.

**Where to set it:** Elementor → Theme Builder → Header → Contribute button widget → Advanced tab → CSS Classes → `tiaa-contribute`

---

## 10. Loop Grid — Clickable Cards

No CSS class needed. `tiaa-elementor` automatically makes all Elementor Loop Grid cards (`.e-loop-item`) fully clickable by overlaying a transparent anchor that links to the first `<a>` found inside the card. This affects all Loop Grids site-wide (Hot Topics, Forum Categories, etc.).

---

## 11. Quick-Edit Plugin (tiaa-quick-edit)

`tiaa-quick-edit` enhances the WordPress admin Quick Edit panel for posts. No configuration required — activate and it works. Used primarily for setting post Order values on Hot Topics and Discourse Categories posts without opening the full editor.

---

## 12. Verifying the Full Setup

Work through this checklist after initial setup:

- [ ] All three custom plugins active (Plugins screen shows no errors)
- [ ] WP-Discourse active and Discourse URL saved
- [ ] TIAA WP Plugin → Connection tab: Discourse URL, API Key, Username saved
- [ ] Connection tab Ping returns success
- [ ] Site Settings tab: Cookie Domain set correctly for the environment
- [ ] Site Settings tab: Discourse URL field shows the correct URL (pulled from WP-Discourse)
- [ ] GO TO FORUM button in header has CSS class `tiaa-go-to-forum`
- [ ] Viewing the homepage: GO TO FORUM button href matches Discourse URL
- [ ] Contribute button in header has CSS class `tiaa-contribute`
- [ ] Viewing the homepage: Contribute button background colour matches the Funding Level set in Site Settings
- [ ] Invite form (if present): submitting a test email reaches Discourse without a console error
- [ ] Quick Edit available on Posts screen for Order field

---

## Troubleshooting

**GO TO FORUM button href is empty or wrong**
- Verify WP-Discourse is active and the Discourse URL is saved under Settings → WP Discourse → Connection
- Verify the button widget has the CSS class `tiaa-go-to-forum` (Advanced tab, CSS Classes field)
- Check browser console for JS errors in `wp_footer`

**Invite form returns an error**
- Check TIAA WP Plugin → Connection tab credentials
- Run the Ping test
- Check WordPress debug log — the plugin logs all Discourse API calls at debug level
- Confirm the API key has sufficient scope (must be admin-level for invite + duplicate detection)

**Cookie domain issues (SSO not working across subdomains)**
- Confirm `TIAA_COOKIE_DOMAIN` constant in `wp-config.php` matches the environment
- For local dev: `.local` — for staging: `.test.tiaa-forum.org` — for production: `.tiaa-forum.org`
- The leading dot is required

**Plugin not loading / PHP error on activation**
- Requires PHP 8.2+ and WordPress 6.5+
- Check that the `vendor_prefixed/autoload.php` file exists inside `tiaa-wpplugin` (it should be committed to the repo)