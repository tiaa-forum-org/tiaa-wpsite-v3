# Deployment Checklist: test-v3 → Staging → Production
*TIAA Forum v3 — DigitalOcean VPS / Docker Compose*
*Checklist version 3.0 — July 2026*

---

## Architecture Summary

```
test-v3 Docker project
    → Duplicator package
        → Staging WP service (inside prod Docker project, same DB container, new database)
            → verify all systems
                → Production WP service (Duplicator import)
```

**Staging** = a new WordPress service added to the existing prod `docker-compose.yml`. It shares the existing MySQL container (new `tiaa_staging` database). It gets its own nginx server block and SSL cert. It is accessed at `staging.tiaa-forum.org` (CNAME).

---

## PHASE 0 — Pre-Flight: Confirm test-v3 Is Ready

- [ ] All Elementor templates final and working (header, footer, single post, archive, loop items)
- [ ] Elementor templates exported to Git (`/exports/elementor/`) as a snapshot
- [ ] Custom plugins at correct tagged versions and pushed to GitHub:
    - `tiaa-wpplugin`
    - `tiaa-elementor`
    - `tiaa-quick-edit`
- [ ] All 10 Hot Topics posts present; `menu_order` values correct (see `HotTopicPostOrder.md`)
- [ ] ACF Forum Thread URL fields populated on all posts
- [ ] Static back-navigation links in single post template working (not Yoast — static links in template)
- [ ] Cookie-based conditional nav confirmed working (`tiaa-member` / `tiaa_member`)
- [ ] No PHP errors in `wp-content/debug.log`
- [ ] Mobile layout verified on real device
- [ ] Discourse test instance (`discourse-f2.test.tiaa-forum.org`) is running v3 brand header — confirm before proceeding
- [ ] Agree on maintenance window; notify team

---

## PHASE 1 — Backups

### 1.1 Backup test-v3 with Duplicator

- [ ] WP admin → Duplicator → Packages → Create New
- [ ] Run the scanner; resolve any warnings (large files, path issues)
- [ ] Build the package
- [ ] Download **both files** (`installer.php` + `.zip`) to local machine

> ⚠️ Confirm the Duplicator output directory is on a bind-mounted volume so files are reachable from the host. If not, use `docker cp` to extract them before the container is removed.

### 1.2 Backup Production with Duplicator

- [ ] WP admin (prod) → Duplicator → Create New package
- [ ] Build and download both files to local machine
- [ ] Store off-VPS (local drive or DigitalOcean Spaces)

### 1.3 VPS Droplet Snapshot

- [ ] DigitalOcean console → Droplets → your droplet → Snapshots → Take Snapshot
- [ ] Wait for completion before proceeding — this is your last-resort full rollback

---

## PHASE 2 — Provision Staging Inside the Prod Docker Project

### 2.1 Create Staging Database via phpMyAdmin

In phpMyAdmin (connected to the existing prod MySQL container):

- [ ] Create new database: `tiaa_staging` (collation: `utf8mb4_unicode_ci`)
- [ ] Create new user: `wp_staging`
- [ ] Grant `wp_staging` full privileges on `tiaa_staging` only (not global)
- [ ] **Record credentials** — add to `wp-config.php` constants and store in your password manager:
  ```
  DB: tiaa_staging
  User: wp_staging
  Password: <generate a strong password>
  Host: db  (the existing mysql service name in docker-compose.yml)
  ```

### 2.2 Add Staging WP Service to `docker-compose.yml`

Duplicate the existing prod `wordpress` service block. Change only what must differ:

```yaml
  wp-staging:
    image: wordpress:latest          # same image as prod
    container_name: tiaa-wp-staging  # must differ from prod container name
    depends_on:
      - db                           # reuse existing db service — no second db container
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_NAME: tiaa_staging
      WORDPRESS_DB_USER: wp_staging
      WORDPRESS_DB_PASSWORD: <wp_staging password>
    volumes:
      - ./staging-wp-content:/var/www/html/wp-content  # separate directory from prod
    ports:
      - "8081:80"   # pick a free host port; prod is on a different port
    restart: unless-stopped
```

> Do NOT add a second `db` service. The existing MySQL container handles both databases.

- [ ] Service block added to `docker-compose.yml`
- [ ] Start staging service only: `docker compose up -d wp-staging`
- [ ] Confirm container running: `docker compose ps`

### 2.3 nginx Server Block for Staging

Add a new server block to your nginx config on the host:

```nginx
server {
    listen 80;
    server_name staging.tiaa-forum.org;

    location / {
        proxy_pass http://localhost:8081;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

- [ ] nginx server block created
- [ ] Config test: `nginx -t`
- [ ] Reload: `systemctl reload nginx`

### 2.4 DNS — CNAME Record

- [ ] Add CNAME record: `staging.tiaa-forum.org → tiaa-forum.org`
- [ ] Confirm resolution (allow a few minutes): `dig staging.tiaa-forum.org`

### 2.5 SSL via Certbot

Run **after** DNS resolves and nginx is serving the `server_name`:

```bash
certbot --nginx -d staging.tiaa-forum.org
```

- [ ] Certbot issues cert and updates nginx for HTTPS
- [ ] `https://staging.tiaa-forum.org` loads (WP install screen or default WP page is fine at this point)

---

## PHASE 3 — Seed Staging from test-v3 via Duplicator

### 3.1 Copy Duplicator Package to Staging Container

```bash
docker cp /path/to/installer.php tiaa-wp-staging:/var/www/html/installer.php
docker cp /path/to/package.zip   tiaa-wp-staging:/var/www/html/package.zip
```

### 3.2 Run Duplicator Installer

- [ ] Open `https://staging.tiaa-forum.org/installer.php` in browser
- [ ] Step 1: Accept terms, confirm package detected
- [ ] Step 2 (Database): Enter staging credentials (`tiaa_staging`, `wp_staging`, host = `db`)
- [ ] Step 3 (Update Paths): Set URL to `https://staging.tiaa-forum.org`
- [ ] Run the install
- [ ] Install completes without errors

### 3.3 Fix Serialized URL Data

Duplicator updates `wp_options` (`siteurl`, `home`) during import. However, serialized Elementor data in `wp_postmeta` may still contain the old test-v3 URL.

> ⚠️ **Do not use phpMyAdmin for this replace.** Serialized PHP data embeds string lengths — a raw SQL find-replace will corrupt Elementor templates silently. Use WP-CLI or Better Search Replace.

```bash
docker exec tiaa-wp-staging wp search-replace 'https://old-test-v3-domain.com' 'https://staging.tiaa-forum.org' --skip-columns=guid --allow-root
```

- [ ] Search-replace run
- [ ] Verify: `docker exec tiaa-wp-staging wp option get siteurl --allow-root` → returns `https://staging.tiaa-forum.org`
- [ ] Open a single Hot Topic post page — if it loads without broken layout, serialized data is clean

> phpMyAdmin is fine for *verifying* `wp_options` rows after the replace — not for performing it.

### 3.4 Verify Plugins Are Active

All plugins came in with the Duplicator import from test-v3 — no reinstall needed. Just verify they are active:

- [ ] WP admin → Plugins: confirm all expected plugins are active:
    - Elementor + Elementor Pro
    - WP-Discourse
    - WP SimplePay
    - ACF
    - Yoast SEO (if still used for SEO meta/sitemap — not for breadcrumbs)
    - tiaa-wpplugin
    - tiaa-elementor
    - tiaa-quick-edit
    - phpMyAdmin (if installed as a plugin — otherwise access via container)

### 3.5 Elementor Pro License

Elementor Pro licenses allow activations for staging/dev instances. Try activating on `staging.tiaa-forum.org` first.

**Decision point:**
- [ ] **If Elementor allows it:** Activate on staging (Elementor → License → Activate). Keep test-v3 active for ongoing dev.
- [ ] **If Elementor rejects it** (license limit reached): Use `staging.test.tiaa-forum.org` as the staging URL instead. Update DNS CNAME, Certbot, and nginx accordingly. Update URL search-replace to match.

- [ ] Elementor Pro status confirmed active on staging
- [ ] Theme Builder accessible (confirms Pro is working)

### 3.6 Flush Permalinks

- [ ] WP admin → Settings → Permalinks → Save (no change needed — just flush)

---

## PHASE 4 — Configure Staging Environment

### 4.1 `wp-config.php` for Staging

```bash
docker exec -it tiaa-wp-staging bash
nano /var/www/html/wp-config.php
```

```php
define('WP_HOME',    'https://staging.tiaa-forum.org');
define('WP_SITEURL', 'https://staging.tiaa-forum.org');

// Discourse — test instance for staging
define('TIAA_DISCOURSE_URL',          'https://discourse-f2.test.tiaa-forum.org');
define('TIAA_DISCOURSE_API_KEY',      '<staging-discourse-api-key>');
define('TIAA_DISCOURSE_API_USERNAME', '<staging-api-username>');

// Stripe — TEST keys on staging
define('TIAA_STRIPE_PUBLISHABLE_KEY', 'pk_test_...');
define('TIAA_STRIPE_SECRET_KEY',      'sk_test_...');

// SES SMTP
define('TIAA_SES_SMTP_USER', '<ses-smtp-user>');
define('TIAA_SES_SMTP_PASS', '<ses-smtp-pass>');

// WP Cron fix for Docker
define('ALTERNATE_WP_CRON', true);

// Debug on during staging
define('WP_DEBUG',         true);
define('WP_DEBUG_LOG',     true);
define('WP_DEBUG_DISPLAY', false);
```

- [ ] Saved; restart container if needed: `docker compose restart wp-staging`

### 4.2 Get New Discourse API Credentials (Staging)

From the test Discourse admin panel (`discourse-f2.test.tiaa-forum.org/admin`):

- [ ] Admin → API → New API Key
- [ ] Scope: global or scoped to required endpoints
- [ ] Username: use the Discourse admin account username
- [ ] Copy key into staging `wp-config.php` above

### 4.3 WP-Discourse Plugin Settings (Staging)

WP admin → Settings → Discourse:

- [ ] Discourse URL = `https://discourse-f2.test.tiaa-forum.org`
- [ ] API key + username match what was just created
- [ ] SSO enabled; SSO secret matches test Discourse `discourse_connect_secret`
- [ ] WP-Discourse logout setting enabled (so WP logout also ends Discourse session)

### 4.4 WP SimplePay — Stripe Test Mode (Staging)

- [ ] WP SimplePay → Settings → Stripe keys = test keys (`pk_test_...` / `sk_test_...`)
- [ ] Confirm SimplePay settings panel keys match `wp-config.php` constants (some versions store separately in `wp_options`)
- [ ] Run a test payment on `/join` using card `4242 4242 4242 4242`
- [ ] Confirm test charge appears in Stripe **test** dashboard
- [ ] Confirm post-payment flow: Discourse account created, SSO initiates

### 4.5 Amazon SES — Admin Email (Staging)

Configure in `tiaa-wpplugin` via `phpmailer_init` hook (preferred — no extra plugin):

```php
add_action('phpmailer_init', function($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'email-smtp.us-east-1.amazonaws.com';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = 587;
    $phpmailer->Username   = defined('TIAA_SES_SMTP_USER') ? TIAA_SES_SMTP_USER : '';
    $phpmailer->Password   = defined('TIAA_SES_SMTP_PASS') ? TIAA_SES_SMTP_PASS : '';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From       = 'noreply@tiaa-forum.org';
    $phpmailer->FromName   = 'TIAA Forum';
});
```

> ⚠️ **SES sandbox:** New accounts can only send to verified addresses. If SES production access hasn't been requested yet, do it now — approval can take up to 24 hours.

- [ ] SES credentials in `wp-config.php`
- [ ] Hook added to `tiaa-wpplugin` (or SMTP plugin configured if preferred)
- [ ] Test email sent and received

### 4.6 Discourse v3 Brand Header (Staging)

The Discourse test instance must be running the v3 brand header before staging SSO testing.

- [ ] Confirm v3 brand header/theme component is installed on test Discourse
- [ ] Discourse admin → Appearance → Themes → confirm v3 theme active
- [ ] Visual spot-check: visit test Discourse in browser — header looks correct

### 4.7 Matomo — Disable on Staging

Staging pageviews should not pollute prod analytics.

- [ ] Disable Matomo tracking on staging (gate it with a constant in `wp-config.php` if `tiaa-wpplugin` controls the tracking snippet)
  ```php
  define('TIAA_MATOMO_ENABLED', false);
  ```
- [ ] Or: point staging to a separate Matomo site ID

### 4.8 Yoast — Confirm Not Noindexing (If Yoast Is Active)

- [ ] WP admin → Yoast SEO → Settings → confirm NOT set to discourage search engines
- [ ] Spot-check a public page `<head>` for `noindex` — should not be present on staging (or should, if you want to block staging from search — your call, but be deliberate)

---

## PHASE 5 — Staging Verification

Fix any issues on test-v3, re-export Duplicator package, re-import to staging. Do not patch staging directly except for config-only fixes.

### 5.1 Core Site
- [ ] Homepage loads at `https://staging.tiaa-forum.org`
- [ ] All 5 homepage sections render
- [ ] `[tiaa_stat]` shortcodes display numbers (not raw shortcode text)
- [ ] Footer renders correctly

### 5.2 Navigation — Anonymous State
- [ ] JOIN/SIGN IN button (coral) visible
- [ ] GO TO FORUM, CONTRIBUTE, logout hidden
- [ ] All static nav links resolve (no 404s)

### 5.3 Navigation — Logged-In State
- [ ] Manually set `tiaa_member` cookie in DevTools → refresh
- [ ] Header switches: GO TO FORUM (teal) + CONTRIBUTE (coral) + logout visible
- [ ] JOIN/SIGN IN hidden

### 5.4 Discourse SSO
- [ ] Click sign-in → redirects to test Discourse → returns to staging after auth
- [ ] Post-login header state correct
- [ ] Logout → session ends; anonymous state restored

### 5.5 Hot Topics
- [ ] `/hot-topics` archive: all 10 posts visible in 3-column grid
- [ ] Cards display featured images
- [ ] Sort order correct (`menu_order=1` appears first)
- [ ] Single post template loads: title, featured image right, body, ACF forum link
- [ ] Static back-navigation link at bottom of single post works (links back to `/hot-topics`)

### 5.6 Join / Payment Flow
- [ ] `/join` page loads; WP SimplePay form visible (Stripe test mode)
- [ ] Test card `4242 4242 4242 4242` processes successfully
- [ ] Post-payment: Discourse account created, SSO flow initiates
- [ ] Welcome message email received (confirms SES working)

### 5.7 tiaa-wpplugin
- [ ] Discourse API call succeeds (no PHP errors after join flow)
- [ ] Clickable cards JS working on homepage (clicking a Loop Grid card navigates to post)
- [ ] No PHP errors in `wp-content/debug.log`

### 5.8 Other Pages
- [ ] `/about-us` loads
- [ ] `/contact-us` loads; contact form submits and email received
- [ ] `/contribute`: anonymous → blocked/redirected; logged-in member → accessible

### 5.9 Mobile
- [ ] Homepage on real device: all sections stack correctly
- [ ] Mobile header: hamburger opens, nav accessible
- [ ] `/hot-topics` cards readable
- [ ] `/join` form usable on mobile

### 5.10 Sign-Off
- [ ] Lew reviews staging and approves
- [ ] At least one volunteer reviews on mobile
- [ ] Sign-off recorded (Slack or email)

---

## PHASE 6 — Production Cutover

> ⚠️ **Point of no return.** Have Phase 7 rollback steps open in a second window before starting.

### 6.1 Enable Maintenance Mode on Production
```bash
docker exec tiaa-wp-prod wp maintenance-mode activate --allow-root
```
- [ ] Maintenance page confirmed at `https://tiaa-forum.org`

### 6.2 Final Production Backup (Right Now, Fresh)
- [ ] Create a new Duplicator package on prod (captures any changes since Phase 1)
- [ ] Download both files to local machine — this is your cutover rollback point

### 6.3 Confirm Discourse Live Instance Is Running v3 Brand Header

This must be done before cutover — the production Discourse instance must show the v3 header when members visit the forum.

- [ ] Identify source of v3 theme component (is it in test Discourse? In a GitHub repo?)
- [ ] Export v3 theme component from test Discourse: Admin → Appearance → Themes → Export
- [ ] Import to live Discourse: Admin → Appearance → Themes → Import
- [ ] Activate v3 theme on live Discourse
- [ ] Visual spot-check at live Discourse URL

> ⚠️ This is a separate deploy step for Discourse — it is independent of the WordPress cutover but must happen before or during the same window.

### 6.4 Create Fresh Duplicator Package from Staging

This is what you'll install on prod — a clean package from the verified staging instance.

- [ ] Staging WP admin → Duplicator → Create New package
- [ ] Download both files to local machine

### 6.5 Import Staging Duplicator Package into Production

```bash
docker cp /path/to/installer.php tiaa-wp-prod:/var/www/html/installer.php
docker cp /path/to/package.zip   tiaa-wp-prod:/var/www/html/package.zip
```

- [ ] Open `https://tiaa-forum.org/installer.php`
- [ ] Step 2 (Database): enter prod DB credentials
- [ ] Step 3 (Update Paths): set URL to `https://tiaa-forum.org`
- [ ] Run install; completes without errors

### 6.6 URL Search-Replace for Production
```bash
docker exec tiaa-wp-prod wp search-replace 'https://staging.tiaa-forum.org' 'https://tiaa-forum.org' --skip-columns=guid --allow-root
```
- [ ] Verify: `wp option get siteurl` → `https://tiaa-forum.org`

### 6.7 `wp-config.php` for Production

```php
define('WP_HOME',    'https://tiaa-forum.org');
define('WP_SITEURL', 'https://tiaa-forum.org');

// Discourse — production instance
define('TIAA_DISCOURSE_URL',          'https://forum.tiaa-forum.org');
define('TIAA_DISCOURSE_API_KEY',      '<PROD-discourse-api-key>');
define('TIAA_DISCOURSE_API_USERNAME', '<prod-api-username>');

// Stripe — LIVE keys
define('TIAA_STRIPE_PUBLISHABLE_KEY', 'pk_live_...');
define('TIAA_STRIPE_SECRET_KEY',      'sk_live_...');

// SES (same credentials as staging if using same SES account)
define('TIAA_SES_SMTP_USER', '<ses-smtp-user>');
define('TIAA_SES_SMTP_PASS', '<ses-smtp-pass>');

define('ALTERNATE_WP_CRON', true);
define('WP_DEBUG',           false);
```

- [ ] Saved

### 6.8 Get New Discourse API Credentials (Production)

From live Discourse admin:

- [ ] Admin → API → New API Key
- [ ] Copy into prod `wp-config.php`

### 6.9 WP-Discourse Settings (Production)

- [ ] Discourse URL = live Discourse instance
- [ ] API key + username = production credentials
- [ ] SSO secret matches live Discourse `discourse_connect_secret`

### 6.10 WP SimplePay — Switch to Live Stripe

- [ ] Update prod `wp-config.php` Stripe constants to `pk_live_...` / `sk_live_...`
- [ ] WP SimplePay → Settings: verify the plugin settings panel also shows live keys (check `wp_options` if unsure — some versions store separately)
- [ ] Confirm form is in live mode (no "test mode" badge on the form)

### 6.11 Elementor Pro License on Production

- [ ] If staging license was activated and prod is a different domain: Elementor → License → Activate on prod
- [ ] Confirm Theme Builder accessible

### 6.12 Flush Permalinks on Production
- [ ] WP admin → Settings → Permalinks → Save

### 6.13 Disable Maintenance Mode
```bash
docker exec tiaa-wp-prod wp maintenance-mode deactivate --allow-root
```

---

## PHASE 7 — Post-Cutover Verification (Production)

### 7.1 Smoke Test
- [ ] `https://tiaa-forum.org` loads; SSL valid
- [ ] Homepage all 5 sections display
- [ ] Anonymous nav state correct
- [ ] `/hot-topics` archive loads; all cards display
- [ ] Single Hot Topic post opens; static back-link works
- [ ] `/join` loads; Stripe form in **live** mode

### 7.2 Auth Smoke Test
- [ ] Discourse SSO login → header switches to logged-in state
- [ ] Logout → anonymous state

### 7.3 Stripe Live Transaction Test

- [ ] Process a real payment on `/join` (smallest amount the form allows)
- [ ] Charge appears in Stripe **live** dashboard
- [ ] Welcome email received via SES
- [ ] Refund the charge immediately in Stripe

### 7.4 Discourse Brand Header
- [ ] Visit live Discourse — v3 brand header showing correctly
- [ ] SSO login from WP → Discourse → returns to WP: header transition correct

### 7.5 Monitor (First 30 Minutes)
- [ ] `docker exec tiaa-wp-prod tail -f /var/www/html/wp-content/debug.log`
- [ ] `tail -f /var/log/nginx/error.log`
- [ ] WP admin → Settings → Discourse → Logs (check for SSO errors)
- [ ] Stripe dashboard: no unexpected errors

---

## PHASE 8 — Rollback Plan

### Staging only (Phases 3–5): Production untouched.
Fix on test-v3 → re-export Duplicator → re-import to staging → re-verify.

### Production affected (Phase 6+):
```bash
# 1. Maintenance mode immediately
docker exec tiaa-wp-prod wp maintenance-mode activate --allow-root

# 2. Reset database
docker exec tiaa-wp-prod wp db reset --yes --allow-root

# 3. Restore Phase 6.2 pre-cutover backup
docker cp /backup/installer.php tiaa-wp-prod:/var/www/html/installer.php
docker cp /backup/package.zip   tiaa-wp-prod:/var/www/html/package.zip
# Open https://tiaa-forum.org/installer.php → restore with prod credentials

# 4. Confirm wp-config.php is back to prod v2 values

# 5. Disable maintenance mode
docker exec tiaa-wp-prod wp maintenance-mode deactivate --allow-root
```

### Last resort:
Restore entire droplet from Phase 1.3 DigitalOcean snapshot. Reverts all containers, nginx, certs.

---

## PHASE 9 — Post-Launch Wrap-Up

- [ ] Delete `installer.php` and `.zip` from prod WP root:
  ```bash
  docker exec tiaa-wp-prod rm /var/www/html/installer.php /var/www/html/package.zip
  ```
- [ ] Export all Elementor templates from prod → commit to `tiaa-wpsite-v3/exports/elementor/`
- [ ] Tag a release in all three plugin repos
- [ ] Update `02-environments-and-deployment.md` to reflect:
    - Staging-inside-prod-project pattern
    - Duplicator as backup/restore method
    - Staging URL and CNAME approach
- [ ] Update project instructions document:
    - Remove Yoast breadcrumb references from Hot Topics description
    - Clarify Yoast role (SEO meta/sitemap only — no breadcrumbs)
    - Add staging environment to environments table
- [ ] Decide staging fate: stop containers (`docker compose stop wp-staging`) but keep compose config for future use
- [ ] Post team notification: site is live, what changed, who to contact
- [ ] Schedule first-week check-in: analytics, error logs, user issues

---

## Config Audit: Constants Across Environments

Verify before Phase 6 that no staging value carries over to prod:

| Constant | test-v3 | Staging | Production |
|---|---|---|---|
| `WP_HOME` / `WP_SITEURL` | test-v3 URL | `staging.tiaa-forum.org` | `tiaa-forum.org` |
| `TIAA_DISCOURSE_URL` | test Discourse | test Discourse | **live Discourse** |
| `TIAA_DISCOURSE_API_KEY` | test key | test key | **prod key** |
| Stripe keys | test | test | **live** |
| SES credentials | n/a | staging SES | prod SES |
| `WP_DEBUG` | true | true | **false** |
| `ALTERNATE_WP_CRON` | true | true | true |
| Matomo | disabled/dev | disabled | **prod site ID** |

---

## Documentation Updates Triggered by This Deployment

The following project docs need updating after launch:

| Document | What to Update |
|---|---|
| `02-environments-and-deployment.md` | Add staging environment, Duplicator method, CNAME pattern |
| Project instructions (this file's source) | Remove Yoast breadcrumb references; clarify Yoast role; add staging to environments table |
| `05-hot-topics-system.md` | Replace Yoast breadcrumb instructions with static link instructions |
| `03-content-model.md` | Still stale re: CPT — update to confirm Posts-based implementation |
| `IMPLEMENTATION-ROADMAP.md` | Phase status is outdated — update or retire |

---

## Quick Reference: Key Commands

```bash
# Start staging only
docker compose up -d wp-staging

# WP-CLI
docker exec tiaa-wp-staging wp <command> --allow-root
docker exec tiaa-wp-prod    wp <command> --allow-root

# URL search-replace (safe for serialized data)
wp search-replace 'https://old.com' 'https://new.com' --skip-columns=guid --allow-root

# Check siteurl
wp option get siteurl --allow-root

# Flush permalinks
wp rewrite flush --allow-root

# Maintenance mode
wp maintenance-mode activate --allow-root
wp maintenance-mode deactivate --allow-root

# Tail WP debug log
docker exec tiaa-wp-prod tail -f /var/www/html/wp-content/debug.log

# Tail nginx error log
tail -f /var/log/nginx/error.log
```
