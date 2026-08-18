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

**Status: not yet fixed — awaiting a decision on the timing-side-channel half.**
Add a verification section here once N2 is addressed.

## N3 — Invite rate limiter vs. reverse proxy

**Status: awaiting a decision on production network topology.**
Add a verification section here once N3 is addressed.

## N4 — `/tiaa-logout` bypasses F5's logout-CSRF fix

**Status: awaiting a decision (accept as documented tradeoff, or add the
same Referer gate to `/tiaa-logout`).**
Add a verification section here once N4 is addressed.
