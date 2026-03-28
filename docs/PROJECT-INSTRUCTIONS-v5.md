# TIAA Forum Rebuild — Project Instructions for Claude

*Version: 5.0 — Updated 2026-03-17*  
*Previous version: 4.0 (2026-03-14)*  
*Changes this version: tiaa-quick-edit plugin added; repo plugins/ structure updated; Custom Code Locations table updated; Guide Index unchanged.*

---

## Objective
Rebuild tiaa-forum.org using Elementor Pro based on a new design created in Replit. The goal is an MVP that volunteer maintainers with basic WordPress experience (but potentially limited Elementor Pro knowledge) can support long-term.

---

## Current Tech Stack
- **WordPress** with Hello Elementor theme
- **Elementor Pro** for all page building, templates, and conditional visibility
- **Yoast SEO** plugin — installed and active; provides breadcrumb widget used in Hot Topics single post template
- **Custom plugin**: tiaa-wpplugin (handles Discourse API integration, new member signups, welcome messages, and clickable card JS)
- **Custom plugin**: tiaa-elementor-forms-invite-action (Elementor Pro form action for the invite/join flow)
- **Custom plugin**: tiaa-quick-edit (Sort Order + Excerpt fields in WP Quick Edit panel — admin utility only)
- **WP-Discourse plugin**: Discourse SSO integration (Discourse is the SSO provider)
- **WP SimplePay plugin**: Stripe payment processing for memberships (not yet configured)
- **ACF (Advanced Custom Fields)**: Custom fields for Hot Topics posts
- **Hosting**:
  - Development: Local on macOS with Docker Compose
  - Test/Production: DigitalOcean VPS with Docker Compose stack
- **Development environment**: IntelliJ IDEA with Docker Compose plugin
- **Version control**: GitHub - https://github.com/tiaa-forum-org/tiaa-wpsite-v3.git

---

## Current Build State (as of 2026-03-17)

### ✅ COMPLETED

**Phase 1 — Foundation**
- Global Colors (10 colors defined in Elementor Site Settings)
- Global Fonts (7 font styles, Inter family)

**Phase 2 — Core Templates**
- Header template with conditional navigation (fully working)
- Footer template (fully working — 3-column layout matching Replit reference)

**Phase 4 (re-numbered) — Homepage**
- All 5 sections complete and working:
  1. Hero — light teal gradient background
  2. Two Ways to Learn and Share — 50/50 card split
  3. What You Can Do + Forum Statistics — 60/40 split with navy stats card
  4. Forum Categories — 4-column Loop Grid
  5. Selected Hot Topics — 3-column Loop Grid with "View all topics" button
- Clickable cards implemented via JS overlay snippet in tiaa-wpplugin

**Hot Topics System — COMPLETE**
- **11 Hot Topics posts** exist (migrated from original tiaa-forum.org site)
- Each topic is a separate WordPress Post (not a Custom Post Type)
- Theme Builder single post template ID: `3484`; display condition: Posts in category: Hot Topics
- Single post layout: title (left, 50%), featured image (right, 50%), body content, Yoast breadcrumb at bottom
- Discourse CTA button lives in each post's block editor content — not in the template — because the URL is unique per post
- Hot Topics archive page: `/hot-topics/` — 3-column card grid, all 11 posts visible
- Yoast breadcrumb widget used at bottom of each post ("← back to Hot Topics")
- Anchor link SEO issue **resolved** — Yoast breadcrumb provides the back-navigation
- `menu_order` field controls display priority (lower = higher)
- ACF Forum Thread URL field implemented on all posts
- **Known issues outstanding:** meta descriptions pending on all posts; JPG→WebP image conversions pending for 10 of 11 posts; Discourse thread URLs confirmed only for AA Hotlines
- See `HotTopicPostOrder.md` for the complete list of 11 posts with IDs, slugs, menu_order values, image files, and status

**Discourse Categories System — COMPLETE**
- 6 category posts exist as WordPress Posts in the `discourse-categories` category
- Displayed in homepage Section 4 as a 4-column Loop Grid
- Loop Item template: `Category Card - Homepage`
- Cards show: featured image, category name, excerpt
- Sorted by `menu_order`
- See `06-discourse-categories-system.md` for full guide

**tiaa-quick-edit Plugin — COMPLETE**
- Standalone admin utility plugin; lives at `plugins/tiaa-quick-edit/`
- Adds Sort Order (`menu_order`) and Excerpt (`post_excerpt`) fields to Quick Edit
- Fields visible only for posts in `hot-topics` and `discourse-categories` categories
- Adds sortable Sort Order column to Posts list table
- No external dependencies; safe to deactivate without data loss
- See `plugins/tiaa-quick-edit/README.md` for full usage and troubleshooting guide

### ⚠️ IN PROGRESS / STUB PAGES EXIST
The following pages exist in WordPress as stubs (title + placeholder content only):
- `/join` — needs WP SimplePay form + payment flow
- `/contribute` — needs content + member-only visibility condition
- `/about-us` — needs content
- `/contact-us` — needs content + contact form
- `/resources` — stub only, dropdown nav post-MVP

### 🔲 NOT YET STARTED
- WP SimplePay + Stripe configuration (HIGH PRIORITY — critical path)
- Discourse SSO integration testing end-to-end
- About Us page content
- Contact Us page with form
- Contribute page (member-only)
- The Forum explainer page (`/the-forum`)
- Resources stub content

---

## Critical Constraints

### MUST Preserve:
- ✅ Discourse SSO integration (forum signup/login flow)
- ✅ Hot Topics SEO — posts exist at individual URLs; Yoast breadcrumb handles back-navigation
- ✅ Contact forms and Join form functionality
- ✅ Payment processing via WP SimplePay + Stripe

### Team & Approach:
- ✅ Team skill level: Comfortable with WordPress admin, variable experience with Elementor Pro's Theme Builder
- ✅ Preference: Elementor-native solutions over custom code when possible
- ✅ When custom code is required: Use cut-and-paste working code assets from maintained GitHub repo
- ✅ Volunteer-friendly: Solutions must be maintainable by non-developers

---

## Authentication & User States

### How Authentication Works:
- **SSO Provider**: Discourse (NOT WordPress)
- **Flow**: WordPress defers to Discourse for authentication via WP-Discourse plugin
- **Member Status**: Stored in WP-Discourse profile fields
- **Only members can log in** — anonymous visitors cannot create WordPress accounts directly

### User States:
1. **Anonymous Visitor** (not logged in)
   - Has NOT joined the forum
   - Cannot log into WordPress
   - Sees "JOIN/SIGN IN" button (coral) in header → links to `/join`

2. **Logged-In Member** (authenticated via Discourse SSO)
   - Has paid/joined the forum
   - Sees "GO TO FORUM" (teal) + "CONTRIBUTE" (coral) + "logout" text link in header

---

## Navigation

### Static Nav (all users): Home | Hot Topics | Resources | About Us | Contact Us

### Conditional Header Buttons:

| Element | Logged Out | Logged In |
|---------|-----------|-----------|
| JOIN/SIGN IN (coral) | Visible | Hidden |
| GO TO FORUM (teal) | Hidden | Visible |
| CONTRIBUTE (coral) | Hidden | Visible |
| logout (text link) | Hidden | Visible |

**Implementation**: Elementor Pro display conditions on individual widgets in Header template.

---

## Content Model

### Hot Topics
- **Post type**: WordPress Posts (NOT Custom Post Type)
- **Category**: Hot Topics
- **Sort order**: `menu_order` field — lower number = higher priority on archive page
- **ACF custom field**: Forum Thread URL (link back to original Discourse thread)
- **Featured image**: Required — used in card display on archive and homepage
- **Single post template**: Title (left, 50%) + featured image (right, 50%) + body content + Yoast breadcrumb at bottom; template ID `3484`
- **Archive page**: 3-column Loop Grid at `/hot-topics/`
- **Featured toggle**: Deferred — `menu_order` is the current priority mechanism
- **11 posts exist** — see `HotTopicPostOrder.md` for complete list with IDs, slugs, menu_order values, image files, and status

> ⚠️ **Pending clarification:** Two source documents reference what appears to be the same post under slightly different titles ("A.A. Website Starter Toolkit" and "AA Website Starter Kit"), both pointing to WP Post ID 3536. Confirm before publishing that post.

### Discourse Categories
- **Post type**: WordPress Posts
- **Category**: `discourse-categories`
- **Sort order**: `menu_order` field
- **Each post**: represents one Discourse forum category
- **Featured image**: Required for card display
- **Card description**: Post excerpt field (1–2 sentences)
- **6 categories currently**: General Discussion, Websites & Apps, Meeting Technology, Data & Privacy, Archives & History, Tools & Resources
- See `06-discourse-categories-system.md` for full guide

### Pages
- `/` — Homepage (complete)
- `/hot-topics/` — Archive page (complete)
- `/join` — Public signup + payment (stub — HIGH PRIORITY)
- `/contribute` — Member-only (stub)
- `/about-us` — Public (stub)
- `/contact-us` — Public with form (stub)
- `/resources` — Public stub, dropdown post-MVP
- `/the-forum` — Forum explainer (not yet created)

---

## Design System

### Colors (Global in Elementor Site Settings):
- Navy: `#2b2e60` — Primary text, headings, navbars
- Teal: `#31bba6` — Primary actions, links, highlights
- Coral: `#f37758` — Secondary actions, Join button, accent
- Light Gray: `#f8f9fa` — Section backgrounds (alternating)
- Dark Gray: `#6c757d` — Body text, subtitles
- White: `#ffffff`

### Typography:
- Single font family: **Inter**
- 7 font styles defined in Elementor Global Fonts

### Layout:
- Max content width: **1200px** (Elementor Site Settings → Layout → Content Width)
- Section pattern: Full Width outer container (background) + Boxed inner container (respects 1200px)
- Section padding: 80px top/bottom standard

### Content Width Reference:

| Layout | Width |
|--------|-------|
| Single column content (post body, narrow pages) | 760px |
| Two-column content (homepage sections, archive grids) | 960px |
| Max content width (Elementor Site Settings) | 1200px |

---

## Elementor Templates (Existing — Do Not Recreate)

| Template Name | Type | Used For |
|---------------|------|----------|
| TIAA-forum Header | Theme Builder — Header | Site-wide header with conditional nav |
| TIAA-forum Footer | Theme Builder — Footer | Site-wide footer |
| Home Page Hot Topics card | Loop Item | Hot Topics cards on homepage (3-col) |
| Category Card - Homepage | Loop Item | Category cards on homepage (4-col) |
| Single Hot Topic | Theme Builder — Single | Individual Hot Topic post pages |
| Hot Topics Archive | Theme Builder — Archive | `/hot-topics/` archive page |

### Template IDs (test site — verify on production before go-live):

| Template | ID |
|----------|----|
| Site header | `1480` |
| Single Hot Topic | `3484` |
| Site footer | `1478` |

---

## Custom Code Locations

All custom code lives in **plugins**, not in Elementor Custom CSS/JS.

| Code Type | Plugin | Purpose |
|-----------|--------|---------|
| Discourse integration, join flow, welcome messages, clickable cards JS | `tiaa-wpplugin` | Core site behaviour and Discourse API |
| Elementor form action for invite/join flow | `tiaa-elementor-forms-invite-action` | Bridges Elementor Pro form submission to TIAA invite API |
| Sort Order + Excerpt fields in WP Quick Edit | `tiaa-quick-edit` | Admin utility — no front-end output |
| Environment config (URLs, email) | `wp-config.php` PHP constants | Per-environment values |

**Clickable cards JS** (in `tiaa-wpplugin`, `wp_footer` hook, `is_front_page()` conditional):
```php
document.querySelectorAll('.e-loop-item').forEach(function(card) {
    var link = card.querySelector('a');
    if (link) {
        var url = link.href;
        card.style.position = 'relative';
        card.style.cursor = 'pointer';
        var overlay = document.createElement('a');
        overlay.href = url;
        overlay.style.cssText = 'position:absolute;inset:0;z-index:1;';
        overlay.setAttribute('aria-hidden', 'true');
        card.appendChild(overlay);
    }
});
```

---

## Guide Index

| File | Status | Covers |
|------|--------|--------|
| `00-QUICK-START-GUIDE.md` | ✅ Exists | Master guide, phase overview |
| `01-elementor-global-colors-setup.md` | ✅ Exists | Global Colors setup |
| `02-elementor-global-fonts-setup.md` | ✅ Exists | Global Fonts setup |
| `03-elementor-header-template.md` | ✅ Exists | Header + conditional nav |
| `04-homepage-complete-build.md` | ✅ Exists | All 5 homepage sections |
| `05-hot-topics-system.md` | ✅ Updated 2026-03-14 | Hot Topics archive + single template + per-post workflow |
| `06-discourse-categories-system.md` | ✅ Created 2026-03-14 | Discourse Categories — homepage grid + per-post workflow |
| `07-footer-template.md` | ⚠️ Not yet written | Footer build — template exists but guide not yet written |
| `08-join-page.md` | 🔲 Not started | /join page + WP SimplePay |
| `09-content-pages.md` | 🔲 Not started | About, Contact, Forum Explainer |
| `10-contribute-page.md` | 🔲 Not started | Member-only contribute page |

> **Note on numbering:** Guide 06 was previously listed as the missing footer guide. It has been assigned to Discourse Categories (higher priority). Footer guide is now `07-footer-template.md`.

---

## Next Priorities (in order)

1. **`/join` page + WP SimplePay + Stripe** — CRITICAL PATH. No one can join without this.
2. **End-to-end signup testing** — payment → Discourse account → SSO login
3. **Complete Hot Topics post publishing** — meta descriptions, WebP images, Discourse URLs for remaining 10 posts
4. **Content pages** — About Us, Contact Us, Forum Explainer (needed before nav links work)
5. **Contribute page** — member-only, needs Elementor visibility condition
6. **Footer guide** (`07-footer-template.md`) — document what was built

---

## Critical Success Factors

✅ Conditional navigation works (different buttons for logged-in vs logged-out)  
✅ Payment flow is functional (new members can join and pay)  
✅ Discourse SSO works (members access forum seamlessly)  
✅ Hot Topics display correctly (archive + single post — COMPLETE)  
✅ Mobile responsive (all pages work on phones)  
✅ Volunteer maintainable (non-developers can update content)

---

## Pinned Decisions (Deferred Items)

1. **Contribute button color** changes by funding level (Blue/Green/Yellow/Red) — needs TIAA Settings admin page in tiaa-wpplugin
2. **Resources dropdown** — stub page now, full dropdown with Glossary + Related Orgs post-MVP
3. **TIAA Settings admin page** — for stats, funding level, contact email (replaces hardcoded values)
4. **Header full redesign** — current header is MVP; redesign before public launch (preserve all display conditions)
5. **Hot Topics search + filter** — deferred, requires JetSmartFilters or equivalent
6. **Category card modal/popup** — deferred, Elementor Pro Popup builder post-MVP
7. **Welcome Back state** (3rd login state) — deferred, requires custom PHP
8. **Discourse Categories live metadata** — topic counts and "last activity" timestamps require Discourse API integration; deferred post-MVP
9. **Hot Topics Featured toggle** (ACF field) — deferred in favour of `menu_order` approach; may be added post-MVP

---

## Reference Documents (in docs/project-reference/)

- `01-architecture.md` — System overview, authentication flow, user states
- `02-environments-and-deployment.md` — Dev/staging/production environments
- `03-content-model.md` — ✅ Updated 2026-03-14 — full content model, page widths, per-post workflows
- `04-elementor-system.md` — Elementor template system overview
- `05-component-library.md` — Component catalog with specs
- `HotTopicPostOrder.md` — ✅ Updated 2026-03-14 — authoritative list of 11 posts with IDs, slugs, menu_order, image files, status
- `conditional-navigation.md` — Complete auth + navigation specification
- `tiaa-design-governance-v2.md` — Design governance, change control, all pinned decisions
- `tiaa-design-divergences.md` — How Elementor build diverged from Replit reference and why
- `tiaa-v3-design-build-v1-vs-v2-.md` — Build divergences from original governance spec
- `TIAA-Forum-Hot-Topics-Reference.md` — Imported from hot-topics redesign repo. Authoritative source for Hot Topics template architecture, template IDs, per-post workflow, encoding fix table, and known issues. Cross-referenced into `05-hot-topics-system.md`.

### Known Stale Documents (do not rely on without cross-checking):
- `IMPLEMENTATION-ROADMAP.md` — Phase numbering and task status are outdated
- `00-QUICK-START-GUIDE.md` — Phase 5 (Hot Topics) shown as future work; it is complete

---

## Working Style Preferences

- ✅ Step-by-step Elementor instructions suitable for someone learning Theme Builder
- ✅ Explain **why** as well as **how** — helps volunteers understand and maintain
- ✅ Flag sections requiring plugins or custom CSS upfront
- ✅ Default to simple, maintainable solutions over complex ones
- ✅ Create actual files (Markdown) — don't just show content in chat
- ✅ Use clear naming conventions (`01-`, `02-`, etc.)
- ✅ Place all guide outputs in `/mnt/user-data/outputs/`
- ✅ Always read relevant SKILL.md files before creating documents
- ✅ **Use date and time for all document dating** — format `YYYY-MM-DD`, not month names (e.g. `2026-03-17`, not "March 2026")

---

## Environments

| Environment | URL | Purpose |
|-------------|-----|---------|
| Local dev | DEV-TIAAforum.og / wp-test/ | Active development |
| Discourse (test) | discourse-f2.test.tiaa-forum.org | SSO testing |
| Production (target) | tiaa-forum.org | Live site (current v2) |
| VPS (staging) | DigitalOcean — new Docker container | v3 staging |

---

## Repository Structure

```
tiaa-wpsite-v3/
├── docs/
│   ├── guides/               # Step-by-step how-to guides (01- through 10-)
│   └── project-reference/    # Reference documentation
├── exports/
│   └── elementor/            # Exported Elementor templates (JSON) — export before deployments
├── plugins/
│   ├── tiaa-wpplugin/        # Core plugin: Discourse integration, join flow, clickable cards
│   ├── tiaa-elementor-forms-invite-action/  # Elementor Pro form action for invite/join
│   └── tiaa-quick-edit/      # Admin utility: Sort Order + Excerpt in Quick Edit
└── [WordPress core files]
```

---

*Instructions version: 5.0 — Updated 2026-03-17*
