# Hot Topic Post Order — Authoritative Reference

*Last updated: 2026-03-14 — Reconciled project instructions (10 posts) with Hot Topics Reference file (11 topics). 11 is correct. Added slugs, image files, and status.*

> ⚠️ **Discrepancy resolved:** The project instructions previously stated 10 Hot Topics posts. The Hot Topics Reference document (imported from the hot-topics redesign project) lists 11 topics. **11 is the correct number.** The project instructions have been updated accordingly.

---

## Sort Order

`menu_order` is set in the database via the `menu_order` field in the `wp_posts` table.

- Lower numbers = higher priority (appear first)
- Spacing between values (1000, 2000, etc.) leaves room to insert posts without renumbering
- To update: Posts → Quick Edit → Order field

---

## Complete Post List

| menu_order | Post Title | Slug | Image File | WP Post ID | Status |
|------------|------------|------|-----------|------------|--------|
| 1000 | AA Hotlines: Phone Services for 24/7 Alcoholics Anonymous Helplines | `aa-hotlines` | Telephone-Hotline-Hot-Topic.webp | 3421 | ✅ Published (test) |
| 2000 | A.A. Website Starter Toolkit | `aa-website-toolkit` | Website-Toolkit.jpg | 3536 | ⚠️ Pending |
| 3000 | Online/In-Person Hybrid Alcoholics Anonymous Meetings | `hybrid-meetings` | AA-Mtg-Zoom-AdobeStock_432693246.jpg | 3581 | ⚠️ Pending |
| 4000 | AA Website Starter Kit | `aa-website-starter-kit` | Website-Toolkit.jpg | 3536 | ⚠️ Pending — **see note** |
| 5000 | Google Workspace for Alcoholics Anonymous Service Groups | `google-workspace-nonprofits` | Google-Workspace-Hot-Topic.jpg | 3542 | ⚠️ Pending |
| 6000 | Code for Recovery (C4R) | `code-for-recovery` | Code-for-Recovery-Hot-Topic.jpg | 3587 | ⚠️ Pending |
| 7000 | Migrating an Alcoholics Anonymous Website to New Web Hosting | `website-migration` | Migrating-a-website-Hot-Topic.jpg | 3606 | ⚠️ Pending |
| 8000 | Digital 7th AA Tradition | `digital-7th-tradition` | Online-contributions-AdobeStock_217569408.jpg | 3468 | ⚠️ Pending |
| 9000 | Use of Electronic Voting for A.A. Purposes | `electronic-voting-aa` | Online-Polls-Hot-Topic.jpg | 3557 | ⚠️ Pending |
| 9100 | Fellowship Connection | `fellowship-connection` | Fellowship-Connection-Hot-Topic.jpg | 3603 | ⚠️ Pending |
| 9200 | The A.A. Archives Category | `aa-archives` | Archives-graphic.jpg | 3593 | ⚠️ Pending |

> **Note on rows 2000 and 4000:** The two source documents list what appears to be the same post under slightly different titles ("A.A. Website Starter Toolkit" vs "AA Website Starter Kit"). WP Post ID 3536 appears in both. Confirm whether this is one post with a working title discrepancy, or two separate posts. Resolve before publishing.

---

## Discourse Thread URLs

| Post Title | Discourse URL |
|------------|--------------|
| AA Hotlines | https://discourse.tiaa-forum.org/t/phone-service-for-24-7-hotline/10860/12 |
| All others | TBC — confirm URL before publishing each post |

---

## Image Conversion Status

All images except AA Hotlines (Topic 1) are currently JPG. Convert to WebP on upload — this is an SEO and performance requirement.

| Image File | Format | Action |
|-----------|--------|--------|
| Telephone-Hotline-Hot-Topic.webp | WebP | ✅ Already correct |
| Website-Toolkit.jpg | JPG | Convert to WebP on upload |
| AA-Mtg-Zoom-AdobeStock_432693246.jpg | JPG | Convert to WebP on upload |
| Online-Polls-Hot-Topic.jpg | JPG | Convert to WebP on upload |
| Google-Workspace-Hot-Topic.jpg | JPG | Convert to WebP on upload |
| Code-for-Recovery-Hot-Topic.jpg | JPG | Convert to WebP on upload |
| Migrating-a-website-Hot-Topic.jpg | JPG | Convert to WebP on upload |
| Online-contributions-AdobeStock_217569408.jpg | JPG | Convert to WebP on upload |
| Fellowship-Connection-Hot-Topic.jpg | JPG | Convert to WebP on upload |
| Archives-graphic.jpg | JPG | Convert to WebP on upload |
| Mobile-Phone-Meeting-Guide-app-AdobeStock_299062890.jpg | JPG | Convert to WebP on upload |

---

## Known Issues (Per Post)

| Post | Issue | Status |
|------|-------|--------|
| AA Hotlines | Meta description not set — Yoast admin notice visible | ⚠️ Action required before going live |
| AA Hotlines | Vonage link uses long tracking URL with UTM parameters | ⚠️ Replace with `https://www.vonage.com` |
| All posts | Confirm Discourse thread URLs before publishing | ⚠️ Pending |
| All pending posts | JPG images need WebP conversion | ⚠️ On upload |

---

## SEO Requirements Per Post

| Yoast Field | Format |
|-------------|--------|
| SEO Title | `[Topic Title] - TIAA Forum` |
| Meta Description | 150–160 characters, plain language, describes topic and who it helps |
| Slug | Short, hyphenated, descriptive (see table above) |

---

## Template Reference

The single post layout is controlled by Theme Builder template ID `3484`:

```
Container (full width, boxed) — flex row
  ├── Container (50%) — H1 Heading widget [dynamic: Post Title]
  └── Container (50%) — Image widget [dynamic: Featured Image]

Container (full width) — Post Content widget
  [Renders WordPress block editor content]

Container (full width) — Breadcrumb
  ← Back to Hot Topics [static link to /hot-topics/]
```

The H1, featured image, and breadcrumb are all handled by the template. All body content and the Discourse CTA button live in the WordPress block editor per post.

---

*Cross-reference: `03-content-model.md`, `05-hot-topics-system.md`, TIAA-Forum-Hot-Topics-Reference.md (source repo)*
