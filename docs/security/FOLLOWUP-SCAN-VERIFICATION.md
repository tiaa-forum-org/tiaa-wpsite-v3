# N1–N4 Manual Verification Runbook

Browser verification companion to the follow-up Fable security scan
(context brief: `SECURITY-SCAN-BRIEF.md`; findings N1–N4). Same purpose as
the archived F1–F8 runbook: independent confirmation in a real browser
against wp-test, since scripted tests have twice missed real regressions
that manual testing caught (F2's REST nonce gap, F8's empty-download bug).

**Site:** `https://wp-test.test` · **Admin account:** any `manage_options`
user.

---

## N1 — CSRF on Welcome-message cron controls

Fixed in `tiaa-wpplugin` commit `eed460f`. The Start/Stop/Fire-Once/
Get-Status cron forms on the Welcome admin page now require a nonce, same
as the Screened-Emails forms (F3).

**Setup:** admin session throughout.

**As admin — the real buttons still work**
1. Open [Welcome](https://wp-test.test/wp-admin/admin.php?page=welcome_message)
   and scroll to the cron status panel. Note the current status text (e.g.
   "scheduled at: ...").
2. Click **Stop Cron**. Confirm the status changes to "unscheduled".
3. Click **Start Cron** again to restore a schedule (exact re-scheduled time
   doesn't matter, just that it's scheduled again).

> **Expected:** both buttons work normally with no extra prompt — nonces are
> submitted invisibly as hidden fields.

**Simulate an attacker — stop the cron without visiting the form**

4. Save this as a local file and open it (double-click, or drag into a tab)
   while logged in as admin:

   ```html
   <!DOCTYPE html>
   <html><body>
   <h3>CSRF PoC — stop welcome cron, no nonce</h3>
   <form id="f" method="POST"
         action="https://wp-test.test/wp-admin/admin.php?page=welcome_message">
     <input type="hidden" name="cron_stop" value="1">
   </form>
   <script>document.getElementById('f').submit();</script>
   </body></html>
   ```

> **Expected:** WordPress's `"The link you followed has expired"`
> nonce-failure page — not a silent stop. Go back to the Welcome page and
> confirm the cron status is unchanged from before you opened the file.

- [x] Buttons work normally; the forged `cron_stop` was rejected, not silently applied

---

## N2 — Screened-email enumeration (residual F6 gap)

Fixed (shape half only) in `tiaa-wpplugin` commit `e441c57`. The screened-email
invite response now includes a `body_response` key, matching a genuine
success's key set. **The timing side-channel is a known, accepted residual —
not fixed.** The screened response still returns near-instantly since it
skips the Discourse API call; a real invite makes a network round trip. This
was a deliberate decision, not an oversight — don't re-flag it without a new
decision to close it.

**Setup:** no login needed. Run from the browser console **on a wp-test.test
tab** (same-origin).

1. Open any page on wp-test.test, open the console (F12), and paste this:

   ```js
   (async () => {
     const screened = await fetch('/wp-json/tiaa_wpplugin/v1/invite', {
       method: 'POST',
       headers: { 'Content-Type': 'application/json' },
       body: JSON.stringify({ name: 'N2 Test', email: 'foo@flee.flum' })
     }).then(r => r.json());
     console.log('screened response keys:', Object.keys(screened));
     console.log('screened response:', screened);
   })();
   ```

> **Expected:** keys are `success, status, response, body_response` — the
> same four keys a genuine successful invite returns. No structural
> difference visible from `Object.keys()` alone.

- [ ] Screened-email response includes `body_response`; key set matches a genuine success

## N3 — Invite rate limiter vs. reverse proxy

**Resolved as documentation, no code change.** Confirmed with the maintainer:
production has no reverse proxy in front of PHP, so `REMOTE_ADDR` is the real
client IP and the existing per-IP rate limiter is correct as written. Recorded
directly on `invite_rate_limit_exceeded()`'s docblock in `tiaa-wpplugin`
(commit `cb020b5`) so this assumption gets re-checked if the deployment
topology ever changes — nothing in the code itself would catch that silently.

No browser test applies here — there's no behavior change to verify.

- [x] Decision recorded (no proxy in prod; REMOTE_ADDR usage confirmed correct)

## N4 — `/tiaa-logout` bypasses F5's logout-CSRF fix

**Resolved as documentation, no code change.** Reviewed and accepted as a
deliberate inconsistency: logout-CSRF is low-impact (a forced logout is a
nuisance, not a compromise), and `/tiaa-logout`'s entire purpose is staying
reachable with zero friction during a Wordfence lockout — adding a Referer
gate or nonce here would undercut that. Cross-referenced in both
`TiaaLogoutRoute`'s and `skip_logout_confirmation()`'s docblocks (commit
`cb020b5`) so it reads as a reviewed decision, not an oversight, from either
side.

No browser test applies here — the behavior (force-logout via `/tiaa-logout`
with no confirmation, from any referring page) is unchanged and intentional.
If you want to see it, the same PoC pattern from F5 applies:
`<img src="https://wp-test.test/tiaa-logout">` on any page still logs you out
immediately, unlike the equivalent `wp-login.php?action=logout` link.

- [x] Decision recorded (accepted as intentional; documented in both classes' docblocks)
