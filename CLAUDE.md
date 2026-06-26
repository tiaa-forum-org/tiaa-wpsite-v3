# tiaa-wpsite-v3 — Claude Code Context
# Last updated: 2026-06-25

## What This Is

A non-functional WP plugin used as a container for v3 design documents and
site assets. Not deployed as an active plugin in production — its value is
the `docs/` and `assets/` directories, which hold design references and
exportable assets for the tiaa-forum.org v3 redesign.

Part of the tiaa-v3 project. Umbrella WP environment context:
`docs/wp-env-context.md` (canonical, tracked here).

---

## File Structure

```
tiaa-wpsite-v3/
├── tiaa-wpsite-v3.php          ← minimal WP plugin header only (no active code)
├── docs/
│   ├── wp-env-context.md       ← umbrella WP environment context (canonical)
│   ├── guides/                 ← step-by-step how-to guides (01- through 10-)
│   ├── project-reference/      ← reference documentation
│   └── style-guide/            ← auto-listed document library (index.php) + dated style guide exports
└── assets/                     ← design assets (images, icons, etc.)
```

---

## Usage

- `docs/wp-env-context.md` — umbrella WP environment context (Docker setup, plugin list, code style, REST endpoints); update this when environment-level facts change
- `docs/style-guide/` — document library served as static files outside WP (symlinked at the web root). `index.php` auto-lists dated subdirectories. To add a new style guide: drop a `tiaa-style-guide-YYYY-MM-DD/` subdirectory containing `index.html` and commit. See `docs/style-guide/README.md` for the generation workflow.
- Reference `docs/` for design specs when implementing features in other repos
- Assets here may be the source for uploads into Discourse theme components
  or WP media library
- Do not add functional PHP code here — this is a design/docs container only