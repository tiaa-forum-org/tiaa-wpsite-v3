# WP Test Environment — Claude Code Context
# Canonical location: tiaa-wpsite-v3/docs/wp-env-context.md
# Last updated: 2026-05-28

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Environment

This is a WordPress local development environment running in Docker. Key config:
- Site URL: `http://wp-test.local/`
- Database host: `mysql_dev` (Docker container), database: `wp-test`
- PHP 8.2+ required
- WP_DEBUG is enabled; `DISABLE_WP_CRON` is true (external cron)
- PHP memory limit: 768M, max execution: 600s

## Custom Plugins

All active custom development lives under `wp-content/plugins/`. Each plugin is its own git repo.

### tiaa-wpplugin (main plugin) — current version: 0.0.7
WordPress/Discourse integration. Handles user invitations, welcome messages, screened emails, group management, cookie management, and SSO redirect.

REST API namespace: `tiaa_wpplugin/v1`.

- Entry point: `tiaa-wpplugin.php` — defines constants, loads lib files, instantiates `TiaaBase`
- `lib/TiaaBase.php` — base class; wires up all lib classes via `initialize_plugin()`
- `lib/TiaaHooks.php` — registers all REST API routes via `rest_api_init`
- `lib/Discourse.php` — all Discourse API calls
- `lib/ScreenEmailsUtil.php` / `lib/WelcomeUtil.php` — feature-specific utilities
- `lib/TiaaSiteSettings.php` — "Site Settings" admin tab; cookie domain, contact email, funding level, forum stats, Discourse URL (read from WP-Discourse). Shortcodes: `[tiaa_stat field="members|topics|posts"]`. Also outputs `<script id="tiaa-forum-url">` to set `.tiaa-go-to-forum` button href at runtime.
- `lib/TiaaReturnUrlCookie.php` — writes `tiaa_wp_return_url` cookie on `.tiaa-sso-trigger` click (short-lived, 1 hr); Discourse brand header reads it to return user to originating WP page after SSO login.
- `lib/TiaaMemberCookie.php` — writes `tiaa_member` cookie on first logged-in page load (1 yr, persists after logout); adds `tiaa-returning-member` body class when cookie present.
- `lib/TiaaLoginRedirect.php` — hooks `template_redirect` at priority 20; detects Discourse SSO callback via `?sso=&sig=` params and issues 302 redirect to Discourse home, eliminating the logged-in landing page flash.
- `admin/` — settings pages; each settings group has its own `*Settings.php` class

PHP namespace: `TIAAPlugin\lib`. Vendor deps (only `analog/analog`) are prefixed under `TIAAPlugin` and committed to `vendor_prefixed/` — do not use the standard `vendor/` directory for this plugin.

Option group constants are defined in `tiaa-wpplugin.php` (e.g. `TIAA_CONNECT_GROUP`, `TIAA_INVITE_GROUP`).

Cookie domain is read from `TiaaSiteSettings::get_cookie_domain()` — checks for `TIAA_COOKIE_DOMAIN` constant in `wp-config.php` first, then falls back to the `tiaa_cookie_domain` option. Set the constant in `wp-config.php` for dev/staging to avoid touching the database.

Elementor CSS classes wired to plugin behaviour (set in Header template 1480, Advanced → CSS Classes):
- `.tiaa-sso-trigger` — JOIN/SIGN IN button; triggers return-URL cookie write
- `.tiaa-go-to-forum` — GO TO FORUM button; href set to Discourse URL at runtime

### tiaa-quick-edit — current version: 1.5.2
Adds Sort Order (`menu_order`) and Excerpt (`post_excerpt`) fields to the WordPress Quick Edit panel for posts in the `hot-topics` and `discourse-categories` categories, and for all Pages. Adds a sortable Sort Order column to the Posts and Pages list tables.

Uses post-type-specific hooks (`manage_post_posts_columns`, `manage_page_posts_columns`) — not the broad `manage_posts_columns` — so the column does not appear on Elementor templates or other CPT list screens.

Target categories defined in `TIAA_QE_CATEGORY_SLUGS` constant at top of `tiaa-quick-edit.php`.

### tiaa-elementor-forms-invite-action
Adds a custom Elementor Pro form action called "TIAA Invite" that POSTs to the plugin's `/invite` REST endpoint. Also makes Elementor Loop Grid cards clickable. JS lives in `assets/js/form-handler.js`.

### tiaa-manage-options-users
Utility plugin for managing WordPress option settings for users.

## Code Style

- OO PHP following WordPress conventions (hooks, options API, REST API)
- PHPDoc docblock author: `Lew Grothe, TIAA Admin Platform sub-team`, email: `info@tiaa-forum.org`, link: `https://tiaa-forum.org/contact`
- TypeScript config targets ES5 with CommonJS modules (`tsconfig.json` at root)

## Dependency Management

Root `composer.json` manages WordPress plugins/themes via wpackagist and elementor-pro via their Composer repo.

The `tiaa-wpplugin` plugin has its own `composer.json` using [PHP-Prefixer](https://php-prefixer.com/) to namespace vendor libs under `TIAAPlugin`. After adding a new Composer dependency to the plugin, run PHP-Prefixer to regenerate `vendor_prefixed/`.

## REST API Testing

`Discovery.http` at the repo root is an IntelliJ HTTP Client file for manual REST API testing. It is not committed to any repo. Key endpoints:

```
GET  http://wp-test.local/wp-json/tiaa_wpplugin/v1/tiaa_discourse_ping?option_group=tiaa_connection
POST http://wp-test.local/wp-json/tiaa_wpplugin/v1/invite
GET  http://wp-test.local/wp-json/tiaa_wpplugin/v1/get_discourse_post?post_id=...&option_group=tiaa_invite
```

## Plugin Packaging

Each plugin has a `bin/package.sh` that creates a dated backup ZIP.

To package `tiaa-wpplugin`:
```bash
cd wp-content/plugins/tiaa-wpplugin/bin
./package.sh
```
Saves to `~/tmp/tiaa-backup/`.
