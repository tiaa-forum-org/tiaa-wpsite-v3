# Security Scan Brief — F1–F8 Follow-Up Pass

Context to hand to Fable (or any independent reviewer) for one more full
security scan of the three TIAA WordPress plugins, building on the F1–F8
remediation work already done. Written 2026-08-18.

```
SCOPE: Full security scan of three tiaa-forum.org WordPress plugins:
- tiaa-wpplugin (github.com/tiaa-forum-org/tiaa-wpplugin), main @ v0.0.14
- tiaa-elementor (github.com/tiaa-forum-org/tiaa-elementor), main @ v0.0.13
- tiaa-quick-edit (github.com/tiaa-forum-org/tiaa-quick-edit), main @ v1.5.2

CONTEXT — prior security work (read these first):
- tiaa-wpsite-v3/docs/security/SECURITY-SCOPE.md — original review scope/methodology
- tiaa-wpsite-v3/docs/security/SECURITY-REVIEW.md — 8 findings (F1-F8) from that review,
  plus a "Cleared" section of what was traced and found clean at that time
- tiaa-wpsite-v3/docs/security/archive/SECURITY-REVIEW-VERIFICATION.md — closed-out
  manual browser verification runbook; the closure banner at the top summarizes
  what got fixed and which commits

All 8 F1-F8 findings are fixed and verified (both scripted tests and manual browser
walkthroughs). Don't re-report them as open. Commit ranges:
- tiaa-wpplugin: ed1195e..1cd9bea on main
- tiaa-elementor: 16c20c6..c98884a on main
- tiaa-quick-edit: cb1516d..04dcd50 on main

WHAT'S GENUINELY NEW AND UNREVIEWED — prioritize this:
The fix code written during F1-F8 remediation has not had independent security
review; the original SECURITY-REVIEW.md predates all of it. Specifically look at:

1. tiaa-wpplugin/admin/GeneralFileHandler.php — new table allowlist +
   manage_options check (tiaa_serve_file/output_csv_from_database), and a fix to
   output_file() where readfile() output was getting silently discarded by an
   outer ob_end_clean() (fixed by adding exit()). Check for any other latent
   output-buffering issues in this file, and whether the allowlist
   (tiaa_screened_emails, tiaa_welcome_log) is complete against current callers.

2. tiaa-wpplugin REST permission_callback + nonce pairing generally. This exact
   bug class (gating a route with manage_options while a caller hits it via a
   nonce-less request, which WP's rest_cookie_check_errors() then treats as fully
   anonymous) has now recurred three times in this codebase's history: the
   original v0.0.10 ping-route fix, and twice in this pass (get_discourse_post
   route gate + the "Ping test"/"Get Message" admin links needing a wp_rest
   nonce). Please systematically audit every register_rest_route() call against
   every caller (JS fetch, <a href> link, form) to confirm each pairing is
   correct — this is the single highest-value thing to check given the repeat
   history.

3. tiaa-wpplugin/lib/TiaaHooks.php:
   - skip_logout_confirmation() / referred_from_discourse() — now scoped to
     Referer-host-matches-Discourse-URL before auto-skipping the logout
     confirmation. Sanity check the Referer-based trust assumption.
   - invite_to_discourse() rate limiting — per-IP throttle (5 req/60s) keyed on
     $_SERVER['REMOTE_ADDR'] directly, not X-Forwarded-For. Flag whether this is
     adequate for how tiaa-forum.org is actually deployed (if there's a reverse
     proxy in front of prod, REMOTE_ADDR could be the proxy's IP for everyone,
     making the rate limit useless — I don't know the real prod network topology
     and didn't verify this).
   - the uniform invite response for screened emails (no more distinguishing
     "dropped_email" code) — check whether any other response field could still
     leak screened-status (e.g. timing, or downstream error variations).

4. tiaa-wpplugin/admin/ScreenedEmailsHandler.php + views/screened-emails-view.php —
   new nonce fields on add/delete/import forms, and a fixed field-name mismatch
   that made CSV import actually functional again (it silently no-op'd before).
   Confirm the newly-functional import path doesn't introduce anything the
   review didn't anticipate now that it's reachable.

5. tiaa-elementor/CLAUDE.md — only a documentation change (F7 follow-up), no code.

ENVIRONMENT NOTES:
- No automated test suite for any of these plugins; verification during this
  pass was wp-cli eval scripts against a local Docker WP instance, plus a manual
  browser runbook. Please give concrete reproduction steps for anything found.
- Volunteer-maintained project (not a dedicated dev team) — prefer flagging
  small, mechanical, low-risk fixes over large redesigns; note where something
  needs a maintainer product/design decision rather than prescribing one.
- Also feel free to revisit the original review's "Cleared" section
  (tiaa-quick-edit, front-end output paths in TiaaSiteSettings.php, Discourse.php
  response handling, the Elementor invite relay) in case anything has shifted
  since — it was clean as of the original review's commit state, not since.
```
