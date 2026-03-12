# TIAA Forum v3 — Design Divergences from Replit Reference

**Document purpose:** This document records where and why the Elementor implementation diverged from the Replit vibe-coded reference design (https://techtaalk.replit.app/). It is intended as a record of intentional design decisions — not a list of mistakes — so that future contributors understand the reasoning behind what was built.

**Date:** March 2026  
**Author:** lewg  
**Relates to:** tiaa-wpsite-v3 homepage build

---

## 1. Two Ways to Learn and Share (Section 2)

### Replit Reference
- Mobile-first stacked layout: two cards displayed vertically, one per screen
- Each card takes up the full viewport width
- Large teal icon box at top of each card
- Section heading not visible — cards appeared to start immediately
- "Option 1: Hot Topics" and "Option 2: The Forum" as card titles with colored highlight on the option name
- Single CTA button per card, full-width, rounded, teal (Option 1) and coral (Option 2)
- Checklist items used teal checkmarks

### What Was Built in Elementor
- Desktop-first 50/50 side-by-side two-column layout
- Section heading "Two Ways to Learn and Share" added above cards with subtitle
- Cards are equal height, contained within 1200px max-width
- Icon box retained but proportionally smaller relative to card
- Card titles retained: "Option 1: Hot Topics" / "Option 2: The Forum" with colored spans
- Buttons not full-width — pill-style, centered within card
- For logged-out users: Option 2 button says "What is a Forum?" linking to the forum explainer page
- For logged-in users: Option 2 button says "Go to Forum" linking directly to Discourse

### Why
The Replit reference was designed mobile-first with single-column stacking. The Elementor build prioritizes the desktop experience where side-by-side cards communicate the "choose your path" concept more clearly. The full-width button style from Replit was preserved in spirit (pill shape, centered) without being literally full-width, which looked oversized on desktop. The conditional button text (What is a Forum? vs Go to Forum) was added because the two user states have genuinely different needs — non-members need education, members need direct access.

---

## 2. Hot Topics Section (Section 3)

### Replit Reference
- Section titled "Current Hot Topics" with a "CURATED DISCUSSIONS" pill badge above
- Centered heading and subtitle
- **Search bar** — "Search hot topics..." with a magnifying glass icon
- **Category filter dropdown** — "All Categories" dropdown below the search bar
- Card style: no featured image — text-only cards with:
  - Category tag (teal pill, e.g. "Communication")
  - "Updated X days ago" timestamp
  - Contributor count ("16 contributors")
  - Bold H2 title
  - 2-3 sentence description
  - No CTA button on the card
- Cards stacked vertically (mobile), presumably grid on desktop

### What Was Built in Elementor
- Section titled "Selected forum conversation topic summaries:"
- No pill badge above heading
- No search bar
- No category filter dropdown
- Card style: featured image (full width, 200px height, object-fit cover) + title + excerpt
- 3-column Loop Grid using WordPress Posts with Hot Topics category
- "View all topics →" outline button to the right of the heading
- Ordered by menu_order ASC (volunteer-managed priority)
- Whole card is clickable via JS overlay (links to individual post page)

### Why
**Search and filter:** These are post-MVP features. Elementor's Loop Grid does not natively support front-end search/filter without additional plugins (e.g. JetSmartFilters). Adding these now would significantly increase complexity and maintenance burden. Flagged for future implementation.

**Featured images vs text-only cards:** The Replit design used text-only cards with metadata (contributor count, timestamp, category tag). These metadata fields don't exist in our WordPress post structure — contributor count and real-time timestamps would require Discourse API calls. Featured images were used instead as a simpler, more visually engaging approach that volunteers can manage without code. Images also help users scan content more quickly.

**Section title wording:** "Selected forum conversation topic summaries" was chosen to accurately describe what this content is — curated summaries, not live forum content. This distinction matters for member trust.

**"View all topics" placement:** Replit showed a centered button below the cards. The Elementor build places it as an outline button to the right of the section heading, which is more space-efficient and communicates the secondary nature of the action relative to the cards themselves.

---

## 3. What You Can Do / Forum Statistics (Section 4)

### Replit Reference
- Full-width centered heading: "Join The Forum Community"
- Centered subtitle
- Then a 60/40 split section below:
  - Left (60%): Feature list "What You Can Do in the Forum" with 4 icon+title+description items
  - Right (40%): Navy stats card with 2x2 grid of stats + "Join the Forum" button + email link
- Feature list icons: colored (teal/coral) icon to the left of each item title
- Stats card: dark navy (#2b2e60) background, teal numbers, white labels

### What Was Built in Elementor
- Section heading: "What You Can Do in the Forum?" (no separate "Join The Forum Community" heading above)
- Same 60/40 split retained
- Feature list icons retained (Icon List widget with colored icons)
- Stats card retained: navy background, teal numbers, 2x2 grid, "Join the Forum →" button, email link
- Stats are currently hardcoded values

### Why
**Removed the "Join The Forum Community" heading:** It was redundant with the hero section messaging and the Two Ways section. The What You Can Do heading plus the stats card together communicate the same message more efficiently. Adding a third "join" headline in the middle of the page felt repetitive.

**Stats hardcoded:** The correct solution (a TIAA Settings admin page) was identified but deferred to post-deployment to meet the MVP launch deadline. This is a known technical debt item — see Pinned Decision #3 in the governance document.

**Icon List widget vs custom icons:** The Replit design showed custom colored icon boxes. Elementor's Icon List widget was used instead as it's simpler for volunteers to edit and maintain without touching code.

---

## 4. Forum Categories (Section 5)

### Replit Reference (desktop)
- Section titled "Forum Categories" with subtitle
- 3-column grid of category cards
- Each card: small colored icon (left-aligned), category name, 1-sentence description, topic count, "X days ago" timestamp
- Cards have subtle border and white background
- "View All Categories" button centered below the grid, teal, rounded
- Categories shown: General Discussion, Websites & Apps, Meeting Technology, Data & Privacy, Archives & History, Tools & Resources

### What Was Built in Elementor
- Section titled "Forum Categories" with same subtitle retained
- **4-column grid** (not 3) — smaller cards for lighter content
- Each card: featured image (top, full width), category name, excerpt of post)
- Cards use similar Loop Item template pattern to Hot Topics
- "View All Categories →" outline button to the right of the heading (same pattern as Hot Topics section)
- Background: light gray (#f8f9fa) alternating with white of Section 4
- Categories are WordPress Posts (discourse-categories) ordered by menu_order

### Why
**4 columns vs 3:** Category names are short (often 1 word) and excerpts are brief. 4 columns makes better use of horizontal space and gives the section a denser, more comprehensive feel without feeling cluttered.

**Featured images vs icon + metadata:** The Replit design used small colored icons and live metadata (topic count, last activity). Live metadata requires Discourse API integration which is out of scope for MVP. Featured images were substituted — they provide visual variety and are manageable by volunteers. Icon-based cards are flagged as a potential future improvement once the categories are finalized.

**Topic count and timestamps omitted:** These would require real-time Discourse API calls. Deliberately omitted for MVP — the categories content itself is the value, not the activity metrics.

**"View All Categories" position:** Same decision as Hot Topics — moved from centered below grid to right-aligned beside the heading. Consistent pattern across both sections.

---

## 5. Navigation / Header

### Replit Reference
- Simple nav: Home, Hot Topics, The Forum, About
- Two conditional buttons top-right: "Forum Home" (teal) and "Join Forum" (coral)
- No logout link visible

### What Was Built in Elementor
- Nav: Home, Hot Topics, Resources, About Us, Contact Us
- Logged-out button: coral "JOIN/SIGN IN" linking to /join
- Logged-in buttons: teal "GO TO FORUM" + coral "CONTRIBUTE" + text "logout" link
- "The Forum" removed from nav — replaced by "What is a Forum?" button in Two Ways section
- "About" renamed to "About Us"
- "Resources" added (stub page, dropdown post-MVP)
- Contribute button added for logged-in users with planned funding-level color change

### Why
**"The Forum" removed from nav:** For non-members, "The Forum" as a nav item is confusing — they don't yet know what the forum is. Moving it to the Two Ways section as "What is a Forum?" puts the explanation in context, at the moment of discovery. Members who are logged in can go directly to Discourse via the "GO TO FORUM" button.

**Contribute button:** The forum's sustainability depends on member contributions. Making Contribute a prominent, color-coded button for logged-in members (rather than burying it in a menu) communicates the importance of financial support. The planned color-change-by-funding-level feature reinforces the community stewardship model.

**Resources added:** A placeholder for the glossary, related organizations, and colophon. Better than having those pages orphaned with no nav path. Full dropdown implementation is post-MVP.

---

## 6. Footer

### Replit Reference (desktop)
- Three-column footer: About text (left), Quick Links (center), Get Started (right)
- About text: "TIAA-Forum connects Alcoholics Anonymous members..." (2 paragraphs)
- Quick Links: Hot Topics, Forum Home, What is a Forum?, What's it Like?, Forum Categories
- Get Started: Join the Forum, Contact Us, Recent Discussions + teal "Join Forum" button
- Copyright line: "© 2024 TIAA-Forum. A community resource for A.A. members exploring technology solutions."
- Disclaimer: "This website is not affiliated with Alcoholics Anonymous World Services, Inc."

### What Was Built in Elementor
- Three-column footer retained with same general structure
- Content adapted to reflect actual site pages
- Copyright and AAWS disclaimer retained — these are non-negotiable

### Why
The footer implementation closely followed the Replit reference. The main adaptation was updating link destinations to match actual built pages rather than the Replit placeholder links. The AAWS disclaimer was treated as fixed, non-negotiable legal language.

---

## 7. Features Present in Replit Reference But Deferred

The following Replit features were intentionally not built in the MVP:

| Feature | Replit Had It | Reason Deferred |
|---------|--------------|-----------------|
| Hot Topics search bar | Yes | Requires additional plugin (JetSmartFilters); post-MVP |
| Category filter dropdown | Yes | Same as above |
| Topic count on category cards | Yes | Requires Discourse API integration |
| Last activity timestamp on cards | Yes | Requires Discourse API integration |
| Contributor count on topic cards | Yes | Requires Discourse API integration |
| Category pill tags on topic cards | Yes | Not in current post data model; post-MVP |
| Recent Discussions footer link | Yes | Page not yet built |
| What's it Like? page | Yes | Page not yet built; content team task |

---

## 8. Features Built That Were Not in Replit Reference

The following were added during the Elementor build that did not appear in the Replit reference:

| Feature | Why Added |
|---------|-----------|
| Conditional navigation (Join vs Go to Forum) | Core to the two-state authentication model; Discourse SSO requirement |
| Contribute button with funding-level color | Forum sustainability communication; not in Replit scope |
| discourse-categories as separate post type | Emerged from content architecture decisions during build |
| menu_order priority management | Volunteer-friendly alternative to Featured toggle |
| Whole-card clickable via JS overlay | Better UX than title-only link; Elementor-native approach not available |
| "What is a Forum?" button replacing nav item | Better UX placement for non-member audience |

