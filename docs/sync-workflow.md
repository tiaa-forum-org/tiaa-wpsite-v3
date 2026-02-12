# TIAA-WPSITE-V3 — CSS / JS Sync Workflow (MVP)

This document defines how custom CSS and JavaScript are managed
between GitHub and WordPress (Elementor).

This workflow exists to:

- Prevent design drift
- Maintain a single source of truth
- Allow future rotation of admins without Git knowledge
- Keep changes controlled and auditable

---

# 1. Source of Truth Policy

## Elementor Owns
- Theme Builder templates (Header, Footer, etc.)
- Page layouts
- Saved component templates
- Global Colors / Fonts / Buttons / Layout

## GitHub Owns
- assets/css/tokens.css
- assets/css/custom.css
- assets/js/custom.js
- docs/change-log.md

WordPress contains a synchronized copy of CSS/JS.
GitHub is the canonical source.

---

# 2. Where Code Lives in WordPress

## CSS Location (single location only)

Elementor → Site Settings → Custom CSS

The contents must include:

1. tokens.css
2. custom.css

In that order.

No other CSS paste locations are allowed.

---

## JS Location (single location only)

Elementor → Custom Code

- One snippet only
- Location: End of Body
- Applies to: Entire Site
- Contains only the contents of assets/js/custom.js

No inline JS in widgets.
No additional JS snippets.

---

# 3. Standard Update Procedure

When a CSS or JS change is needed:

### Step 1 — Edit in GitHub
- Modify the appropriate file:
    - tokens.css
    - custom.css
    - custom.js
- Commit with a clear message.

Example:
