# tiaa-wpsite-v3 — Claude Code Context
# Last updated: 2026-05-17

## What This Is

A non-functional WP plugin used as a container for v3 design documents and
site assets. Not deployed as an active plugin in production — its value is
the `docs/` and `assets/` directories, which hold design references and
exportable assets for the tiaa-forum.org v3 redesign.

Part of the tiaa-v3 project. See umbrella context at `../CLAUDE.md`.

---

## File Structure

```
tiaa-wpsite-v3/
├── tiaa-wpsite-v3.php  ← minimal WP plugin header only (no active code)
├── docs/               ← design documents, specs, decision records
└── assets/             ← design assets (images, icons, etc.)
```

---

## Usage

- Reference `docs/` for design specs when implementing features in other repos
- Assets here may be the source for uploads into Discourse theme components
  or WP media library
- Do not add functional PHP code here — this is a design/docs container only