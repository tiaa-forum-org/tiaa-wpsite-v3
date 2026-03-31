# Pinned Decisions — Deferred Items

**Document:** pinned-decision-custom-code-and-org-tags.md  
**Date:** 2026-03-29  
**Status:** Deferred — action required before public launch

---

## Pinned Decision A — Custom Code Summary Document

### What needs to be done

Create a comprehensive document (`docs/project-reference/custom-code-summary.md` in `tiaa-wpsite-v3`) that records all custom code across the three plugin repos, what each piece does, why it exists, and where to find it. This document is intended for future volunteer maintainers who may not have context on why specific code decisions were made.

### Why this matters

The TIAA Forum now has three custom plugins, each with distinct responsibilities. Without a central summary, a new Platform Sub-team member has no single place to understand what custom code exists and what it touches.

### Suggested document structure

```
1. Overview of custom plugins (names, repos, activation dependencies)
2. tiaa-wpplugin — Discourse integration, invite flow, welcome messages
3. tiaa-elementor-forms-invite-action — Elementor form action + clickable cards JS
4. tiaa-quick-edit — Admin Quick Edit fields for Posts and Pages
5. Cross-plugin interactions (what calls what)
6. Known technical debt items
7. Deployment and update notes
```

### Key items to document

- **Clickable cards JS** — lives in `tiaa-elementor-forms-invite-action.php`, runs site-wide in `wp_footer`, makes `.e-loop-item` cards fully clickable via transparent anchor overlay. As of v0.0.6, applies to all pages with Loop Grids (homepage, Hot Topics archive, Related Organizations).
- **Yoast SEO / Elementor content gap workaround** — Elementor stores content as serialized JSON in postmeta; Yoast reads `post_content`. Workaround: populate the Excerpt field with a 200-word prose summary. This is a manual maintenance step. The `tiaa-quick-edit` plugin surfaces the Excerpt field in Quick Edit for both Posts (target categories) and Pages (all pages) to make this easier.
- **WP-Cron in Docker** — `cURL error 7` resolved by `ALTERNATE_WP_CRON` in `wp-config.php`. Must be verified after any Docker config change.
- **Discourse API case sensitivity** — Group names passed to the Discourse API must match exactly.

---

## Pinned Decision B — WordPress Tags for Related Organizations Cards

### What needs to be done

Add a single WordPress Tag to each Related Organizations post to serve as a one-line descriptor displayed on the Loop Item card. Implement the Post Terms (Tags) dynamic tag in the Related Organizations Loop Item template to display this tag as a category label on each card.

### Why this matters

Organization cards currently show only a logo, title, and truncated excerpt. A brief descriptor tag (e.g. "Annual Workshop", "Online Meetings") lets visitors scan the grid and identify relevant organizations without reading the full excerpt.

### Current tag assignments

| Organization | Tag |
|---|---|
| OIAA — Online Intergroup of Alcoholics Anonymous | Online Meetings |
| Code for Recovery (C4R) | Open-Source Tools |
| NAAAW — National A.A. Archives Workshop | Annual Workshop |
| NAATW — National A.A. Technology Workshop | Annual Workshop |

### Implementation steps

1. Add the tag to each Related Organizations post in WP Admin → Posts → Tags field
2. Open the Related Organizations Loop Item template in Elementor Theme Builder
3. Add a **Text** widget above or below the post title
4. Set the dynamic tag to **Post Terms → Tags**
5. Style as a small teal pill or italic label text using site design tokens (Teal `#31bba6`)
6. Publish template

### Notes

- Tags do not affect the Loop Grid query (which filters by category `related-organizations`) — adding tags to these posts is safe and non-breaking
- The tag vocabulary may need to expand as more organizations are added — review when the org count exceeds 8
- NAATW and NAAAW intentionally share the "Annual Workshop" tag — this is correct and helps visitors group similar organizations visually
- C4R's tag "Open-Source Tools" accurately describes their work; alternatively "Meeting Technology" could be used if the audience skews less technical

---

*Add both items to the governance document (`tiaa-v3-design-governance-v2.md`) Pinned Decisions appendix.*
