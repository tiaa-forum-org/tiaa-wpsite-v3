# Guide 05 — Hot Topics System

*Last updated: 2026-03-14 — Fully revised to reflect completed build. Incorporates Hot Topics Reference document from source repo.*

> **Status:** ✅ COMPLETE — All 11 posts exist. Archive page and single post template are built and working. This guide documents what was built and how to maintain it.

---

## What Is the Hot Topics System?

Hot Topics are **curated summaries** of popular Discourse forum discussions. They are NOT live forum content — they are written and maintained manually by volunteer editors, and each topic lives at its own WordPress URL with proper SEO structure.

There are currently **11 Hot Topics posts** (see `HotTopicPostOrder.md` for the complete list).

---

## Architecture Overview

| Component | Implementation |
|-----------|---------------|
| Post type | Standard WordPress Posts |
| Category | `Hot Topics` |
| Archive page | `/hot-topics/` — Theme Builder Archive template |
| Single post | Custom slug per post — Theme Builder Single Post template |
| Card display | Elementor Loop Grid with Loop Item template |
| Sort order | `menu_order` field (lower = higher priority) |
| Custom fields | ACF — Forum Thread URL |
| Back-navigation | Yoast SEO breadcrumb widget (bottom of single post template) |

---

## Template Structure

### Single Post Template (Theme Builder)

**Template ID:** `3484`  
**Display condition:** Posts in category: Hot Topics

```
Container (full width, boxed inner) — flex row
  ├── Container (50%) — H1 Heading widget [dynamic: Post Title, centred]
  └── Container (50%) — Image widget [dynamic: Featured Image]

Container (full width) — Post Content widget
  [Renders WordPress block editor content for each post]

Container (full width) — Yoast Breadcrumb widget
  ← Back to Hot Topics [links to /hot-topics/]
```

**Key implementation details:**
- All layout uses **Flexbox Containers** — no legacy Elementor Sections or Columns
- H1 comes from the **dynamic Post Title tag** — auto-populated from WordPress post title
- Featured image comes from the **dynamic Featured Image tag** — set in WP post sidebar
- **Post Content widget** (not Text Editor widget) renders block editor content
- The **Discourse CTA button lives in each post's block editor content** — not in the template — because the URL is unique per post
- Breadcrumb at the bottom is the Yoast SEO breadcrumb widget — provides the "back to Hot Topics" navigation

### Archive Template (Theme Builder)

**Display condition:** Category: Hot Topics  
**URL:** `/hot-topics/`

- 3-column Loop Grid using the Hot Topics card Loop Item template
- Cards sorted by `menu_order` ASC (lowest number first)
- Each card shows: featured image, title, excerpt, and links to the individual post

### Loop Item Templates

Two Loop Item templates exist for Hot Topics:

| Template Name | Used In | Columns |
|---------------|---------|---------|
| Home Page Hot Topics card | Homepage — Section 5 | 3 |
| Hot Topics Archive card | `/hot-topics/` archive page | 3 |

Both templates display: featured image, post title, excerpt. The card is made fully clickable via a JS overlay snippet in `tiaa-wpplugin`.

---

## Template IDs Reference

| ID | Type | Description |
|----|------|-------------|
| `1480` | Header (elementor_library) | Site header — no H1, navigation only |
| `3484` | Single Post (Theme Builder) | Hot Topics post template |
| `1478` | Footer (elementor_library) | Site footer |

> ⚠️ **H1 conflict check:** The header template (`1480`) contains navigation buttons only — no H1 or site-name heading. Each Hot Topics post's H1 comes from the template's dynamic Post Title widget without conflict. **Before going live on production:** verify the live site header does not still contain an H1 Heading widget. If it does, change that widget's HTML tag to `p` in the header template.

---

## SEO Structure

### Per-Post Requirements

| Element | Value |
|---------|-------|
| H1 | Dynamic post title — set automatically by template |
| Yoast SEO Title | `[Topic Title] - TIAA Forum` |
| Meta Description | 150–160 characters, plain language |
| Slug | Short, hyphenated (e.g. `aa-hotlines`) |
| Canonical URL | Set automatically by Yoast to post URL |
| Featured image | Must be set in WP sidebar (not just in content) |
| Excerpt | 2–3 sentences — written manually in Excerpt field |

### Featured Image Requirements

- Set as **WordPress Featured Image** in the post sidebar
- Feeds: template image widget, Yoast `primaryImageOfPage` schema, Open Graph tags
- Format: **WebP strongly preferred** — all JPG images should be converted on upload
- Alt text: descriptive of image content
- Do NOT use lazy loading on above-the-fold images (the template image is above the fold)

### Archive Page SEO

- H1: "Hot Topics"
- Meta description: set via Yoast on the archive page, covering the range of topics
- Breadcrumb: Home → Hot Topics

---

## Per-Post Workflow

No Elementor editing is needed per post. All content goes into the WordPress block editor. The Theme Builder template handles layout automatically.

### Step-by-Step

1. **Duplicate the reference post** — keep as Draft; never publish the reference. Use as a template backup.
2. **Set title** — this becomes the H1 on the page automatically.
3. **Set slug** — short, hyphenated, descriptive (see `HotTopicPostOrder.md`).
4. **Set featured image** in the WP sidebar — not just in content. This is required.
5. **Set category to Hot Topics only** via Quick Edit — this triggers the Theme Builder template.
6. **Paste body content** into the WordPress block editor.
   - Paste through a plain text editor first to strip hidden formatting
   - Fix encoding artifacts (see table below)
7. **Add Discourse CTA button** at the bottom of block editor content:
   - URL is unique per post — confirm from `HotTopicPostOrder.md` or Discourse
   - Open in new tab with `rel="noopener"`
8. **Set menu_order** (Page Attributes → Order) — lower = higher priority
9. **Write manual excerpt** (2–3 sentences) in the Excerpt field
10. **Fill in Yoast SEO fields** — SEO Title and Meta Description
11. **Publish**
12. **Verify on front end:** H1, featured image, body content, Discourse CTA button, and Yoast breadcrumb all present and correct

### Encoding Artifact Fix Table

Always paste through a plain text editor first. Then fix these common encoding problems:

| Broken sequence | Correct character |
|-----------------|------------------|
| `â€™` | `'` apostrophe |
| `â€"` | `—` em dash |
| `â€œ` | `"` open double quote |
| `â€` + `\x9d` | `"` close double quote |

---

## Priority Management (for volunteers)

The `menu_order` field controls which posts appear first on the archive page and on the homepage. Lower numbers = higher priority.

**To change which posts appear in the homepage grid (top 3):**
1. Go to **Posts** in WordPress admin
2. Find the post you want to prioritise
3. Click **Quick Edit**
4. Change the **Order** field to a lower number
5. Click **Update** — the homepage and archive update automatically, no Elementor editing needed

Current `menu_order` values are spaced by 1000 (1000, 2000, 3000…) to leave room to insert posts without renumbering the full list.

> **Volunteer tip:** The **Simple Page Ordering** plugin can be installed to allow drag-and-drop post ordering in the WordPress admin — more intuitive than entering numbers.

---

## Discourse Thread URLs

Each post requires a unique Discourse thread URL for its CTA button. The button lives in the block editor, not in the template.

| Post | Discourse URL |
|------|--------------|
| AA Hotlines | https://discourse.tiaa-forum.org/t/phone-service-for-24-7-hotline/10860/12 |
| All others | TBC — confirm before publishing each post |

---

## Known Issues

| Issue | Status | Action |
|-------|--------|--------|
| Header H1 conflict | ✅ Resolved on test site | Verify on production before going live |
| AA Hotlines meta description missing | ⚠️ Pending | Fill in Yoast SEO field before going live |
| AA Hotlines Vonage URL | ⚠️ Pending | Replace long tracking URL with `https://www.vonage.com` |
| JPG images need WebP conversion | ⚠️ Ongoing | Convert on upload for all non-WebP images |
| Character encoding artifacts | ⚠️ Ongoing | Always paste through plain text editor first |
| Website Starter Kit — duplicate title? | ⚠️ Needs clarification | Two source documents show slightly different titles for what may be the same post (WP ID 3536) — confirm |

---

## What Is Complete vs Still Needed

| Item | Status |
|------|--------|
| Single post Theme Builder template | ✅ Built |
| Archive page | ✅ Built |
| Loop Item templates (homepage + archive) | ✅ Built |
| Clickable card JS (tiaa-wpplugin) | ✅ Implemented |
| 11 posts migrated | ✅ All exist (1 published on test, 10 pending full content) |
| ACF Forum Thread URL field | ✅ On all posts |
| Yoast breadcrumb back-navigation | ✅ In template |
| Discourse thread URLs confirmed | ⚠️ Only AA Hotlines confirmed |
| Yoast meta descriptions filled in | ⚠️ Pending for all posts |
| WebP image conversion | ⚠️ Pending for 10 of 11 posts |
| AA Hotlines Vonage URL fix | ⚠️ Pending |
| Hot Topics search + filter | 🔲 Deferred (requires JetSmartFilters or equivalent) |

---

## Elementor Implementation Notes

- All layout uses **Flexbox Containers** — do not introduce legacy Sections or Columns into the Hot Topics templates
- Use **Global Colors** from Elementor Site Settings — do not set hex values per widget
- Do not add new animation effects to the Hot Topics templates
- The Discourse CTA button style should match the site's global button style

---

*Cross-reference: `HotTopicPostOrder.md`, `03-content-model.md`, `06-discourse-categories-system.md`, TIAA-Forum-Hot-Topics-Reference.md (source repo)*
