# 04 — Homepage Build Guide

**Status:** ✅ Built — March 2026  
**Replaces:** `06 - Homepage Complete Build Guide.md` (retired)  
**Audience:** Volunteer maintainers, future developers  
**Prerequisites:** Global Colors ✅, Global Fonts ✅, Header ✅, Footer ✅

---

## Purpose of This Guide

This guide serves two functions:

1. **Build record** — documents what was actually built, every structural decision, and why choices were made the way they were. If you are setting up a fresh environment, follow this top to bottom.
2. **Maintenance reference** — tells volunteers exactly what to touch (and what not to touch) for day-to-day content updates.

If you only need to update content, jump directly to the [Maintenance Quick Reference](#maintenance-quick-reference) at the bottom.

---

## How the Homepage Is Built

The homepage is a **standalone WordPress page edited directly in Elementor** — it is *not* controlled by a Theme Builder template. The Header and Footer are Theme Builder templates that wrap every page including this one.

### The Two-Layer Container Rule

Every section on this homepage uses the same structural pattern. Understanding it will prevent the most common layout mistakes:

```
Section (outer container)
  └─ Full Width — background colour stretches edge to edge
        └── Inner container
              └─ Boxed — content stays within 1200px max-width
```

**Never set an inner container to Full Width.** That is the single most common cause of content spilling beyond the page boundary.

---

## Homepage Section Map

| # | Section Title | Background | Primary Widget |
|---|--------------|-----------|---------------|
| 1 | Hero | Light teal gradient | Headings + 2 buttons |
| 2 | Two Ways to Learn and Share | White (#ffffff) | Two 50/50 cards |
| 3 | Selected Forum Conversation Topic Summaries | Light gray (#f8f9fa) | Loop Grid (Hot Topics, 3 cols) |
| 4 | What You Can Do in the Forum | White (#ffffff) | 60/40 split: icon list + stats card |
| 5 | Forum Categories | Light gray (#f8f9fa) | Loop Grid (Categories, 4 cols) |

The alternating white / light-gray pattern creates visual section separation. **Preserve this pattern if adding new sections.**

---

## Section 1 — Hero

### What it does
First impression for all visitors. Communicates the site purpose and provides two primary calls to action.

### Why it looks the way it does
The Replit reference had a bold hero with a gradient background. We preserved that intent with a light teal-to-white gradient that echoes the brand without feeling heavy.

### Structure
```
Outer container (Full Width, light teal gradient)
  └── Inner container (Boxed, centered text)
        ├── Heading — H1, Navy — "Technology in Alcoholics Anonymous"
        ├── Heading — H2, Teal — "Online Community"
        ├── Text Editor — body copy
        └── Inner container (Row, center-justified)
              ├── Button — "Browse Hot Topics" (navy bg → /hot-topics)
              └── Button — "Join the Discussion" (coral bg → /join)
```

### To update
Edit text widgets directly in Elementor. The gradient is on the outer container → Style tab → Background → Gradient.

---

## Section 2 — Two Ways to Learn and Share

### What it does
Immediately after the hero, this section presents the two distinct paths: browsing curated Hot Topics (no login required) or entering the live Forum (requires signup). It routes visitors to the right next step before asking them to commit.

### Why it looks the way it does
The Replit reference showed full-viewport stacked mobile cards. The Elementor build uses a 50/50 desktop split because side-by-side communicates "choose your path" more effectively on desktop — visitors can compare options at a glance rather than scrolling.

**Important:** The button on Card 2 is conditional — it changes based on whether the user is logged in:
- **Logged out** → "What is a Forum?" → `/the-forum` (explainer page)
- **Logged in** → "Go to Forum →" → Discourse URL (SSO handles login automatically)

This is the only piece of conditional logic on the homepage itself. All other conditional behaviour is in the Header.

### Structure
```
Outer container (Full Width, white)
  └── Inner container (Boxed)
        ├── Heading — H2, Navy, centered — "Two Ways to Learn and Share"
        ├── Text Editor — subtitle, centered
        └── Inner container (Row, 40px gap)
              ├── Card 1 container (50%, white bg, rounded border)
              │     ├── Icon Box (teal bg, book icon)
              │     ├── Heading — "Option 1: <span teal>Hot Topics</span>"
              │     ├── Text Editor — description
              │     ├── Icon List — 2 checkmark items (teal icons)
              │     └── Button — "Explore Hot Topics →" (teal → /hot-topics)
              └── Card 2 container (50%, white bg, rounded border)
                    ├── Icon Box (coral bg, chat icon)
                    ├── Heading — "Option 2: <span coral>The Forum</span>"
                    ├── Text Editor — description
                    ├── Icon List — 2 checkmark items (coral icons)
                    ├── Button A — "What is a Forum?" (coral → /the-forum)
                    │     Display Condition: User Logged Out
                    └── Button B — "Go to Forum →" (coral → Discourse URL)
                          Display Condition: User Logged In
```

### Editing display conditions
Select either conditional button → Advanced tab → Display Conditions. Do not delete these conditions when editing card text — they are invisible in the editor but critical to correct behaviour.

---

## Section 3 — Hot Topics

### What it does
Shows three featured Hot Topics posts as image cards. Uses a **live Loop Grid** — the homepage updates automatically when post `menu_order` values change. No Elementor editing required for routine content management.

### Why it looks the way it does
The Replit reference showed text-only cards with contributor counts and timestamps. We use featured-image cards for two reasons:
1. Contributor counts and live timestamps would require Discourse API calls, which are out of scope for MVP
2. Featured images are more visually engaging and are managed entirely through WordPress — no developer needed

The section header row (heading left, "View all topics" button right) is a space-efficient layout pattern that also appears in Section 5. Keeping it consistent trains users to know where the "see more" action lives.

### Structure
```
Outer container (Full Width, light gray #f8f9fa)
  └── Inner container (Boxed)
        ├── Header row (Row, Space Between, Align Center, margin-bottom 32px)
        │     ├── Heading — H3, Navy, left — "Selected forum conversation topic summaries:"
        │     └── Button — outline teal — "View all topics →" (→ /hot-topics)
        └── Loop Grid widget
```

### Loop Grid settings
| Setting | Value |
|---------|-------|
| Template | `Home Page Hot Topics card` |
| Columns | 3 |
| Gap | 20px |
| Source | Posts |
| Filter by Category | Hot Topics |
| Order By | Menu Order |
| Order | ASC |
| Posts Per Page | 3 |

### Card template: `Home Page Hot Topics card`
This is a saved Loop Item template in Elementor → Theme Builder → Loop Items. **Edit the template there, not inline.**

```
Outer container (100% width, white bg, 8px border-radius)
  ├── Featured Image (full width, 200px height, object-fit: cover)
  └── Inner container (padding 16px)
        ├── Heading widget — Post Title (dynamic tag), H4, 14px, Navy
        │     Link set to Post URL (dynamic tag)
        └── Text Editor widget — Excerpt (dynamic tag), word limit ~15
```

> **Container width must be 100%.** The Loop Grid columns control how wide each card is — the template itself must fill whatever space the grid gives it.

### Clickable cards (the whole card, not just the title)
Whole-card click is handled by a JavaScript overlay in `tiaa-wpplugin`. The snippet fires only on `is_front_page()`. It finds every `.e-loop-item`, reads its first `<a>` href, and appends a transparent overlay anchor covering the full card.

Do not add `onclick` attributes in Elementor. If the clickable-card behaviour needs changing, edit the plugin — do not work around it in Elementor.

### How volunteers manage which 3 cards appear
No Elementor editing needed. In WordPress Admin:
1. Posts → find the post
2. Quick Edit → set the **Order** field
3. Lower number = appears first (1 beats 5 beats 10)
4. Save — homepage updates immediately

---

## Section 4 — What You Can Do in the Forum

### What it does
Conversion section. Answers "what's in it for me?" with a four-item feature list, then provides social proof via a Forum Statistics card. The "Join the Forum" button here is a second conversion point for visitors who scrolled past the hero without clicking.

### Why it looks the way it does
The Replit reference included a "Join The Forum Community" heading above this section. We removed it — the hero and Two Ways sections already carry the join message, so a third repetition felt forced. The 60/40 column split and the navy stats card were retained directly from the Replit reference as they worked well and translated cleanly to Elementor.

Stats are **hardcoded** in Elementor text widgets. A TIAA Settings admin page to manage them without opening Elementor is planned (see Pinned Decision #3 in `tiaa-v3-design-governance-v2.md`).

### Structure
```
Outer container (Full Width, white)
  └── Inner container (Boxed, Row, 60px gap)
        ├── Left column (60%)
        │     ├── Heading — H2, Navy — "What You Can Do in the Forum?"
        │     └── Icon List widget (4 items)
        │           ├── Ask Questions (teal icon)
        │           ├── Share Experience (coral icon)
        │           ├── Explore Developments (teal icon)
        │           └── Build Community (coral icon)
        └── Right column (40%, navy bg #2b2e60, border-radius 12px, padding 40px)
              ├── Heading — white, centered — "Forum Statistics"
              ├── Inner container (2×2 grid)
              │     ├── Stat: 1,247 / Active Members
              │     ├── Stat: 856 / Discussions
              │     ├── Stat: 12.3k / Posts
              │     └── Stat: 15 / Categories
              ├── Button — teal bg — "Join the Forum →" (→ /join)
              └── Text Editor — "Questions? Email us at info@tiaa-forum.org"
```

### To update statistics (until TIAA Settings admin page is built)
1. Open the homepage in Elementor
2. Click the number widget you need to change
3. Update the text directly
4. Update → Publish

> ⚠️ Statistics are the only content on this page that requires opening Elementor. Everything else is managed via WordPress posts or menus.

---

## Section 5 — Forum Categories

### What it does
Shows all forum categories as a 4×2 grid, helping visitors understand the scope of topics available. Uses a live Loop Grid ordered by `menu_order`.

### Why it looks the way it does
The Replit reference used a 3-column grid with icon + topic count + last activity timestamp. We made two changes:

**4 columns instead of 3:** Category names are short (often one or two words). Four columns uses horizontal space more efficiently and gives the section a denser, more comprehensive feel at a glance.

**Featured images instead of icon + metadata:** Topic counts and timestamps require live Discourse API calls. Featured images are a simpler, more maintainable substitute — volunteers can update them through the WordPress Media Library.

The "View all categories" outline button follows the same header-row pattern as Section 3 for visual consistency.

### Structure
```
Outer container (Full Width, light gray #f8f9fa)
  └── Inner container (Boxed)
        ├── Header row (Row, Space Between, Align Center, margin-bottom 32px)
        │     ├── Left container (Column)
        │     │     ├── Heading — H3, Navy — "Forum Categories"
        │     │     └── Text Editor — subtitle — "Organized discussions to help you find exactly what you're looking for"
        │     └── Button — outline teal — "View all categories →" (→ /categories or Discourse)
        └── Loop Grid widget
```

### Loop Grid settings
| Setting | Value |
|---------|-------|
| Template | `Category Card - Homepage` |
| Columns | 4 |
| Gap | 16px |
| Source | Posts |
| Filter by Category | discourse-categories |
| Order By | Menu Order |
| Order | ASC |
| Posts Per Page | 8 |

### Card template: `Category Card - Homepage`
Saved Loop Item template in Elementor → Theme Builder → Loop Items.

```
Outer container (100% width, white bg, 8px border-radius, subtle border)
  ├── Featured Image (full width, 120px height, object-fit: cover)
  └── Inner container (padding 12px)
        ├── Heading widget — Post Title (dynamic tag), H4, 15px, Navy
        │     Link set to Post URL (dynamic tag)
        └── Text Editor widget — Excerpt (dynamic tag)
```

### Managing category display order
Same as Hot Topics — WordPress Admin → Posts → Quick Edit → Order field.

### Clickable cards
Same JS overlay as Hot Topics — runs on `is_front_page()` via `tiaa-wpplugin`.

---

## Maintenance Quick Reference

### Content changes that require NO Elementor editing

| Task | Where to do it |
|------|---------------|
| Change which 3 Hot Topics cards appear | Posts → Quick Edit → Order field |
| Change which 8 Category cards appear | Posts → Quick Edit → Order field |
| Add a new Hot Topic | Posts → Add New → set category "Hot Topics" + featured image + Order |
| Add a new Category | Posts → Add New → set category "discourse-categories" + featured image + Order |
| Update navigation menu links | Appearance → Menus → Main Navigation |

### Content changes that DO require Elementor editing

| Task | Where in Elementor |
|------|-------------------|
| Update Forum Statistics numbers | Homepage → Section 4 → click stat number widget |
| Change hero headline or buttons | Homepage → Section 1 |
| Change card 1 or card 2 text (Two Ways section) | Homepage → Section 2 |
| Update any button links | Click the button widget → Content tab → Link field |

### After any Elementor edit
1. Click **Update** (green button, bottom-left of Elementor editor)
2. Click the eye icon → Preview Changes
3. Check both desktop and mobile views (use the responsive icons in the Elementor toolbar)
4. Hard-refresh your browser (Cmd+Shift+R on Mac, Ctrl+Shift+R on Windows) to clear cache

---

## Troubleshooting

**Content spills outside the max-width / stretches full screen**
→ An inner container is set to Full Width. Select it → Layout tab → Content Width → change to **Boxed**.

**Loop Grid shows no cards**
→ Check three things in order: (1) Posts exist and are Published, (2) Posts are assigned to the correct category, (3) Loop Grid Query tab has the right category selected.

**Whole-card click not working on homepage**
→ The JS is in `tiaa-wpplugin`. Verify the plugin is active. Verify WordPress Admin → Settings → Reading → "Your homepage displays" is set to **A static page** pointing to the homepage.

**Whole-card click working on pages other than the homepage**
→ The `is_front_page()` check may be failing. Check the plugin code — do not attempt to fix this in Elementor.

**Conditional button on Card 2 (Two Ways section) showing wrong state**
→ Select the button → Advanced tab → Display Conditions. Confirm "User Logged Out" is set on the "What is a Forum?" button and "User Logged In" is set on the "Go to Forum" button. These are separate Button widgets stacked on top of each other — do not delete either one.

**Stats card text appearing dark instead of white**
→ The text widget is inheriting a global color. Select the widget → Style tab → Color → override explicitly to White (#ffffff).

**Section backgrounds blending into each other**
→ Background color must be on the **outer** (Full Width) container, not the inner (Boxed) container. Check which container has the color set.

---

## What Is Not Yet Built (Related to Homepage)

These items are documented as next steps and do not block the current homepage:

| Item | Notes |
|------|-------|
| `/hot-topics` archive page | Needs Theme Builder Archive template — index page for all Hot Topics |
| `/categories` archive page | Needs Theme Builder Archive template |
| Single Hot Topic template | Theme Builder → Single Post — needed for individual topic pages |
| Hot Topics search and filter | Deferred — requires JetSmartFilters or equivalent — see Pinned Decision #9 |
| TIAA Settings admin page | Deferred — will replace hardcoded stats — see Pinned Decision #3 |

---

## Elementor Template Inventory (Homepage-Related)

| Template Name | Type | Location | Used In |
|--------------|------|----------|---------|
| `Home Page Hot Topics card` | Loop Item | Theme Builder → Loop Items | Section 3 Loop Grid |
| `Category Card - Homepage` | Loop Item | Theme Builder → Loop Items | Section 5 Loop Grid |

> Before any major deployment, export both templates: Elementor → Tools → Export Kit. Commit the JSON exports to `exports/elementor/` in the `tiaa-wpsite-v3` repo.

---

*Commit this file to: `docs/guides/04-homepage-build.md` in tiaa-wpsite-v3*  
*Supersedes: `docs/guides/06 - Homepage Complete Build Guide.md` — that file can be archived or deleted*
