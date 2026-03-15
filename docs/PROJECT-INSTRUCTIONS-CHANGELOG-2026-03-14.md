# Project Instructions — Change Log

*Session: 2026-03-14*

This documents all changes needed to the main project instructions based on this session's work.

---

## Changes to Apply to Main Project Instructions

### 1. Date/Time Format (all future documents)

Use **date and time**, not month only. Format: `YYYY-MM-DD HH:MM` or `YYYY-MM-DD` for date-only references.

**Example:**
- ❌ `March 2026`
- ✅ `2026-03-14`

Apply to: all new guide files, all updated reference documents, all "Last updated" timestamps.

---

### 2. Hot Topics Post Count — 10 → 11

**Correction:** The project instructions stated 10 Hot Topics posts. The correct number is **11**.

The Hot Topics Reference document (imported from the hot-topics redesign project repo) is authoritative — it lists 11 topics with full metadata (slugs, image files, Discourse URLs, status).

**Files updated:**
- `HotTopicPostOrder.md` — now shows all 11 posts with slugs, image files, and status
- `05-hot-topics-system.md` — corrected throughout
- `03-content-model.md` — corrected

**Action required:** Update the following line in project instructions:
> "10 posts exist — see HotTopicPostOrder.md for complete list"

Change to:
> "11 posts exist — see HotTopicPostOrder.md for complete list with IDs, slugs, menu_order values, and status"

---

### 3. Page Width Values — Add to Design System

Add to the **Design System** section of project instructions:

```
### Content Width Reference

| Layout | Width |
|--------|-------|
| Single column content (post body, narrow pages) | 760px |
| Two-column content (homepage sections, archive grids) | 960px |
| Max content width (Elementor Site Settings) | 1200px |
```

---

### 4. Guide Index — Update Status

Update the Guide Index table:

| File | Previous Status | New Status |
|------|----------------|-----------|
| `05-hot-topics-system.md` | ✅ Exists | ✅ Updated 2026-03-14 |
| `06-discourse-categories-system.md` | ⚠️ Missing (was footer guide) | ✅ Created 2026-03-14 |
| `07-footer-template.md` | ⚠️ Missing | ⚠️ Still needed |

> **Note on numbering:** Guide 06 was previously described as the "missing footer guide." It has been used for the Discourse Categories system guide, which was a higher priority. The footer guide should be created as `07-footer-template.md` (renumbered).

---

### 5. Reference Documents — Update Status

| Document | Previous Status | New Status |
|----------|----------------|-----------|
| `03-content-model.md` | ⚠️ STALE | ✅ Updated 2026-03-14 |
| `HotTopicPostOrder.md` | ✅ Existed (incomplete) | ✅ Updated 2026-03-14 — now includes slugs, image files, status |

---

### 6. New Reference from Imported Repo

Add to Reference Documents section:

```
- `TIAA-Forum-Hot-Topics-Reference.md` — (imported from hot-topics redesign repo) 
  Authoritative source for Hot Topics template architecture, template IDs, 
  per-post workflow, encoding fix table, known issues. 
  Cross-referenced into 05-hot-topics-system.md.
```

---

### 7. Completed Items to Update in "Current Build State"

Under **✅ COMPLETED**, update Hot Topics System entry:

```
**Hot Topics System — COMPLETE**
- 11 Hot Topics posts exist (not 10 — corrected 2026-03-14)
- Theme Builder template ID: 3484
- Archive URL: /hot-topics/ — 3-column card grid, all 11 posts
- Single post template: title (left, 50%), featured image (right, 50%), body content, Yoast breadcrumb
- Per-post workflow documented in 05-hot-topics-system.md
- Discourse CTA button lives in block editor per post (not in template)
- Known issues: meta descriptions pending, JPG→WebP conversions pending, Discourse URLs pending for 10 of 11 posts
```

---

### 8. Pending Clarification — Website Starter Kit Duplicate

Two source documents reference what may be the same post under slightly different titles:
- "A.A. Website Starter Toolkit" (Hot Topics Reference, Topic 2)
- "AA Website Starter Kit" (HotTopicPostOrder.md, WP Post ID 3536, menu_order 4000)

Both reference WP Post ID 3536. **Action required:** Confirm whether this is one post with a working title discrepancy, or two separate posts. Update `HotTopicPostOrder.md` and project instructions accordingly.

---

## New Files Created This Session

| File | Location | Purpose |
|------|----------|---------|
| `03-content-model.md` | `docs/project-reference/` | Full rewrite — corrects CPT stale info, adds page widths, full workflow |
| `HotTopicPostOrder.md` | `docs/project-reference/` | Updated — adds slugs, image files, status, 11th post |
| `05-hot-topics-system.md` | `docs/guides/` | Full rewrite — incorporates Hot Topics Reference file |
| `06-discourse-categories-system.md` | `docs/guides/` | New — complete Discourse Categories guide |

All files use `2026-03-14` date format per new dating convention.
