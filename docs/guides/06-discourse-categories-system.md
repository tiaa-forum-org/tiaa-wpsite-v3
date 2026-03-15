# Guide 06 — Discourse Categories System

*Last updated: 2026-03-14 — New document. Covers the Forum Categories content system used in the homepage Section 4 and any future categories archive page.*

> **Status:** ✅ COMPLETE for MVP — 6 category posts exist and are displayed in the homepage 4-column Loop Grid. This guide documents how the system works and how to maintain it.

---

## What Are Discourse Categories?

Discourse Categories are WordPress Posts that represent the major discussion categories in the Discourse forum. They power the **Forum Categories section** on the homepage (Section 4 — the 4-column grid).

These are **not live Discourse data** — they are manually managed WordPress posts. Content (name, description) is set by editors. Live metadata like topic counts and "last activity" timestamps are deferred to post-MVP when Discourse API integration is added.

**Current count:** 6 category posts

---

## Architecture Overview

| Component | Implementation |
|-----------|---------------|
| Post type | Standard WordPress Posts |
| Category | `discourse-categories` |
| Sort order | `menu_order` field (lower = higher priority / appears first) |
| Featured image | Required — shown in card display |
| Card description | Post **excerpt** field (1–2 sentences) |
| Loop Item template | `Category Card - Homepage` |
| Homepage display | 4-column Loop Grid, Section 4 |
| Archive/index page | None currently — homepage grid is the only display |

---

## The 6 Current Categories

Based on the Replit reference design and current implementation:

| menu_order | Category Name | Description (excerpt) | Discourse Category |
|------------|---------------|----------------------|-------------------|
| 1000 | General Discussion | Open conversations about technology in A.A. | General Discussion |
| 2000 | Websites & Apps | Building and maintaining A.A. websites and applications | Websites & Apps |
| 3000 | Meeting Technology | Audio/visual setup, hybrid meetings, and virtual platforms | Meeting Technology |
| 4000 | Data & Privacy | Security, anonymity, and data management practices | Data & Privacy |
| 5000 | Archives & History | Digital archiving and preserving A.A. history | Archives & History |
| 6000 | Tools & Resources | Software recommendations, tutorials, and how-to guides | Tools & Resources |

> **Note:** The Replit reference design showed topic counts (e.g. "127 topics") and timestamps ("2 hours ago") on each card. These require live Discourse API integration and are **deliberately omitted from MVP**. The category name and description are the value for now.

---

## Homepage Display (Section 4)

### Layout

- **Section background:** Light gray (`#f8f9fa`) — alternates with the white of Section 3
- **Grid:** 4 columns (not 3 as in the Replit reference — see design divergences below)
- **Card content:** Featured image (top, full width), category name, excerpt
- **"View All Categories →" button:** Outline style, right-aligned beside the section heading

### Why 4 Columns (Not 3 as in Replit)?

Category names are short (often 1–2 words) and excerpts are brief. 4 columns makes better use of horizontal space and gives the section a denser, more comprehensive feel. The Replit reference used 3 columns with icon-based cards and live metadata; our implementation substitutes featured images for icons (simpler for volunteers to manage) and drops the live metadata.

### Card Clickability

Cards are made fully clickable via the JS overlay snippet in `tiaa-wpplugin`. The overlay wraps the entire card, linking it to the category post's URL. (MVP: the link goes to the WordPress post page. Future: could link directly to the Discourse category.)

---

## Adding or Editing a Category Post

1. Go to **Posts → Add New**
2. **Post title** = the category name (e.g. "Meeting Technology")
3. **Slug** = short, hyphenated (e.g. `meeting-technology`)
4. **Featured image** — set in the WP sidebar (not just in content). Required for the card display.
   - Represents the category visually
   - Format: WebP preferred
   - Alt text: descriptive
5. **Category** = `discourse-categories` only (no other categories)
6. **Excerpt** = 1–2 sentences describing the category. This is what appears as the card description on the homepage.
7. **menu_order** (Page Attributes → Order) = controls display order in the 4-column grid (lower = appears earlier/first)
8. **Body content** = optional. Currently these posts have no body beyond the excerpt, since there's no single-category page in the MVP. If a categories index page is added post-MVP, body content could be used there.
9. **Publish**

---

## Priority Management (for volunteers)

`menu_order` controls the left-to-right, top-to-bottom order of category cards in the homepage grid.

| menu_order | Appears |
|------------|---------|
| 1000 | First (top-left) |
| 2000 | Second |
| ... | ... |
| 6000 | Last (bottom-right in 4-col grid) |

**To reorder:**
1. Go to **Posts** in WordPress admin
2. Find the category post
3. Click **Quick Edit**
4. Change the **Order** field
5. Click **Update** — homepage updates automatically

---

## Loop Item Template: Category Card - Homepage

This is an Elementor Loop Item template. It controls how each category card looks in the homepage grid.

**Template name:** `Category Card - Homepage`

The template contains:
- Featured image (top of card, full width)
- Post title (category name)
- Excerpt (short description)

> Do not edit this template without understanding the impact on all 6 category cards simultaneously. Changes to the Loop Item template affect every card in the grid.

---

## Design Reference (Replit vs Built)

| Element | Replit Reference | What Was Built | Reason |
|---------|-----------------|---------------|--------|
| Columns | 3 | 4 | Short content fills 4 columns better |
| Card icon | Small colored icon (left-aligned) | Featured image (top, full width) | Icons require custom design per category; images are volunteer-manageable |
| Topic count | "127 topics" | Omitted | Requires Discourse API — post-MVP |
| Timestamp | "2 hours ago" | Omitted | Requires Discourse API — post-MVP |
| "View All Categories" | Centered button below grid, teal | Outline button, right of heading | Consistent pattern with Hot Topics section; space-efficient |

---

## Future State (Post-MVP)

| Feature | Notes |
|---------|-------|
| Live topic counts | Requires Discourse API integration via `tiaa-wpplugin` or cron job |
| "Last activity" timestamps | Same — Discourse API |
| Category icon design | Custom icons per category — design task |
| Forum Categories index page | Full `/forum-categories/` page with Archive template (similar to Hot Topics archive) |
| Direct Discourse link | Card could link directly to Discourse category instead of WP post page |

---

## Elementor Implementation Notes

- The 4-column Loop Grid is in **Section 4** of the homepage, built with the `Category Card - Homepage` Loop Item template
- **Query settings:** Source = Posts, Category = `discourse-categories`, Order By = `Menu Order`, Order = ASC
- **Columns:** 4 desktop, adjust for tablet (2) and mobile (1) in responsive settings
- All layout uses **Flexbox Containers** — no legacy Sections
- Use **Global Colors** — do not set hex values per widget
- Card background: White (`#ffffff`) on light gray section background

---

*Cross-reference: `03-content-model.md`, `05-hot-topics-system.md`, `04-homepage-complete-build.md` (Section 4)*
