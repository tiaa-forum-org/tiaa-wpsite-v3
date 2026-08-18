# F1–F8 Manual Verification Runbook

> **CLOSED 2026-08-18.** All 8 findings verified in a real browser against
> wp-test, including two regressions this pass caught and fixed:
> `tiaa-wpplugin` `ad97dac` (F2 — admin links needed a `wp_rest` nonce) and
> `34055a2` (F8 — log download returned an empty body). The F7 documentation
> follow-up in `tiaa-elementor` also landed (`6c8a4f3`). Archived here as a
> historical record — not an active checklist. `tiaa-wpplugin` is at
> `v0.0.14`; `tiaa-quick-edit` and `tiaa-wpplugin` also both lowered
> `Requires PHP` to match real-world environments (see each plugin's
> `CLAUDE.md`, flagged there for revisiting).

Manual QA companion to `SECURITY-REVIEW.md`. Every fix (tiaa-wpplugin v0.0.13,
commits `6182147..d13a3af` on `main`) was verified with a scripted test against
the wp-test Docker container before being committed. This walks through the
same checks by hand, in a real browser, against the live site — so there's
independent confirmation before calling any item done.

**Site:** `https://wp-test.test` · **Admin account:** any `manage_options`
user · **Second account:** a subscriber, or an incognito window · **Discourse
(for F5):** `https://discourse-dev.test`

Before you start:
- Log in to wp-test.test as an administrator in your normal browser window —
  most steps assume that session.
- A few steps ask you to act as a **non-admin** or **logged-out** visitor. Use
  a private/incognito window for those.
- Steps marked **Simulate an attacker** have you open a small local HTML file
  or paste JavaScript into the browser console. That's the standard, safe way
  to reproduce a CSRF or cross-origin probe against your own site — nothing
  here reaches outside wp-test.test / discourse-dev.test.
- If anything doesn't match its expected result, stop there and flag it
  before treating that fix as verified — don't continue down the list
  assuming it's fine.

| # | What it fixed |
|---|---|
| F1 | Arbitrary table read in the CSV export handler |
| F2 | Public route was reading Discourse posts with privileged credentials |
| F3 | No CSRF protection on screened-email add/delete/import |
| F4 | Dead code could write a CSV to an attacker-chosen server path |
| F5 | Any page could force-log-out a member with no confirmation |
| F6 | Invite endpoint leaked screened-email status + no rate limit |
| F7 | `tiaa_member` cookie documented as presentation-only |
| F8 | Dead `admin_post` hook pointing at a missing method |

---

## F1 — CSV export: table allowlist + capability check

The `table` parameter used to go straight into a SQL query. Now it's checked
against a fixed list, and the handler requires `manage_options` on top of the
nonce.

**Setup:** admin session for steps 1–2; a subscriber or incognito window for
step 3.

**As admin — the legitimate path still works**
1. Open [Screened Emails](https://wp-test.test/wp-admin/admin.php?page=screened_emails)
   and click **Download CSV**.
2. Open [Welcome](https://wp-test.test/wp-admin/admin.php?page=welcome_message),
   scroll to the cron status panel, and click its **Download CSV**.

> **Expected:** both downloads succeed — one file of screened emails, one of
> the welcome-message log. These are the two tables the allowlist permits.

**Simulate an attacker — a disallowed table, no admin session**

3. In a private window, log in as a non-admin account (or stay logged out
   entirely), and visit:

   ```
   https://wp-test.test/wp-admin/admin-post.php?action=tiaa_secure_file&type=csv&table=users
   ```

> **Expected:** a 403 JSON error (`Unauthorized request` or `Insufficient
> permissions`), never a CSV — regardless of the table name, because there's
> no valid session/nonce for this action to begin with. This confirms the
> outer gate; the allowlist itself was verified at the code level against an
> authenticated admin session (a real admin trying `table=users` gets a clean
> 400, `Invalid table name`).

- [x] Both legitimate downloads worked, and the crafted URL was rejected

---

## F2 — `get_discourse_post` now requires `manage_options`

This route fetches Discourse posts using the site's own privileged API key.
It used to be wide open; it's now gated the same way the admin pages that use
it already were.

**Setup:** admin session for step 1; a private/incognito window (logged out)
for step 2.

**As admin — the "Get Message" / "Ping test" links still work**
1. Open [Signup](https://wp-test.test/wp-admin/admin.php?page=signup). If a
   post ID isn't set yet, enter any number and save — you just need the link
   to appear. Click **Ping test** and then **Get Message**.

> **Expected:** either a real response, or an error about reaching Discourse
> itself (SSL/connection) if local Discourse isn't fully wired up — **not** a
> permission error. A 401/403 here would mean the gate is blocking admins
> too, which would be wrong.

> **Caught by this exact check (tiaa-wpplugin commit `ad97dac`):** the first
> pass at F2 gated these routes with `manage_options` but left the "Ping
> test" / "Get Message" links as plain `<a href>` tags with no nonce. WP core
> treats any REST request with no `_wpnonce`/`X-WP-Nonce` as anonymous
> (`rest_cookie_check_errors()`), even with valid login cookies — so a real
> admin's own click resolved to user 0 and got 401. All six affected links
> now carry a `wp_rest` nonce. If you still see a 401 here, that regression
> is back.

**Simulate an attacker — anonymous, no session**

2. In a private window (make sure you're logged out), visit:

   ```
   https://wp-test.test/wp-json/tiaa_wpplugin/v1/get_discourse_post?post_id=1&option_group=tiaa_invite
   ```

> **Expected:**
> ```json
> {"code":"rest_forbidden","message":"Sorry, you are not allowed to do that.","data":{"status":401}}
> ```
> No post content is ever returned to an anonymous caller.

- [x] Admin links still work; anonymous request gets `rest_forbidden`

---

## F3 — CSRF protection on screened-email forms

Add, delete, and CSV import on the Screened Emails page now require a valid
nonce, like the export button already did.

**Setup:** admin session throughout. You'll create one throwaway test row to
use as the CSRF target.

**As admin — the real form still works**
1. Open [Screened Emails](https://wp-test.test/wp-admin/admin.php?page=screened_emails).
2. Add a test entry — email `csrf-poc@example.com`, any note — and confirm it
   appears in the table.
3. Note its **ID** from the first column. You'll use it below, then delete it
   normally when you're done.

**Simulate an attacker — delete without visiting the form**

4. Save this as a local file, e.g. `csrf-poc.html`, replacing `ID_HERE` with
   the row ID from above:

   ```html
   <!DOCTYPE html>
   <html><body>
   <h3>CSRF PoC — delete screened email, no nonce</h3>
   <form id="f" method="POST"
         action="https://wp-test.test/wp-admin/admin.php?page=screened_emails">
     <input type="hidden" name="delete_email_id" value="ID_HERE">
   </form>
   <script>document.getElementById('f').submit();</script>
   </body></html>
   ```

5. While still logged in as admin in the same browser, open that file
   (double-click it, or drag it into a tab).

> **Expected:** WordPress's `"The link you followed has expired"`
> nonce-failure page — not a silent delete. Go back to the Screened Emails
> page and confirm your test row is still there.

6. Clean up: delete `csrf-poc@example.com` the normal way, using the real
   Delete button (this exercises the legitimate delete path too — it should
   succeed with no confirmation-page detour).

- [x] Add/delete work normally; the forged delete was rejected, not silently applied

---

## F4 — the arbitrary-file-write export path is gone

A dead branch used to let a POST choose a server-side path to write a CSV to.
It never even streamed a download — it's been deleted. The real Download CSV
button is untouched.

**Setup:** admin session.

1. Open [Screened Emails](https://wp-test.test/wp-admin/admin.php?page=screened_emails)
   and look at the page: there is exactly one export control, the
   **Download CSV** button. No filename field, no separate "Export" button.
2. Click **Download CSV** once more to confirm it still downloads correctly
   (same check as F1 — this is the surviving code path).

> **Expected:** one export control, and it works. There's nothing left on the
> page that accepts a filename or writes to the server.

- [x] Only the Download CSV button remains, and it still works

---

## F5 — logout confirmation only auto-skips from Discourse

WordPress's own "are you sure?" logout screen used to be bypassed for *any*
nonce-less logout link. It now only bypasses when the click actually came
from the configured Discourse host.

**Setup:** stay logged in to WP throughout — you're testing whether you get
logged out, not who's logged in.

**Simulate an attacker page — should NOT auto-logout**

1. Save this as a local file and open it (double-click, or drag into a tab)
   while logged in to wp-test.test:

   ```html
   <!DOCTYPE html>
   <html><body>
   <h3>Attacker page — force-logout attempt</h3>
   <a href="https://wp-test.test/wp-login.php?action=logout">
     Innocent-looking link
   </a>
   </body></html>
   ```

2. Click the link.

> **Expected:** WordPress's normal `"Are you sure you want to log out?"`
> confirmation page. You are **not** immediately logged out. (If you click
> through the confirmation, that's fine and expected — it's a real logout at
> that point, just no longer a silent one.)

**The legitimate Discourse-originated case still auto-skips**

3. Log back in, then open [discourse-dev.test](https://discourse-dev.test) in
   a tab.
4. Open the browser console on that Discourse tab (F12) and run:

   ```js
   location.href = 'https://wp-test.test/wp-login.php?action=logout';
   ```

> **Expected:** you're logged out immediately, no confirmation page —
> because this navigation's Referer is the Discourse host, matching the
> configured Discourse URL.

- [x] Attacker-page link required confirmation; Discourse-referred link still skipped it

---

## F6 — uniform invite response + per-IP rate limit

A screened email used to get back a distinct `dropped_email` code — enough
for an outsider to test whether any address was on the list. The invite
endpoint also had no send limit.

**Setup:** no login needed — this route is public by design. Run this from
the browser console **on a wp-test.test tab** (so the request is
same-origin).

1. Open any page on wp-test.test, open the console (F12), and paste this:

   ```js
   (async () => {
     for (let i = 1; i <= 7; i++) {
       const res = await fetch('/wp-json/tiaa_wpplugin/v1/invite', {
         method: 'POST',
         headers: { 'Content-Type': 'application/json' },
         body: JSON.stringify({ name: 'F6 Test ' + i, email: 'foo@flee.flum' })
       });
       console.log(i, res.status, await res.json());
     }
   })();
   ```

> **Expected — requests 1 through 5:**
> `200 {success: true, status: 200, response: "OK"}`. No `code` field, and
> specifically no `dropped_email` — even though `foo@flee.flum` is a real
> screened address in this test data, the response is indistinguishable from
> a genuine successful invite.
>
> **Expected — requests 6 and 7:**
> `429 {success: false, code: "rate_limited", ...}`. Wait 60 seconds and the
> count resets for that IP.

- [x] First 5 responses were uniform (no `dropped_email` code); 6th and 7th were rate-limited

---

## F7 — `tiaa_member` cookie boundary (documentation only)

No code changed. This just records, in `tiaa-wpplugin/CLAUDE.md`, that the
cookie is presentation-only and must never be used to gate real content.

1. Open `wp-content/plugins/tiaa-wpplugin/CLAUDE.md` and confirm the new
   "Security boundary (SECURITY-REVIEW.md F7)" paragraph reads correctly
   under the `TiaaMemberCookie` section.

> Nothing to click — there's no behavior change to reproduce here, the
> finding was informational.

> **Follow-up closed (tiaa-elementor commit `6c8a4f3`):** the matching
> warning has now been added to `tiaa-elementor/CLAUDE.md`, next to the
> `.tiaa-member-only`/`.tiaa-anon-only` description.

- [x] Read the CLAUDE.md note in both tiaa-wpplugin and tiaa-elementor

---

## F8 — dead `admin_post` hook removed

This hook pointed at a handler method that didn't exist. Hitting it directly
used to fatal; now it's just inert. The real log download is a separate,
working path.

**Setup:** admin session.

1. Visit this URL directly:

   ```
   https://wp-test.test/wp-admin/admin-post.php?action=download_log_file
   ```

> **Expected:** a blank page (or a bare `0`) — not a PHP fatal-error page.

2. Now open [Logging](https://wp-test.test/wp-admin/admin.php?page=logging)
   and click **Download log**.

> **Expected:** the real log file downloads normally — this is a different
> code path (`action=tiaa_secure_file`) and wasn't touched.

> **Caught by this exact check (tiaa-wpplugin commit `34055a2`):** the
> downloaded file had correct headers and filename but an empty body — a
> pre-existing bug, not something F8 introduced. `output_file()` returned
> normally after `readfile()`, letting `tiaa_serve_file()`'s
> `finally { ob_end_clean(); }` discard the buffered file content before it
> reached the browser. The CSV path was never affected because it calls
> `exit()` internally, before that `finally` runs. `output_file()` now does
> the same. If a log download is ever empty again, check for that `exit()`
> first.

- [x] Dead hook is inert (no fatal); real Download log still works
