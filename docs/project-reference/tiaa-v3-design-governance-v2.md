# TIAA Forum Site (tiaa-wpsite-v3) — Design Governance (MVP)

**Version:** 2.0  
**Date:** March 2026  
**Supersedes:** `ProjectDesignGovernancePre-MVP.md`

This document defines how design changes are made and approved for the TIAA Forum v3 site during MVP and beyond. It reflects the actual state of the build as of March 2026.

Where decisions have been deferred for future implementation, they are marked with a 📌 Pinned Decision reference. A complete list of pinned decisions appears in the Appendix at the end of this document.

---

## 1. What Controls the Site's Look and Feel

### Primary (Preferred — Elementor-Native)

- Elementor → Site Settings: Global Colors, Global Fonts, Buttons, Layout
- Elementor Theme Builder: Header, Footer, Single Post templates, Archive templates
- Saved Elementor Loop Item templates used as card components
- Elementor Pro display conditions for conditional visibility (logged in/out states)

### Custom Code (Plugin-Based)

Custom code lives in WordPress plugins, **not** in Elementor Custom CSS/JS. This is a deliberate change from the original governance spec.

| Code Type | Lives In | Purpose |
|---|---|---|
| PHP/JS (site behaviour) | `tiaa-wpplugin` | Discourse integration, join flow, welcome messages, clickable cards |
| Elementor-specific JS/CSS | `Elementor Forms / TIAA Invite Form Action` plugin | Form actions, Elementor UI enhancements |
| Environment config | `wp-config.php` (PHP constants) | Discourse URL, contact email, per-environment values |

No custom CSS or JS should be added directly in Elementor → Site Settings → Custom CSS or Elementor → Custom Code without team discussion.

---

## 2. Source of Truth Policy

### A) Elementor Content and Templates

Elementor is the source of truth for:

- Page layouts and content
- Theme Builder templates (Header, Footer, Single, Archive)
- Loop Item card templates
- Global Colors and Global Fonts

### B) Custom Code

GitHub is the source of truth for all custom code across three repositories:

| Repository | Contains |
|---|---|
| `tiaa-wpsite-v3` | Documentation, Elementor template exports (JSON), Docker Compose config |
| `tiaa-wpplugin` | Discourse integration, join flow, welcome messages, clickable cards JS |
| `Elementor Forms / TIAA Invite Form Action` | Elementor form actions, Elementor-specific JS/CSS |

---

## 3. Do Not Edit Plugin Code in WordPress

Plugin code must not be edited directly in WordPress → Plugins → Editor or via FTP without corresponding GitHub commits.

The correct workflow for plugin changes:

1. Update the file in the appropriate GitHub repository
2. Commit with a clear, descriptive message
3. Deploy to the server (pull from GitHub or use Duplicator for full migrations)
4. Test on staging before promoting to production

**Exception:** Emergency hotfixes are allowed, but must be ported back to GitHub within 24 hours.

---

## 4. Elementor Template Components

The following Elementor templates have been created and should be used consistently. Do not recreate these ad-hoc — always edit the saved template.

| Template Name | Type | Used For |
|---|---|---|
| TIAA-forum Header | Theme Builder — Header | Site-wide header with conditional nav |
| TIAA-forum Footer | Theme Builder — Footer | Site-wide footer |
| Home Page Hot Topics card | Loop Item | Hot Topics cards on homepage grid |
| Category Card - Homepage | Loop Item | Category cards on homepage grid |

Template exports (JSON) must be committed to `docs/exports/elementor/` in `tiaa-wpsite-v3` before each major deployment.

---

## 5. Conditional Navigation

Navigation visibility is controlled by Elementor Pro display conditions on individual button widgets in the Header template. Do not modify these conditions without understanding the Discourse SSO authentication flow.

| Element | Logged Out | Logged In | Notes |
|---|---|---|---|
| JOIN / SIGN IN button | Visible | Hidden | Coral. Links to `/join` |
| GO TO FORUM button | Hidden | Visible | Teal. Links to Discourse |
| CONTRIBUTE button | Hidden | Visible | Color driven by funding level 📌 See Pinned #1 |
| logout link | Hidden | Visible | Text link. Logs out of WP + Discourse |

---

## 6. Navigation Structure

| Nav Item | Logged Out | Logged In | Notes |
|---|---|---|---|
| Home | ✅ | ✅ | |
| Hot Topics | ✅ | ✅ | Retained for SEO and UX |
| About Us | ✅ | ✅ | Renamed from "About" |
| Contact Us | ✅ | ✅ | |
| Resources | ✅ | ✅ | Stub page — dropdown post-MVP 📌 See Pinned #2 |
| The Forum | ❌ | ❌ | Moved to Two Ways section as "What is a Forum?" button |

---

## 7. Content Management

### Hot Topics Posts

- Post type: WordPress Posts (not CPT)
- Category: `Hot Topics`
- Sort order: `menu_order` field (lower number = higher priority)
- Custom fields: Forum Thread URL (ACF)
- To update display priority: Posts → Quick Edit → Order field

### Discourse Categories Posts

- Post type: WordPress Posts
- Category: `discourse-categories`
- Sort order: `menu_order` field
- Each post represents one Discourse forum category
- Featured image required for card display

### Forum Statistics (Homepage)

Currently hardcoded in Elementor widgets. To update: open the homepage in Elementor, locate the Forum Statistics section, and update the number widgets directly.

📌 **See Pinned Decision #3** — a TIAA Settings admin page will replace this workflow.

---

## 8. Approval and Change Control (MVP)

Changes that affect global design require team notification:

- Global Colors / Fonts / Buttons
- Header/Footer templates
- Any plugin code changes
- Navigation structure changes

Suggested approval method:

1. Post a note in the Admin Team channel with a screenshot
2. If no objections within the agreed window, proceed
3. Document the change in the change log (Section 10)

---

## 9. Backups and Rollback

- Elementor templates and pages support revisions — use the Revisions panel before making major changes
- Export Elementor templates/site kit before any major deployment
- Store exports in `docs/exports/elementor/` in the `tiaa-wpsite-v3` repo
- Full site backup via Duplicator before each deployment to production
- Database backups managed via Docker volume snapshots on DigitalOcean VPS

---

## 10. Change Log

| Date | Who | What Changed | Why |
|---|---|---|---|
| Mar 2026 | lewg | v2.0 — Full governance rewrite to reflect actual build state | Handoff to volunteer team |
| | | | |
| | | | |

---

## Appendix: Pinned Decisions

The following decisions have been deferred for post-deployment implementation. Each is referenced in the relevant section of this document.

---

### 📌 Pinned Decision #1 — Contribute Button Color by Funding Level

The Contribute button in the header should change color based on the forum's current funding level:

| Level | Color | Meaning |
|---|---|---|
| Blue | `#31bba6` (teal) | Surplus |
| Green | `#28a745` | Fully funded |
| Yellow | `#ffc107` | Getting low |
| Red | `#dc3545` | Critical |

Currently hardcoded as coral.

**Implementation:** TIAA Settings admin page (see Pinned #3) will include a Funding Level dropdown. A small PHP snippet in `tiaa-wpplugin` will output the appropriate CSS class on the Contribute button.

---

### 📌 Pinned Decision #2 — Resources Dropdown Navigation

The Resources nav item currently points to a stub page. Post-MVP it should expand to a dropdown containing:

- Glossary
- List of Related Organizations (OIAA, C4R, NAATW, NAAAW, GIA-AA, CPC, PI, AAWS, Grapevine, AAnVR, etc.)
- Colophon
- Additional reference pages as identified by the committee

**Implementation:** WordPress nav menu dropdown + individual stub pages for each resource.

---

### 📌 Pinned Decision #3 — TIAA Settings Admin Page

A simple WordPress admin settings page (built in `tiaa-wpplugin`) to allow volunteers to update site-wide values without opening Elementor.

Fields to include:

| Field | Type | Used For |
|---|---|---|
| Active Members | Number | Homepage stat |
| Discussions | Number | Homepage stat |
| Posts | Number | Homepage stat |
| Categories | Number | Homepage stat |
| Funding Level | Dropdown (Blue/Green/Yellow/Red) | Contribute button color |
| Contact Email | Text | Footer and contact page |

Environment-specific URLs (Discourse URL, etc.) remain in `wp-config.php` as PHP constants and are **not** part of this admin page.

---

### 📌 Pinned Decision #4 — Header Full Redesign

The current header is a functional MVP implementation with button text reduced 2pt as a temporary fix for spacing. A full header redesign is planned before public launch, including:

- Proper button sizing and spacing
- Possible hamburger/responsive menu for mobile
- Refined conditional navigation layout

**Important:** Preserve all existing Elementor Pro display conditions when rebuilding the header.

---

### 📌 Pinned Decision #5 — Category Card Popup/Modal

Category cards on the homepage currently link to individual category post pages. A future enhancement would open category content in a modal/popup instead of navigating to a new page, given the short content (1-word title + brief excerpt).

**Implementation:** Elementor Pro Popup builder is the likely implementation path. Revisit after MVP stabilises.

---

### 📌 Pinned Decision #6 — Welcome Back State (3rd Login State)

A potential 3rd user state: a visitor whose browser/profile has been touched by WP-Discourse (has logged in previously) but is not currently logged in. Could show a "Welcome Back" message or streamlined sign-in prompt.

**Implementation:** Requires custom PHP to detect the Discourse profile field state. Deferred post-MVP. Current implementation is binary (logged in / logged out).

---

### 📌 Pinned Decision #7 — Expand Claude Project Knowledge

After v3 is live and handed off, add all three GitHub repositories to the Claude project knowledge base: `tiaa-wpsite-v3`, `tiaa-wpplugin`, and `Elementor Forms / TIAA Invite Form Action`. Also:

- Create a decisions log document
- Update READMEs for each repo
- Move clickable cards snippet from `tiaa-wpplugin` to Elementor Forms plugin

