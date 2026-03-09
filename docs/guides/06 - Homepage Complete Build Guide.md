# 06 - Homepage Complete Build Guide
**Phase 6 - Completing the Homepage**
**Date**: February 23, 2026

---

## Overview

This guide covers building the remaining homepage sections in one session:
- **Section 2**: "Two Ways to Learn and Share" (two option cards: Hot Topics + The Forum)
- **Section 3**: "What You Can Do in the Forum" (feature list + stats card)
- **Section 4**: "Forum Categories" (6-card grid)
- **Section 5**: "Current Hot Topics" preview
- **Homepage Polish**: Final spacing, mobile check, links

**Reference files**: `/mnt/project/02options.png`, `/mnt/project/02WhatYouCanDo.png`, `/mnt/project/03Categories.png`, `/mnt/project/03HotTopics.png`

**Starting point**: Hero section is complete. You're in the Elementor editor on the homepage.

---

## Section 2: "Two Ways to Learn and Share"

### Why this section matters
Right after the hero, visitors need to understand the two distinct paths available to them: browsing curated Hot Topics (low commitment, anonymous-friendly) or joining the actual Forum (higher commitment, requires signup). This section sets clear expectations and routes visitors to the right next step.

### Layout Overview
- **Background**: White (#ffffff)
- **Centered heading + subtitle** at the top
- **Two side-by-side cards** of equal width (~50/50)
- Each card has: icon, "Option X:" label + colored title, description, bullet points with checkmarks, CTA button

**Reference file**: `/mnt/project/02options.png`

---

### Step 1: Add the Section Container

1. Click **+** below the Hero section
2. Add a **Container** → Full Width
3. Settings:
    - Background: White (#ffffff)
    - Content Width: Boxed
    - Padding: 80px top/bottom, 40px left/right

---

### Step 2: Section Header

Add a **Container** → Direction: Column → Align Items: Center → Margin Bottom: 48px

**Heading** widget:
- Text: `Two Ways to Learn and Share`
- HTML Tag: H2
- Font: Global Font "Heading 2" (Inter, 36px, Bold)
- Color: Navy (#2b2e60)
- Align: Center

**Text Editor** widget below heading:
- Text: `Whether you prefer to browse or participate, we have a place for you`
- Font: Global Font "Body Text" (Inter, 16px)
- Color: Dark Gray (#6c757d)
- Align: Center
- Margin Top: 12px

---

### Step 3: Two-Card Row

Add a **Container** → Direction: Row → Gap: 32px

Each card is a **Container** with:
- **Width**: 50%
- **Background**: White
- **Border**: 1px solid #e8e8e8
- **Border Radius**: 16px
- **Padding**: 40px
- **Direction**: Column

---

### Step 4: Build Card 1 — "Hot Topics"

Inside the first card container, add these elements:

**Icon box** (top of card):
- Add a **Container** → Width: 64px, Height: 64px → Background: Teal (#31bba6) → Border Radius: 12px → Align: Center/Center
- Inside it, add an **Icon** widget:
    - Choose: `book-open` or `list` icon
    - Size: 28px
    - Color: White
- Margin Bottom: 24px

**Heading** widget:
- Text (use HTML): `Option 1: <span style="color:#31bba6">Hot Topics</span>`
- HTML Tag: H3
- Font: Inter, 24px, Semi-Bold
- Base color: Navy (#2b2e60)
- Margin Bottom: 16px

> **Why HTML in heading**: This gives you "Option 1:" in navy and "Hot Topics" in teal in a single widget — same technique as the Hero heading.

**Description text**:
- Text Editor widget
- Text: `Curated collection of the most popular technology discussions from our forum. Our trusted servants have gathered the best insights into easy-to-access topics.`
- Font: Body Text style, Dark Gray
- Margin Bottom: 20px

**Checklist items** — add an **Icon List** widget:
- Add 2 list items:
    - `Organized by topic for easy browsing`
    - `Best practices and recommendations`
- Icon: Checkmark (`check` or `check-circle`)
- Icon Color: Teal (#31bba6)
- Text Font: Inter, 15px, Dark Gray
- Space Between: 8px
- Margin Bottom: 32px

**CTA Button**:
- Add a **Button** widget
- Text: `Explore Hot Topics →`
- Link: `/hot-topics` (use `#` for now)
- Style:
    - Background: Teal (#31bba6)
    - Text Color: White
    - Border Radius: 25px
    - Padding: 14px 32px
    - Width: Full (100%)
    - Font: Global Font "Button Text"

---

### Step 5: Build Card 2 — "The Forum"

**Duplicate Card 1** (right-click → Duplicate), then modify:

**Icon box**: Change background to Coral (#f37758), change icon to `comments` or `chat-bubble`

**Heading**: Change to:
- Text (HTML): `Option 2: <span style="color:#f37758">The Forum</span>`

**Description text**: Change to:
- `Direct access to all forum discussions where members ask questions, share experiences, and build community around AA technology topics.`

**Checklist items**: Change to:
- `Live discussions with community members`
- `Ask questions and share your experience`

**CTA Button**: Change to:
- Text: `Go to Forum →`
- Link: Forum URL (your Discourse URL, e.g. `https://forum.tiaa-forum.org`)
- Background: Coral (#f37758)

> 📸 **Screenshot checkpoint**: Two matching cards side-by-side, one teal-accented, one coral-accented. Should feel balanced and inviting.

---

---

## Section 3: "What You Can Do in the Forum"

### Why this section matters
This is your conversion section — visitors who scrolled past the hero are curious but undecided. The feature list answers "what's in it for me?" and the stats card provides social proof with real numbers.

### Layout Overview
- **Background**: White (#ffffff)
- **Two columns**: 60% left (feature list) / 40% right (stats card)
- **Padding**: 80px top/bottom

---

### Step 1: Add the Section Container

1. Click the **+** (Add Element) button below the Hero section
2. Add a new **Container** (flexbox)
3. In the container settings panel:
    - **Width**: Full Width
    - **Content Width**: Boxed (max-width ~1140px)
    - **Padding**: 80px top, 80px bottom, 40px left, 40px right
    - **Background**: White (#ffffff) — or use Global Color "Background"

> 📸 **Screenshot checkpoint**: Your homepage should now have a white section below the hero.

---

### Step 2: Create the Two-Column Inner Container

Inside the white section, add an **inner container**:
1. Click inside the white section → **Add Element** → **Container**
2. Set the inner container direction to **Row** (horizontal)
3. Set **Gap** between columns to 60px
4. This will hold your two columns

---

### Step 3: Build the Left Column (60% — Feature List)

Inside the row container, add a **Container** for the left column:
- **Width**: 60%
- **Direction**: Column (vertical)

**Add these elements in order:**

#### 3a. Section Heading

Add a **Heading** widget:
- Text: `What You Can Do in the Forum`
- HTML Tag: H2
- Style:
    - Font: Global Font "Heading 2" (Inter, 36px, Bold)
    - Color: Navy (#2b2e60) — Global Color "Text"
    - Margin Bottom: 32px

#### 3b. Four Feature Items

For each feature item, add an **Inner Container** set to **Row** direction with these contents:

| # | Icon | Title | Color | Description |
|---|------|-------|-------|-------------|
| 1 | `question-circle` or `chat-bubble` | Ask Questions | Teal | Get help from experienced Alcoholics Anonymous members who understand both technology and our traditions. |
| 2 | `share` or `heart` | Share Experience | Coral | Tell others about technology solutions that have worked well for your group or area. |
| 3 | `laptop` or `code` | Explore Development | Teal | Share your progress as you explore or develop technology solutions supporting Alcoholics Anonymous. |
| 4 | `group` or `users` | Build Community | Coral | Connect with tech-savvy Alcoholics Anonymous members from novices to industry experts. |

**For each feature item, do the following:**

1. Add a **Container** → Direction: Row → Gap: 16px → Margin Bottom: 24px
2. Inside it, add an **Icon** widget:
    - Choose an appropriate icon from the Elementor icon library
    - Size: 28px
    - Color: Teal (#31bba6) for items 1 & 3, Coral (#f37758) for items 2 & 4
    - **Align**: Top (not centered)
    - **Width**: Fixed at 36px so it doesn't stretch

3. Add another **Container** → Direction: Column → Flex Grow: 1
4. Inside that, add a **Heading** widget:
    - Text: Feature title (e.g., "Ask Questions")
    - HTML Tag: H3
    - Font: Global Font "Heading 3" (Inter, 24px, Semi-Bold)
    - Color: Navy (#2b2e60)
    - Margin Bottom: 4px

5. Add a **Text Editor** widget:
    - Paste the description text
    - Font: Global Font "Body Text" (Inter, 16px, Regular)
    - Color: Dark Gray (#6c757d)

> **Tip**: Build the first feature item completely, then **right-click → Duplicate** it three times and update the icon color, title, and description for each.

---

### Step 4: Build the Right Column (40% — Stats Card)

Add a second **Container** inside the row for the right column:
- **Width**: 40%
- **Background Color**: Navy (#2b2e60)
- **Border Radius**: 16px
- **Padding**: 40px all sides
- **Direction**: Column
- **Align Items**: Center

#### 4a. Stats Card Heading

Add a **Heading** widget:
- Text: `Forum Statistics`
- HTML Tag: H3
- Font: Inter, 22px, Semi-Bold
- Color: White (#ffffff)
- Align: Center
- Margin Bottom: 32px

#### 4b. Stats Grid (2×2)

Add an **Inner Container** → Direction: Row → Flex Wrap: Wrap → Gap: 16px

Inside it, add **4 stat boxes**, each as a Container:
- **Width**: Calc 50% minus gap (use ~47% or set to "50%" and let flexbox handle it)
- **Background**: Slightly lighter navy — use rgba(255,255,255,0.08) via Custom Background
- **Border Radius**: 12px
- **Padding**: 20px
- **Direction**: Column
- **Align Items**: Center

Each stat box contains:

**Number** (Heading widget):
- Text: `1,247` / `856` / `12.3k` / `15`
- Font: Inter, 32px, Bold
- Color: Teal (#31bba6) — Global Color "Primary"

**Label** (Text widget):
- Text: `Active Members` / `Discussions` / `Posts` / `Categories`
- Font: Inter, 13px, Regular
- Color: White with 70% opacity — use #b0b3c8 or similar
- Align: Center

> **Stats to use:**
> - 1,247 — Active Members
> - 856 — Discussions
> - 12.3k — Posts
> - 15 — Categories

#### 4c. Join Button

Add a **Button** widget below the stats grid:
- Text: `Join the Forum ↗`
- Link: `/join` (update when page exists, use `#` for now)
- Style:
    - Background: Teal (#31bba6)
    - Text Color: White
    - Border Radius: 25px
    - Padding: 14px 32px
    - Width: Full width (100%)
    - Margin Top: 24px
    - Font: Global Font "Button Text"

#### 4d. Help Text

Add a **Text Editor** widget below the button:
- Text: `Questions? Email us at ` then add a hyperlink to `info@tiaa-forum.org`
- Font: Inter, 13px
- Color: White at 60% opacity (#8a8db0 or similar)
- Align: Center
- Margin Top: 12px

> 📸 **Screenshot checkpoint**: Section 2 should now look like the design reference with the navy card on the right.

---

## Section 4: "Forum Categories"

### Why this section matters
Categories help visitors understand the scope of the forum and find topics relevant to them. This section builds credibility and helps potential members self-identify ("Oh, there's a whole section on Meeting Technology — that's my pain point!").

### Layout Overview
- **Background**: Light Gray (#f8f9fa) — Global Color "Light Gray"
- **Section heading + subtitle** centered at top
- **6 cards in a 3-column grid**
- **"View All Categories" button** centered below

---

### Step 1: Add the Section Container

1. Click **+** below Section 2
2. Add a **Container** → Full Width
3. Settings:
    - Background: Light Gray (#f8f9fa)
    - Content Width: Boxed
    - Padding: 80px top/bottom, 40px left/right

---

### Step 2: Section Header

Add a **Container** → Direction: Column → Align Items: Center → Margin Bottom: 48px

Inside it:

**Heading** widget:
- Text: `Forum Categories`
- HTML Tag: H2
- Font: Global Font "Heading 2"
- Color: Navy (#2b2e60)
- Align: Center

**Text Editor** widget:
- Text: `Organized discussions to help you find exactly what you're looking for`
- Font: Global Font "Body Text"
- Color: Dark Gray (#6c757d)
- Align: Center
- Margin Top: 12px

---

### Step 3: Build the 6-Card Grid

Add a **Container** → Direction: Row → Flex Wrap: Wrap → Gap: 24px

Then add **6 Card Containers**, each set to:
- **Width**: ~31% (three across with gap)
- **Background**: White (#ffffff)
- **Border Radius**: 12px
- **Padding**: 28px
- **Direction**: Column
- Add a subtle box shadow: `0 2px 8px rgba(0,0,0,0.06)` via Advanced → CSS Box Shadow

**Card contents (from top to bottom):**

**Row 1** — Icon + Category Name (side by side):
- Inner Container → Row → Gap: 12px → Align Items: Center → Margin Bottom: 12px
- **Icon** widget: 24px, colored per table below
- **Heading** widget: H3, Inter 18px Semi-Bold, Navy

**Description text**:
- Text Editor widget, Inter 14px, Dark Gray (#6c757d)
- Margin Bottom: 16px

**Footer row** (topics count + last post):
- Inner Container → Row → Justify Content: Space Between
- Two **Text** widgets: Inter 13px, #999999
    - Left: `127 topics`
    - Right: `2 hours ago`

---

### The 6 Categories

| # | Name | Icon | Icon Color | Description | Topics | Last Post |
|---|------|------|------------|-------------|--------|-----------|
| 1 | General Discussion | `commenting` or `comments` | Teal #31bba6 | Open conversations about technology in A.A. | 127 topics | 2 hours ago |
| 2 | Websites & Apps | `desktop` or `globe` | Navy #2b2e60 | Building and maintaining A.A. websites and applications | 89 topics | 1 day ago |
| 3 | Meeting Technology | `video-camera` or `wifi` | Coral #f37758 | Audio/visual setup, hybrid meetings, and virtual platforms | 156 topics | 3 hours ago |
| 4 | Data & Privacy | `shield` or `lock` | Coral #f37758 | Security, anonymity, and data management practices | 43 topics | 1 week ago |
| 5 | Archives & History | `archive` or `history` | Teal #31bba6 | Digital archiving and preserving A.A. history | 31 topics | 5 days ago |
| 6 | Tools & Resources | `wrench` or `cog` | Navy #2b2e60 | Software recommendations, tutorials, and how-to guides | 73 topics | 6 hours ago |

> **Tip**: Build Card 1 fully, then duplicate it 5 times and update each card's icon, title, description, and stats.

---

### Step 4: "View All Categories" Button

Below the grid, add a **Button** widget (not inside the grid container):
- Text: `View All Categories ↗`
- Link: `#` (placeholder — update when categories page exists)
- Style:
    - Background: Teal (#31bba6)
    - Text Color: White
    - Border Radius: 25px
    - Padding: 14px 36px
    - Align: Center
    - Margin Top: 48px

> 📸 **Screenshot checkpoint**: You should see a clean 3-column grid of white cards on a light gray background.

---

## Section 5: "Current Hot Topics" Preview

### Why this section matters
This shows real value from the forum — actual curated discussions. Using a live Loop Grid means the homepage always reflects your current priorities without any manual homepage edits. Volunteers just adjust `menu_order` on posts to control what appears.

### Layout Overview
- **Background**: White (#ffffff)
- **Section heading + subtitle** centered at top
- **Loop Grid**: 3 cards using existing card template, sorted by `menu_order`
- **Title list**: All other Hot Topics as a compact 2-column linked list
- **"View All Hot Topics →" button**

### Prerequisites
- [ ] Existing card Loop Item template is available in Elementor (imported/recreated in v3)
- [ ] Posts are categorized as "Hot Topics"
- [ ] `menu_order` values are set on posts to control priority (lower number = higher priority)

---

### Step 1: Add the Section Container

Add a **Container** → Full Width:
- Background: White (#ffffff)
- Content Width: Boxed
- Padding: 80px top/bottom, 40px left/right

---

### Step 2: Section Header

Add a **Container** → Column → Align Items: Center → Margin Bottom: 48px

**Pill badge**:
- Add a **Text** widget
- Text: `CURATED DISCUSSIONS`
- Font: Inter, 12px, Semi-Bold, Letter Spacing: 1.5px
- Color: Teal (#31bba6)
- Background Color: #e8f9f7
- Border Radius: 20px
- Padding: 6px 16px
- Align: Center
- Margin Bottom: 16px

**Heading**:
- Text: `Current Hot Topics`
- H2, Global Font "Heading 2", Navy, Center

**Subtitle**:
- Text: `Popular technology discussions curated from our forum community`
- Body Text style, Dark Gray, Center

---

### Step 3: Loop Grid — 3 Featured Cards

Add a **Loop Grid** widget:

**Layout tab**:
- Template: Select your existing Hot Topics card template
- Columns: 3
- Rows: 1 (this limits output to 3 cards)
- Gap: 24px

**Query tab**:
- Source: Posts
- Include by: Category → select "Hot Topics"
- Order By: `Menu Order`
- Order: ASC (lowest menu_order number appears first)
- Posts Per Page: 3

**No results**: Leave default or set to empty (won't show on homepage if posts exist)

Margin Bottom: 40px

> 📸 **Screenshot checkpoint**: Three cards from your actual Hot Topics posts should appear, showing the highest-priority items (lowest menu_order values).

---

### Step 4: Build the Title List Loop Item Template

This is a new minimal template — just a linked title, one line. You'll build it once in Theme Builder, then reference it in Step 5.

1. Go to **Elementor → Theme Builder → Loop Item → Add New**
2. Name it: `Hot Topics Title List Item`
3. Add a **Text** widget (not Heading — keeps it lightweight)
    - Click the dynamic tag icon (⚡) on the text field
    - Select **Post Title**
4. Link the text: In the Text widget → Content → Link → click ⚡ dynamic tag → select **Post URL**
5. Style:
    - Font: Inter, 15px, Regular
    - Color: Navy (#2b2e60)
    - Hover color: Teal (#31bba6) — set in Style tab
    - No padding, minimal margin (4px bottom)
6. **Publish** the template

---

### Step 5: Loop Grid — Title List

Back on the homepage, below the card Loop Grid, add a **Container** → Direction: Column → Margin Bottom: 40px

Add a small **Heading** widget above the list:
- Text: `All Hot Topics`
- H3, Inter 18px Semi-Bold, Navy
- Margin Bottom: 20px

Add a second **Loop Grid** widget inside a **Container** set to 2 columns:

> **Note**: Elementor's Loop Grid is single-column by default for this use case. To get 2 columns of titles, set the Loop Grid to 2 columns with 1 row per column.

**Layout tab**:
- Template: `Hot Topics Title List Item` (created in Step 4)
- Columns: 2
- Gap: 8px vertical, 40px horizontal

**Query tab**:
- Source: Posts
- Include by: Category → "Hot Topics"
- Order By: `Menu Order`
- Order: ASC
- Posts Per Page: 100 (show all — or set a high number)
- **Exclude**: If Elementor supports it, exclude posts already shown above. If not, it's fine to show all including the top 3 — the list is compact enough that duplication isn't confusing.

---

### Step 6: "View All Hot Topics" Button

Add a **Button** widget (centered):
- Text: `View All Hot Topics →`
- Link: `/hot-topics` (your Hot Topics index page URL)
- Style:
    - Background: Navy (#2b2e60)
    - Text Color: White
    - Border Radius: 25px
    - Padding: 14px 36px

---

### Managing Priority (for volunteers)

To change which 3 cards appear on the homepage:
1. Go to **Posts** in WordPress admin
2. Find the post you want to prioritize
3. Edit the post → find **Order** field (in the Page Attributes panel on the right, or in Quick Edit)
4. Set a lower number = higher priority (0 or 1 = appears first)
5. Save — homepage updates automatically, no Elementor editing needed

> **Tip**: Install the **Simple Page Ordering** plugin to let volunteers drag-and-drop post order visually instead of entering numbers manually. Volunteer-friendly and zero custom code.

---

## Homepage Polish Checklist

Once all sections are built, go through this checklist before saving:

### ✅ Spacing & Rhythm
- [ ] All sections use consistent 80px top/bottom padding
- [ ] Internal spacing between elements feels consistent (use 16, 24, 32, 48px increments)
- [ ] No section feels cramped or has too much empty space

### ✅ Colors
- [ ] All colors use Global Colors (not hardcoded hex where possible)
- [ ] Navy is consistently used for main headings
- [ ] Teal is used for primary actions and accents
- [ ] Coral is used sparingly for secondary emphasis
- [ ] Dark Gray is used for body/description text

### ✅ Typography
- [ ] All headings use Global Fonts
- [ ] Body text is consistently 16px/Dark Gray
- [ ] Small helper text (card footers, time stamps) is consistently 13-14px/#999999

### ✅ Buttons
- [ ] All buttons have 25-30px border radius (pill shape)
- [ ] Buttons have 14px/32-36px padding
- [ ] Button text is white on colored backgrounds
- [ ] Links are all set (even if to `#` as placeholder)

### ✅ Mobile Responsiveness
After saving, click the **mobile view** icon in Elementor's bottom toolbar:

- [ ] Hero section: Text is readable, buttons stack vertically
- [ ] Section 2: Two columns should stack (left above right)
- [ ] Stats card: 2×2 grid still works at mobile width
- [ ] Section 3: Cards stack to 1 column on mobile
- [ ] Section 4: Topic cards stack to 1 column on mobile

**Fix stacking issues**: Select any row container → Responsive tab → set Direction to "Column" on mobile breakpoint.

### ✅ Update Placeholder Links

When you have actual pages created, update these links:
| Location | Current | Target |
|----------|---------|--------|
| Header "Join" button | `#` | `/join` |
| Hero "Join the Discussion" | `#` | `/join` |
| Footer "Join Forum" | `#` | `/join` |
| Stats card "Join the Forum" | `#` | `/join` |
| Categories "View All" | `#` | `/forum-categories` or Discourse URL |
| Hot Topics "View All" | `#` | `/hot-topics` |

---

## Save and Preview

1. Click **Update** (green button, bottom left in Elementor)
2. Click **Preview Changes** to see the full page in browser
3. Walk through the page top to bottom — does it tell a clear story?
    - Hero: Who you are and what the forum offers
    - Section 2: What members can do (with social proof from stats)
    - Section 3: The breadth of topics covered
    - Section 4: Proof the community is active with real discussions

---

## What's Next After Homepage

Once the homepage is complete and looking good, the recommended next steps are:

### Immediate Priority: **Phase 4 — Membership Flow**
The `/join` page with WP SimplePay + Stripe is the critical path item. Until this works, no one can actually join the forum. Tackle this next.

**Steps for Phase 4**:
1. Create the `/join` page in WordPress
2. Install/configure WP SimplePay in test mode
3. Add payment form to the join page using Elementor + SimplePay shortcode
4. Test end-to-end: payment → Discourse account creation → SSO login

### Then: **Phase 3 — Content Pages**
- About page
- Contact page (with form)
- "The Forum" explainer page

These are needed before Phase 7 (conditional navigation) because the nav links need live pages.

---

## Troubleshooting

**Cards don't align to 3 columns?**
→ Check the parent container has Flex Wrap enabled and each card is set to ~31% width (not 33%, as gap takes space)

**Stats card text not white?**
→ In Text/Heading widgets, check Color setting — it may be inheriting a dark global color. Override explicitly to White.

**Mobile columns not stacking?**
→ Select the row container, go to Responsive tab, find mobile breakpoint, change Direction to "Column"

**Icon colors not matching?**
→ Make sure you're setting the icon color in the Style tab of the Icon widget, not the Advanced tab

**Section backgrounds bleeding into each other?**
→ Check that containers are set to Full Width (not boxed) for the background, with a separate inner Boxed container for the content

---

## Summary

| Section | Background | Key Elements |
|---------|------------|-------------|
| ✅ Hero | Light teal | Heading, subtitle, 2 buttons |
| Section 2 | White | "Two Ways" heading + 2 equal option cards (teal / coral) |
| Section 3 | White | 60/40 split: features + navy stats card |
| Section 4 | Light Gray | Section header + 6-card 3-column grid + button |
| Section 5 | White | Badge + heading + 3 topic cards + button |

---

*Guide created: February 23, 2026 | Part of TIAA Forum Rebuild documentation*
