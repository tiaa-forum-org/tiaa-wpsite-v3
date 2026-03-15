# TIAA Forum v3 — Implementation Roadmap

**Updated:** March 2026  
**Supersedes:** Previous version of this file  

This document tracks the full build sequence. Phase statuses reflect the actual state of the site as of March 2026.

---

## Phase Status Summary

| Phase | Description | Status |
|-------|-------------|--------|
| 1 | Foundation — Global Design System | ✅ Complete |
| 2 | Core Templates — Header, Footer | ✅ Complete |
| 3 | Content Pages — About, Contact, Forum Explainer | 🔲 Not started |
| 4 | Membership Flow — Payment, Discourse integration | ⛔ Not started — CRITICAL PATH |
| 5 | Hot Topics — Templates and content | 🔲 Partially done |
| 6 | Homepage | ✅ Complete |
| 7 | Member-Only Features — Contribute page | 🔲 Not started |
| 8 | Testing and Refinement | 🔲 Not started |
| 9 | Documentation and Handoff | 🔶 Ongoing |
| 10 | Deployment to Production | 🔲 Not started |

---

## Phase 1 — Foundation ✅ COMPLETE

**Goal:** Establish the design system everything else builds on.

| Task | Status | Guide |
|------|--------|-------|
| Set up 10 Global Colors in Elementor Site Settings | ✅ | `01-elementor-global-colors-setup.md` |
| Set up 7 Global Font styles (Inter) | ✅ | `02-elementor-global-fonts-setup.md` |
| Test design system on a test page | ✅ | Covered in setup guides |

---

## Phase 2 — Core Templates ✅ COMPLETE

**Goal:** Site-wide templates that appear on every page.

| Task | Status | Guide |
|------|--------|-------|
| Create "Main Navigation" WordPress menu | ✅ | `03-elementor-header-template.md` |
| Upload logo to Media Library | ✅ | `03-elementor-header-template.md` |
| Build Header template with sticky nav | ✅ | `03-elementor-header-template.md` |
| Implement conditional navigation (Join / Go To Forum + Contribute + Logout) | ✅ | `03-elementor-header-template.md` + `01a-conditional-navigation.md` |
| Build Footer template (3-col, copyright, AAWS disclaimer) | ✅ | *(no dedicated guide — built during header phase)* |
| Build 404 template | 🔲 | *(not yet built — low priority)* |

**Notes on actual navigation vs original spec:**
- "The Forum" removed from nav menu — replaced by "What is a Forum?" button in Two Ways section
- "About" renamed to "About Us"
- "Resources" added as stub nav item (dropdown post-MVP)
- "Contribute" is a conditional header button, not a menu item

See `tiaa-v3-design-build-divergences.md` for full reasoning.

---

## Phase 3 — Content Pages 🔲 NOT STARTED

**Goal:** Essential static pages needed before launch.

**Estimated time:** 3–4 hours

| Task | Status | Notes |
|------|--------|-------|
| Build `/the-forum` — Forum Explainer page | 🔲 | Public. What the forum is, why join, how it works. CTA → /join |
| Build `/about-us` — About page | 🔲 | Public. Organisation info, mission, background |
| Build `/contact` — Contact page with form | 🔲 | Public. Elementor Forms or Contact Form 7 |
| Build `/resources` — Resources stub | 🔲 | Public stub. Full dropdown post-MVP (Pinned Decision #2) |
| Build `/contribute` — Contribution page | 🔲 | Member-only. Elementor Display Condition: User Logged In |

**Dependency:** No Theme Builder template required — Hello Elementor theme handles basic page shell. Build pages directly in Elementor.

**Blocker risk:** Low for static pages. `/contribute` requires understanding Elementor Display Conditions.

---

## Phase 4 — Membership Flow ⛔ NOT STARTED — CRITICAL PATH

**Goal:** Enable new users to sign up, pay, and access the forum.

**Estimated time:** 4–6 hours

**This is the highest-risk phase. Start it early and allow time for troubleshooting.**

| Task | Status | Notes |
|------|--------|-------|
| Configure WP SimplePay | 🔲 | Connect to Stripe. Start in test mode. |
| Set up membership pricing in Stripe | 🔲 | One-time or recurring — confirm with project lead |
| Build `/join` page | 🔲 | Payment form (SimplePay shortcode), benefits section above it, styled |
| Verify `tiaa-wpplugin` Discourse API integration | 🔲 | Creates Discourse account after successful payment |
| Test welcome message delivery | 🔲 | WP-Discourse sends welcome DM |
| End-to-end signup test | 🔲 | Visitor → payment → WP account → Discourse account → SSO login → correct header buttons |

**Blocker risks:**
- Stripe test mode setup requires Stripe account access
- tiaa-wpplugin Discourse API integration may require debugging
- WP-Discourse SSO must be verified on the new environment
- `/join` page must be excluded from any page caching plugin

**After this phase is working:** proceed to Phase 3 (content pages) and Phase 5 (Hot Topics templates) in parallel.

---

## Phase 5 — Hot Topics System 🔶 PARTIALLY DONE

**Goal:** Enable creation and display of Hot Topics.

| Task | Status | Notes |
|------|--------|-------|
| "Hot Topics" category created | ✅ | |
| ACF Forum Thread URL field | ✅ | Set on individual posts |
| `menu_order` as priority control | ✅ | Replaces Featured toggle from original spec |
| Homepage Loop Item card template | ✅ | `Home Page Hot Topics card` |
| Content migration from old site | 🔶 | Partial — some posts exist, full migration not done |
| Single Hot Topic post template | 🔲 | Theme Builder → Single Post |
| Hot Topics archive/index template | 🔲 | Theme Builder → Archive — for `/hot-topics` page |

**Notes on changes from original spec:**
- Featured toggle (ACF) deferred — `menu_order` is simpler for volunteers
- Whole-card clickable via JS overlay in `tiaa-wpplugin` (Elementor-native card link not available in this version)
- Index page card template will need to be built separately from the homepage card template (different display requirements)

**Blocker risk:** Medium. Anchor link preservation (`#hotline`, `#webstart`, `#voting`) must be tested carefully during content migration.

---

## Phase 6 — Homepage ✅ COMPLETE

**Goal:** Engaging homepage that communicates value and routes visitors to the right next step.

| Task | Status | Guide |
|------|--------|-------|
| Hero section | ✅ | `04-homepage-build.md` |
| Two Ways to Learn and Share (dual option cards with conditional button) | ✅ | `04-homepage-build.md` |
| Hot Topics Loop Grid (3 cards, menu_order sorted) | ✅ | `04-homepage-build.md` |
| What You Can Do + Forum Statistics card (60/40 split) | ✅ | `04-homepage-build.md` |
| Forum Categories Loop Grid (4×2 cards, menu_order sorted) | ✅ | `04-homepage-build.md` |
| Whole-card clickable via JS overlay | ✅ | `tiaa-wpplugin` |

**Polish items remaining:**
- Section padding consistency (target 60–80px top/bottom throughout)
- Placeholder links updated to real slugs once pages are built
- Mobile responsive check
- Missing featured images on some category card posts

---

## Phase 7 — Member-Only Features 🔲 NOT STARTED

**Goal:** Build features only logged-in members can access.

**Estimated time:** 1–2 hours (small phase — most conditional nav already done)

| Task | Status | Notes |
|------|--------|-------|
| Build `/contribute` page content | 🔲 | Page exists as stub. Add real content. |
| Set Elementor Display Condition on `/contribute` | 🔲 | Advanced tab → Display Conditions → User Logged In |
| Verify "Contribute" button in header shows/hides correctly | ✅ | Done in Phase 2 |
| Test full member experience end-to-end | 🔲 | Requires Phase 4 to be complete |

---

## Phase 8 — Testing and Refinement 🔲 NOT STARTED

**Goal:** Verify everything works before launch.

**Estimated time:** 3–5 hours

| Task | Notes |
|------|-------|
| Cross-browser testing | Chrome, Firefox, Safari, Edge |
| Mobile device testing | Real iOS and Android devices — not just Elementor preview |
| Full user flow: visitor → payment → login → forum | Requires Phase 4 complete |
| Anchor link verification on Hot Topics | #hotline, #webstart, #voting must resolve correctly |
| All links resolved (no `#` placeholders) | |
| Contact form delivery tested | |
| Caching plugin configured (exclude `/join`) | |
| Image optimisation | Compress all featured images |
| Elementor template export + commit to repo | Before any production deployment |

---

## Phase 9 — Documentation and Handoff 🔶 ONGOING

**Goal:** Prepare volunteer maintainers to run the site independently.

| Task | Status | Notes |
|------|--------|-------|
| Quick Start Guide | ✅ Updated | `00-QuickStartGuide.md` |
| Homepage build guide | ✅ Written | `04-homepage-build.md` |
| Design Governance v2 | ✅ Written | `tiaa-v3-design-governance-v2.md` |
| Design Divergences log | ✅ Written | `tiaa-v3-design-build-divergences.md` |
| Implementation Roadmap | ✅ Updated | This file |
| Guide 05 — Content Pages | 🔲 Needed | Write when content pages are built |
| Editor guide (`06-editor-guide.md`) | 🔲 Needs update | Update with current page structure |
| Operations runbook (`07-operations-runbook.md`) | 🔲 Needs update | Backup, update cycle, rollback |
| Elementor template exports (JSON) committed to repo | 🔲 | Do before production deployment |
| Handoff meeting with volunteer team | 🔲 | Walk-through of all editable content |

---

## Phase 10 — Deployment to Production 🔲 NOT STARTED

**Goal:** Launch the new site.

**Estimated time:** 2–4 hours

| Task | Notes |
|------|-------|
| Final staging review and approval | |
| Full Duplicator export from local/staging | |
| New Docker container on DigitalOcean VPS (new port in docker-compose.yml) | See `02-environments-and-deployment.md` |
| Duplicator import on VPS | |
| Verify Elementor Pro license on new install | May require new license activation |
| Configure WP-Discourse plugin for production Discourse URL | Use `wp-config.php` constants |
| Configure Stripe — switch from test mode to live | Only after full testing |
| DNS cutover (if replacing existing tiaa-forum.org) | Coordinate to minimise downtime |
| Verify SSL certificate | |
| Post-launch smoke test | All critical flows: homepage, join, login, forum SSO, logout |
| Backup immediately after launch | Full database + files |

**Blocker risks:**
- Elementor Pro license may not cover multiple installs
- Production Discourse URL differs from staging — all WP-Discourse config must be updated
- Page caching must exclude `/join` to prevent cached payment forms
- DNS changes cause temporary downtime — plan for off-peak

---

## Critical Path (Shortest Route to Launch)

```
Phase 1 ✅ → Phase 2 ✅ → Phase 6 ✅
                                    ↓
                            Phase 4 ⛔ ← DO THIS NOW
                                    ↓
                Phase 3 & Phase 5 (can run in parallel)
                                    ↓
                            Phase 7 (small)
                                    ↓
                            Phase 8 (testing)
                                    ↓
                    Phase 9 (documentation — ongoing)
                                    ↓
                            Phase 10 (launch)
```

**Recommendation for next session:** Start Phase 4 (payment flow). It has the highest uncertainty and the longest debugging tail. Everything else can proceed around it.

---

## MVP Launch Criteria

**Must have (blocking launch):**
- [ ] Header conditional navigation verified working on production URL
- [ ] `/join` page processes payment correctly
- [ ] New members get Discourse accounts automatically (tiaa-wpplugin)
- [ ] Members can SSO to Discourse from "Go To Forum" button
- [ ] Hot Topics display correctly on homepage
- [ ] All critical nav links resolve to real pages (not `#`)
- [ ] Mobile responsive on homepage, join page, and hot topics
- [ ] AAWS disclaimer in footer

**Should have (launch without if necessary):**
- [ ] `/contribute` page with real content
- [ ] Hot Topics archive and single post templates
- [ ] Contact form working
- [ ] 404 page styled

**Post-launch (nice to have):**
- Live stats from Discourse API (replacing hardcoded stats)
- Hot Topics search and filter
- Header full redesign (Pinned Decision #4)
- TIAA Settings admin page for stats and funding level (Pinned Decision #3)
- Resources dropdown (Pinned Decision #2)

---

*Commit this file to: `docs/project-reference/IMPLEMENTATION-ROADMAP.md` in tiaa-wpsite-v3*  
*Supersedes previous version of this file*
