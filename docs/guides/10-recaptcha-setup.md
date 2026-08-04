# 10 — reCAPTCHA v3 Setup (Contact Form + WP SimplePay)

**Status:** ✅ Complete
**Last updated:** 2026-07-24
**Why this exists:** The Contact Us form was receiving 4+ spam emails/day. reCAPTCHA v3 was added to filter spam submissions without showing visitors a visible challenge (checkbox, image puzzle, etc.) — it scores each visitor invisibly in the background.

---

## Background: Where reCAPTCHA Lives Now

Google moved reCAPTCHA key management into the **Google Cloud Console**, under a product called **Fraud Defense**. This is normal — it's not a sign anything is broken, even though the first time you land there it looks like an unfamiliar, complex product (because Cloud Console hosts dozens of unrelated products in the same sidebar).

**Where to find it:**
1. Sign in to Google Cloud Console as an account with access to the `tiaa-forum-org` Cloud project (should be a `@tiaa-forum.org` Workspace account, not a personal Gmail)
2. Left sidebar → **Security** → **Detections and Controls** → **Fraud Defense**
3. Click the **Keys** tab

⚠️ **Do not click "Security Command Center" / "Overview"** — that's a different, unrelated product (cloud infrastructure security monitoring) and will throw an "you need an Organization" error. It's a common misclick since it sits right above Fraud Defense in the sidebar.

---

## Existing Keys (as of 2026-07-24)

The Fraud Defense → Keys table showed **three** reCAPTCHA keys already registered under this Cloud project:

| Name | Type | Status | Created | Notes |
|---|---|---|---|---|
| `tiaa-forum.org` | Score-based (v3) | ✅ Protected | 2026-07-08 | **This is the live key** — auto-created/linked when WP SimplePay's admin flow was first configured. Actively used. |
| `tiaa-forum.org-v2` | Checkbox (v2) | Unknown | 2019 | Leftover from the original 2019-era site build. Not in use. Safe to ignore — no need to delete. |
| `tiaa-forum.org-v3` | Score-based (v3) | Unknown | 2019 | Also a 2019 leftover, dormant. Safe to ignore. |

**Key takeaway for future maintainers:** there is only **one key pair you need** — the `tiaa-forum.org` key created 2026-07-08. Do not create a second v3 key for the Contact form. Since reCAPTCHA is registered per **domain**, not per form, the same Site Key + Secret Key pair covers every form on `tiaa-forum.org` — WP SimplePay and the Elementor Contact form both use the identical pair.

---

## Step 1 — Retrieve the Key Pair

1. Fraud Defense → Keys → click **`tiaa-forum.org`** (the top row, Protected status)
2. Copy the **Site Key** (public) and **Secret Key** (private)
3. Store both in 1Password if not already there

You can also pull these directly from **WP SimplePay's own settings**, since that's where they're already configured and confirmed working.

---

## Step 2 — Configure Elementor Pro (Contact Us Form)

1. WP Admin → **Elementor → Settings → Integrations**
2. Find the **reCAPTCHA v3** section
3. Paste in the same **Site Key** and **Secret Key** from Step 1
4. Set **Minimum Score** — start at **0.5** (Google's suggested default)
5. Save
6. Open the **Contact Us page** in Elementor editor → select the Form widget → confirm reCAPTCHA v3 is enabled **on that specific form** as well. Elementor requires both:
    - the global Site Key/Secret Key in Settings → Integrations, **and**
    - the per-form toggle on the form widget itself

Missing the second step is a common reason reCAPTCHA appears configured but doesn't actually run.

---

## Step 3 — Tune the Score Threshold

There is **no threshold setting in Google's console** — the threshold only exists inside whichever plugin consumes the score (Elementor, WP SimplePay, etc.). Google just returns a 0.0–1.0 score per submission; each plugin decides what to do with it.

- **0.5** is a reasonable starting point
- **Too much spam still getting through?** Raise it (0.6–0.7)
- **Legitimate visitors getting blocked?** Lower it (0.3–0.4)

**Don't guess blind** — after the form has been live for a few days to a week:
1. Go to Fraud Defense → click the `tiaa-forum.org` key → **Dashboard** tab
2. Review the score distribution across real traffic
3. Adjust the Minimum Score in Elementor based on where real visitors vs. bots actually land, rather than tuning off intuition alone

---

## Quick Troubleshooting Reference

| Symptom | Likely Cause |
|---|---|
| Landed on a Security Command Center error page | Clicked "Overview" instead of "Fraud Defense" in the sidebar — go back and click **Fraud Defense** specifically |
| Wrong Google account owns the project | Check the account avatar (top right) before doing anything; Cloud projects are tied to whichever account created them |
| Elementor form still lets spam through after setup | Check the per-form toggle on the Contact form widget, not just the global Elementor Settings |
| Considering creating a new v3 key | Don't — reuse the existing `tiaa-forum.org` key already live via WP SimplePay. One key per domain covers all forms. |

---

## Related Docs
- `07-join-page.md` — WP SimplePay + Stripe configuration (where the original `tiaa-forum.org` v3 key was first wired up)
- `08-content-pages.md` — Contact Us page build