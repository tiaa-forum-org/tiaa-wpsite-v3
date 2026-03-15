# Content Model — TIAA Forum v3

*Last updated: 2026-03-14 — Revised to reflect actual build (Posts, not CPT). Added page widths, Discourse Categories, per-post workflow.*

> ⚠️ **Previous version of this document was stale.** It described Hot Topics as a Custom Post Type (CPT). The actual implementation uses standard WordPress Posts. Do not rely on any version of this document dated before March 2026.

---

## Page Width Reference

These values apply site-wide and are set in **Elementor Site Settings → Layout → Content Width**.

| Layout | Width |
|--------|-------|
| Single column content (post body, narrow pages) | 760px |
| Two-column content (homepage sections, archive grids) | 960px |
| Full content width (max, set in Elementor Site Settings) | 1200px |

Use these values when building templates, Loop Grids, or any Elementor layout where content width matters.

---

## WordPress Post Types Used

### 1. Standard WordPress Posts — Hot Topics

**What they are:** Curated summaries of popular Discourse forum discussions. NOT live forum content — these are manually written and maintained by volunteer editors.

| Property | Value |
|----------|-------|
| Post type | `post` (standard WordPress Posts) |
| Category | `Hot Topics` |
| Archive URL | `/hot-topics/` |
| Single post URL | Custom slug per post (e.g. `/aa-hotlines/`) |
| Sort order | `menu_order` field — lower number = higher priority |
| ACF custom field | Forum Thread URL (link to original Discourse thread) |
| Featured image | Required — used in card display on archive page and homepage |
| Template | Theme Builder → Single Post → condition: Posts in category: Hot Topics |

**Why standard Posts (not CPT)?**
- Better SEO — WordPress optimises Posts natively
- Built-in archive/category pages
- Simpler for volunteers to manage
- Standard WordPress workflow — no plugin dependency for the post type itself

**Current post count:** 11 posts (see `HotTopicPostOrder.md` for complete list with IDs, slugs, and `menu_order` values).

> ⚠️ **Naming note:** The category is currently labelled `Hot Topics` in WordPress but the URL slug may still read `hot-topics`. A rename of the display label (not the slug) has been discussed but not confirmed. Do not change the slug `/hot-topics/` without checking SEO impact.

---

### 2. Standard WordPress Posts — Discourse Categories

**What they are:** One WordPress post per Discourse forum category. These are used to populate the Forum Categories section on the homepage via a Loop Grid. They are NOT live Discourse data — content is managed manually.

| Property | Value |
|----------|-------|
| Post type | `post` (standard WordPress Posts) |
| Category | `discourse-categories` |
| Sort order | `menu_order` field — lower number = higher priority |
| Featured image | Required — used in card display on homepage |
| ACF custom fields | None currently — category description goes in post excerpt |
| Template | Loop Item template: `Category Card - Homepage` |

**Current categories (6):**

| Category Name | Discourse Category |
|---------------|-------------------|
| General Discussion | Open technology conversations |
| Websites & Apps | Building and maintaining A.A. websites |
| Meeting Technology | Audio/visual, hybrid meetings, virtual platforms |
| Data & Privacy | Security, anonymity, data management |
| Archives & History | Digital archiving, preserving A.A. history |
| Tools & Resources | Software recommendations, tutorials, how-to guides |

> **Future state:** These cards currently show category name and excerpt only. Topic counts and "last activity" timestamps (as seen in the Replit reference design) require Discourse API integration — deferred post-MVP.

---

## WordPress Pages

| Page | URL | Status | Visibility |
|------|-----|--------|------------|
| Homepage | `/` | ✅ Complete | Public |
| Hot Topics archive | `/hot-topics/` | ✅ Complete | Public |
| Join | `/join/` | ⚠️ Stub — HIGH PRIORITY | Public |
| About Us | `/about-us/` | ⚠️ Stub | Public |
| Contact Us | `/contact-us/` | ⚠️ Stub | Public |
| Contribute | `/contribute/` | ⚠️ Stub | Members only |
| Resources | `/resources/` | ⚠️ Stub | Public |
| The Forum | `/the-forum/` | 🔲 Not created | Public |

---

## ACF Field Groups

### Hot Topics Posts

| Field Label | Field Name | Type | Notes |
|-------------|-----------|------|-------|
| Forum Thread URL | `forum_thread_url` | URL | Link to original Discourse discussion thread |

> **Deferred:** Featured toggle field was originally planned. Replaced by `menu_order` for priority management. May be added post-MVP.

### Stats Panel (Homepage)

Currently hardcoded in Elementor widgets directly. Planned future state: TIAA Settings admin page (Pinned Decision #3 in governance doc).

| Stat | Current value (hardcoded) |
|------|--------------------------|
| Active Members | 1,247 |
| Discussions | 856 |
| Posts | 12.3k |
| Categories | 15 |

---

## Content Management Workflows

### Adding or Editing a Hot Topics Post

No Elementor editing required. The Theme Builder template handles all layout automatically.

1. Go to **Posts → Add New** (or duplicate an existing post as a draft)
2. Set the **post title** — this becomes the H1 on the page automatically
3. Set the **slug** — short, hyphenated, descriptive (e.g. `aa-hotlines`)
4. Set the **featured image** in the sidebar (not just in content) — required for card display
5. Set category to **Hot Topics only** — this triggers the correct Theme Builder template
6. Paste body content into the **WordPress block editor** — paste as plain text first to strip encoding artifacts
7. Fix any encoding artifacts (see encoding table below)
8. Add the **Discourse CTA button** at the bottom of the block editor content (URL is unique per post, not in template)
9. Set the **menu_order** (Page Attributes → Order) — lower number = higher priority on archive/homepage
10. Write a **manual excerpt** (2–3 sentences) — populates card teasers on the archive page
11. Fill in **Yoast SEO fields**: SEO Title and Meta Description
12. **Publish**
13. Verify on the front end: H1, image, content, CTA button, and breadcrumb all correct

#### Encoding Artifact Fix Table

When pasting content from old documents or Discourse, watch for these:

| Broken sequence | Correct character |
|-----------------|------------------|
| `â€™` | `'` apostrophe |
| `â€"` | `—` em dash |
| `â€œ` | `"` open double quote |
| `â€` + `\x9d` | `"` close double quote |

### Adding or Editing a Discourse Categories Post

1. Go to **Posts → Add New**
2. Set the **post title** = the category name (e.g. "Meeting Technology")
3. Set the **slug** (e.g. `meeting-technology`)
4. Set the **featured image** in the sidebar — required for card display
5. Set category to **discourse-categories only**
6. Write a **1–2 sentence excerpt** — this appears as the card description on the homepage
7. Set the **menu_order** to control display order on the homepage (lower = earlier)
8. **Publish**

### Changing Display Priority (for volunteers)

For both Hot Topics and Discourse Categories posts:
1. Go to **Posts** in WordPress admin
2. Find the post
3. Click **Quick Edit**
4. Find the **Order** field
5. Set a lower number for higher priority (0 or 1 = appears first)
6. Click **Update** — homepage and archive pages update automatically

> **Tip:** The Simple Page Ordering plugin can be installed to allow drag-and-drop ordering — more volunteer-friendly than entering numbers manually.

---

## What Is NOT Managed in Elementor Per Post

The following live in WordPress content, not in Elementor templates:

| Item | Location |
|------|----------|
| Post body content | WordPress block editor |
| Discourse CTA button + URL | WordPress block editor (bottom of each Hot Topics post) |
| Featured image | WordPress post sidebar (not content) |
| Post excerpt | WordPress excerpt field (sidebar) |
| Yoast SEO title and meta description | Yoast SEO panel in post sidebar |
| menu_order / priority | Page Attributes → Order (or Quick Edit) |

---

## Template Reference

| Template Name | Type | Condition | Used For |
|---------------|------|-----------|---------|
| TIAA-forum Header | Theme Builder — Header | Site-wide | Site-wide header |
| TIAA-forum Footer | Theme Builder — Footer | Site-wide | Site-wide footer |
| Single Hot Topic | Theme Builder — Single Post | Posts in category: Hot Topics | Individual topic pages |
| Hot Topics Archive | Theme Builder — Archive | Category: Hot Topics | `/hot-topics/` archive page |
| Home Page Hot Topics card | Loop Item | n/a | Hot Topics cards on homepage (3-col) |
| Category Card - Homepage | Loop Item | n/a | Category cards on homepage (4-col) |

Template IDs (test site — verify on production):

| Template | ID |
|----------|----|
| Site header | `1480` |
| Single Hot Topic | `3484` |
| Site footer | `1478` |

---

## Resources (Future — Post-MVP)

Planned as a Custom Post Type once content is defined. For MVP, the `/resources/` page is a stub.

Fields planned:
- Title
- Summary
- Body
- Resource Type
- External URL
- Icon

---

*Supersedes all previous versions of `03-content-model.md`. Cross-reference: `HotTopicPostOrder.md`, `05-hot-topics-system.md`, `06-discourse-categories-system.md`.*
