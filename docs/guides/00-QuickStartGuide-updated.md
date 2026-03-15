# TIAA Forum v3 — Quick Start Guide

**Updated:** March 2026  
**For:** Volunteer maintainers and new contributors  
**Purpose:** Where the project stands, what exists, what still needs to be done

---

## Current Build Status

Before starting any work, read this section. A lot has been built since the original Quick Start Guide was written.

### ✅ Complete

| Item | Guide |
|------|-------|
| Global Colors (10 colours) | `01-elementor-global-colors-setup.md` |
| Global Fonts (7 styles, Inter) | `02-elementor-global-fonts-setup.md` |
| Header — with conditional navigation | `03-elementor-header-template.md` |
| Footer — three-column, copyright, AAWS disclaimer | *(no dedicated guide — built during header phase)* |
| Homepage — all 5 sections | `04-homepage-build.md` ← **start here for homepage** |
| Conditional navigation (Join ↔ Go To Forum + Contribute + Logout) | `03-elementor-header-template.md` |
| Loop Item templates (Hot Topics card, Category card) | `04-homepage-build.md` |
| Stub pages created (Join, Contribute, About Us, Contact, Resources, The Forum) | *(no guide yet — need content)* |

### ⚠️ Partially Done — Needs Content

- **Hot Topics posts** — WordPress structure exists, full content migration from old site not yet done
- **Discourse Categories posts** — Structure exists, some posts are missing featured images
- **All stub pages** — Pages exist but have placeholder content only

### 🔲 Not Yet Built

- Single Hot Topic post template (Theme Builder → Single)
- Hot Topics archive / index page template (Theme Builder → Archive)
- Categories archive / index page template
- `/join` page with live payment form (WP SimplePay + Stripe)
- `/contribute` page with real content (member-only)
- `/about-us` page with real content
- `/contact` page with working contact form
- `/resources` page (full dropdown post-MVP)
- `/the-forum` explainer page

---

## Guide Index

| # | File | Status | Covers |
|---|------|--------|--------|
| 00 | `00-QuickStartGuide.md` | ✅ This file | Project status and navigation |
| 01 | `01-elementor-global-colors-setup.md` | ✅ Done | 10 Global Colors in Elementor Site Settings |
| 02 | `02-elementor-global-fonts-setup.md` | ✅ Done | 7 font styles using Inter |
| 03 | `03-elementor-header-template.md` | ✅ Done | Header + footer + conditional nav |
| 04 | `04-homepage-build.md` | ✅ Done | All 5 homepage sections — build record + maintenance |
| 05 | *(not yet written)* | 🔲 Needed | Content pages: About, Contact, Forum Explainer, Contribute, Resources |

> **On the guide numbering gap:** The original sequence had `03` (header) jump to `06` (homepage). The intervening numbers were never written. The homepage guide is now `04`. A future `05` should cover content pages.

---

## Reference Documents

These live in `docs/reference/` and `docs/project-reference/`. Read them before making architectural decisions.

| Document | What It Covers | Read When |
|----------|----------------|-----------|
| `tiaa-v3-design-governance-v2.md` | How design changes are made; what lives where; all pinned decisions | Before touching any template or plugin code |
| `tiaa-v3-design-build-divergences.md` | Why the Elementor build differs from the Replit reference | If something looks different from the Replit design — it's probably intentional |
| `01a-conditional-navigation.md` | Auth flow, user states, exact nav spec | Before touching the header or any conditional visibility |
| `01-architecture.md` | System layers, plugins, user flow, deployment | Onboarding; architectural questions |
| `02-environments-and-deployment.md` | Local → Staging → Production flow | Before deploying |
| `05-component-library.md` | Component catalog and specs | Planning new pages or sections |
| `IMPLEMENTATION-ROADMAP.md` | Full 10-phase build sequence with time estimates | Planning what to work on next |

---

## Key Rules for Anyone Editing the Site

These are the most important things to know before touching anything. Violating them is how layouts break.

### 1 — Two-layer container pattern (every section)
```
Outer container → Full Width   (background colour)
  └── Inner container → Boxed  (1200px max-width content)
```
Never set an inner container to Full Width.

### 2 — Use Global Colors and Fonts
Always select from the Global palette in Elementor's colour picker. Never hardcode a hex value in a widget. This ensures the whole site stays consistent if the palette ever changes.

### 3 — Do not edit Loop Item templates inline
The card templates (`Home Page Hot Topics card`, `Category Card - Homepage`) are saved Loop Item templates. Edit them in Elementor → Theme Builder → Loop Items. Do not recreate them ad-hoc on the page.

### 4 — Do not touch conditional display conditions without understanding them
The Header has four conditional buttons (Join, Go To Forum, Contribute, Logout). Each has a Display Condition set in the Advanced tab. Editing button text is fine. Restructuring the container those buttons live in will break the conditions. See `01a-conditional-navigation.md` before touching the header.

### 5 — Custom code lives in plugins, not Elementor
All JavaScript and PHP belongs in `tiaa-wpplugin` or the Elementor Forms plugin. Do not add code via Elementor → Site Settings → Custom Code. See `tiaa-v3-design-governance-v2.md`.

---

## For Content Contributors (No Elementor Needed)

### Adding or editing Hot Topics posts
1. WordPress Admin → Posts → Add New
2. Set **Category**: Hot Topics
3. Add a **Featured Image** (minimum 600×400px)
4. Fill in the **Excerpt** field (2 sentences max — this is what shows on cards)
5. Set **Order** in Page Attributes panel (lower number = higher priority on homepage)
6. Add the **Forum Thread URL** in the ACF custom fields panel
7. Publish

### Adding or editing Discourse Categories posts
1. WordPress Admin → Posts → Add New
2. Set **Category**: discourse-categories
3. Add a **Featured Image**
4. Fill in the **Excerpt** field
5. Set **Order** in Page Attributes
6. Publish

### Changing which cards appear on the homepage
1. WordPress Admin → Posts
2. Hover over a post → Quick Edit
3. Change the **Order** field — lower number = appears higher priority
4. Update — homepage refreshes immediately, no Elementor needed

### Updating navigation menu items
1. WordPress Admin → Appearance → Menus
2. Edit the **Main Navigation** menu
3. Add, remove, or reorder items
4. Save Menu

> Do NOT add the conditional buttons (Go To Forum, Join, Contribute, Logout) to this menu. They are managed in the Elementor Header template with Display Conditions and should not be duplicated in the menu.

### Updating Forum Statistics (until TIAA Settings admin page is built)
1. WordPress Admin → Pages → Homepage → Edit with Elementor
2. Scroll to the Forum Statistics card (Section 4, right column)
3. Click the number you want to change
4. Edit it directly, click Update

---

## What Needs to Be Built Next

Priority order — the site cannot launch without items marked BLOCKING.

### Priority 1 — Payment Flow ⛔ BLOCKING
Nothing works without this. New members cannot join.

1. Configure WP SimplePay — connect to Stripe, set up membership pricing, test mode
2. Build `/join` page — payment form embedded, benefits section, styled
3. Test `tiaa-wpplugin` integration — verify it creates Discourse accounts after payment
4. End-to-end test — visitor → payment → Discourse account → SSO login → correct header buttons

### Priority 2 — Content Pages
Needed before the site is handed off or goes public.

| Page | Notes |
|------|-------|
| `/the-forum` | Explainer: what the forum is, why join, how it works. CTA to /join |
| `/about-us` | Organisation info, mission, background |
| `/contact` | Contact form (Elementor Forms or Contact Form 7) |
| `/contribute` | Member-only — set Elementor Display Condition: User Logged In |
| `/resources` | Stub for now; full dropdown post-MVP |

### Priority 3 — Hot Topics & Categories Templates
Needed for full content team operation.

| Template | Where Built | Notes |
|----------|------------|-------|
| Single Hot Topic | Theme Builder → Single | Individual topic pages with Forum Thread URL link |
| Hot Topics archive | Theme Builder → Archive | The `/hot-topics` index page |
| Categories archive | Theme Builder → Archive | The `/categories` index page |

### Priority 4 — Pre-Launch Polish
These do not block launch but should be resolved before going public.

- Placeholder links updated to real page slugs (see table in `04-homepage-build.md`)
- Section padding consistency check (all sections at 60–80px top/bottom)
- Mobile responsive check on all pages and templates
- Missing featured images on some category card posts
- Header full redesign (Pinned Decision #4 in governance doc)
- Caching plugin configured — ensure `/join` is excluded from any page cache
- Export all Elementor templates (JSON) and commit to `exports/elementor/` in repo

---

## Progress Checklist

### Foundation
- [x] Global Colors
- [x] Global Fonts

### Templates
- [x] Header (conditional navigation working)
- [x] Footer
- [ ] 404 page

### Homepage
- [x] Hero
- [x] Two Ways section (with conditional Card 2 button)
- [x] Hot Topics Loop Grid
- [x] What You Can Do + Forum Statistics card
- [x] Forum Categories Loop Grid
- [ ] Section padding polish
- [ ] Placeholder links updated to real slugs

### Payment / Membership Flow ⛔ BLOCKING
- [ ] WP SimplePay configured
- [ ] Stripe connected (test mode first)
- [ ] /join page built with payment form
- [ ] tiaa-wpplugin Discourse account creation tested
- [ ] End-to-end signup flow tested

### Content Pages
- [ ] /the-forum explainer
- [ ] /about-us
- [ ] /contact (with working form)
- [ ] /contribute (Elementor Display Condition: Logged In)
- [ ] /resources (stub)

### Hot Topics System
- [x] Posts structure (category, featured images, menu_order, ACF)
- [x] Homepage Loop Item card template
- [ ] Single post template (Theme Builder)
- [ ] Archive / index page template (Theme Builder)
- [ ] Content migrated from old site

### Categories System
- [x] Posts structure (category, featured images, menu_order)
- [x] Homepage Loop Item card template
- [ ] Archive / index page template (Theme Builder)
- [ ] All posts have featured images

### Pre-Launch
- [ ] All placeholder `#` links updated
- [ ] Mobile tested on actual devices
- [ ] Cross-browser tested
- [ ] Caching configured (/join excluded)
- [ ] Elementor templates exported to repo
- [ ] Staging review complete
- [ ] Production deployed

---

## Getting Help

**Elementor layout issues:** `04-homepage-build.md` → Troubleshooting section  
**Conditional navigation broken:** `01a-conditional-navigation.md`  
**Not sure if a change is safe:** `tiaa-v3-design-governance-v2.md`  
**Something looks different from the Replit design:** `tiaa-v3-design-build-divergences.md`  
**Plugin / payment / Discourse questions:** `01-architecture.md`  
**Deployment questions:** `02-environments-and-deployment.md`

---

*Commit this file to: `docs/guides/00-QuickStartGuide.md` in tiaa-wpsite-v3*  
*Supersedes the previous version of this file (dated "based on conditional navigation requirements")*
