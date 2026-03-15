# TIAA Forum Rebuild — Project Instructions for Claude

## Objective
Rebuild tiaa-forum.org using Elementor Pro based on a new design created in Replit. The goal is an MVP that volunteer maintainers with basic WordPress experience (but potentially limited Elementor Pro knowledge) can support long-term.

---

## Current Tech Stack
- **WordPress** with Hello Elementor theme
- **Elementor Pro** for all page building, templates, and conditional visibility
- **Yoast SEO** plugin — installed and active; provides breadcrumb widget used in Hot Topics single post template
- **Custom plugin**: tiaa-wpplugin (handles Discourse API integration, new member signups, welcome messages, and clickable card JS)
- **WP-Discourse plugin**: Discourse SSO integration (Discourse is the SSO provider)
- **WP SimplePay plugin**: Stripe payment processing for memberships (not yet configured)
- **ACF (Advanced Custom Fields)**: Custom fields for Hot Topics posts
- **Hosting**:
  - Development: Local on macOS with Docker Compose
  - Test/Production: DigitalOcean VPS with Docker Compose stack
- **Development environment**: IntelliJ IDEA with Docker Compose plugin
- **Version control**: GitHub - https://github.com/tiaa-forum-org/tiaa-wpsite-v3.git

---

## Current Build State (as of March 2026)

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
- 10 Hot Topics posts migrated from the original tiaa-forum.org site
- Each topic is a separate WordPress Post (not a single long page)
- Hot Topics archive page: `/hot-topics` — 3-column card grid, all 10 posts visible
- Single Hot Topic template built in Theme Builder
- Single post layout: title (left, large), featured image (right, ~40%), body content, links
- Yoast breadcrumb widget used at bottom of each post ("← back to Hot Topics")
- Anchor link SEO issue **resolved** — Yoast breadcrumb provides the back-navigation; old anchor-based URLs are handled via this approach
- `menu_order` field controls display priority (lower = higher)
- ACF Forum Thread URL field implemented on all posts

**See `HotTopicPostOrder.md` for the complete list of 10 posts with IDs and menu_order values.**

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
- **Single post template**: Title + featured image (right, ~40%) + body content + Yoast breadcrumb at bottom
- **Archive page**: 3-column Loop Grid at `/hot-topics`
- **Featured toggle**: Deferred — `menu_order` is the current priority mechanism
- **10 posts exist** — see `HotTopicPostOrder.md` for complete list with IDs and order values

### Discourse Categories
- **Post type**: WordPress Posts
- **Category**: discourse-categories
- **Sort order**: `menu_order` field
- **Featured image**: Required for card display

### Pages
- `/` — Homepage (complete)
- `/hot-topics` — Archive page (complete)
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
- Max content width: 1200px (Elementor Site Settings → Layout → Content Width)
- Section pattern: Full Width outer container (background) + Boxed inner container (respects 1200px)
- Section padding: 80px top/bottom standard

---

## Elementor Templates (Existing — Do Not Recreate)

| Template Name | Type | Used For |
|---------------|------|----------|
| TIAA-forum Header | Theme Builder — Header | Site-wide header with conditional nav |
| TIAA-forum Footer | Theme Builder — Footer | Site-wide footer |
| Home Page Hot Topics card | Loop Item | Hot Topics cards on homepage (3-col) |
| Category Card - Homepage | Loop Item | Category cards on homepage (4-col) |
| Single Hot Topic | Theme Builder — Single | Individual Hot Topic post pages |
| Hot Topics Archive | Theme Builder — Archive | `/hot-topics` archive page |

---

## Custom Code Locations

All custom code lives in **plugins**, not in Elementor Custom CSS/JS.

| Code Type | Location |
|-----------|----------|
| Discourse integration, join flow, welcome messages, clickable cards JS | `tiaa-wpplugin` |
| Elementor-specific JS/CSS | Elementor Forms / TIAA Invite Form Action plugin |
| Environment config (URLs, email) | `wp-config.php` PHP constants |

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
| `05-hot-topics-system.md` | ✅ Exists | Hot Topics archive + single template |
| `06-footer-template.md` | ⚠️ Missing | Footer build — template exists but guide not yet written |
| `07-join-page.md` | 🔲 Not started | /join page + WP SimplePay |
| `08-content-pages.md` | 🔲 Not started | About, Contact, Forum Explainer |
| `09-contribute-page.md` | 🔲 Not started | Member-only contribute page |

---

## Next Priorities (in order)

1. **`/join` page + WP SimplePay + Stripe** — CRITICAL PATH. No one can join without this.
2. **End-to-end signup testing** — payment → Discourse account → SSO login
3. **Content pages** — About Us, Contact Us, Forum Explainer (needed before nav links work)
4. **Contribute page** — member-only, needs Elementor visibility condition
5. **Footer guide** (`06-footer-template.md`) — document what was built

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

---

## Reference Documents (in docs/project-reference/)

- `01-architecture.md` — System overview, authentication flow, user states
- `02-environments-and-deployment.md` — Dev/staging/production environments
- `03-content-model.md` — ⚠️ STALE — says Hot Topics is a CPT; actual implementation uses WordPress Posts
- `04-elementor-system.md` — Elementor template system overview
- `05-component-library.md` — Component catalog with specs
- `HotTopicPostOrder.md` — Authoritative list of 10 Hot Topics posts with IDs and menu_order
- `conditional-navigation.md` — Complete auth + navigation specification
- `tiaa-design-governance-v2.md` — Design governance, change control, all 9 pinned decisions
- `tiaa-design-divergences.md` — How Elementor build diverged from Replit reference and why
- `tiaa-v3-design-build-v1-vs-v2-.md` — Build divergences from original governance spec

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
│   ├── guides/           # Step-by-step how-to guides (01- through 09-)
│   └── project-reference/ # Reference documentation
├── exports/
│   └── elementor/        # Exported Elementor templates (JSON) — export before deployments
├── plugins/
│   └── tiaa-wpplugin/    # Custom plugin code
└── [WordPress core files]
```

---

*Instructions version: 3.0 — Updated March 2026 to reflect completed homepage, Hot Topics system, and corrected guide numbering.*
