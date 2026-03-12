TIAA Forum Site (tiaa-wpsite-v3) — Design Governance (MVP)<cwwcw
This page defines how design changes are made and approved for the site during MVP.

1) What controls the site’s look and feel

Primary (preferred):

Elementor → Site Settings: Global Colors, Global Fonts, Buttons, Layout
Elementor Theme Builder: Header, Footer, Templates
Saved Elementor templates used as “components” (Hero, Cards, CTA band, etc.)

Custom code (restricted):

CSS in Elementor → Site Settings → Custom CSS
JS in Elementor → Custom Code (single snippet only)
2) Source of truth policy
   A) Elementor content and templates

Elementor is the source of truth for:

Page layouts
Theme Builder templates
Saved component templates
B) Custom CSS and JS

GitHub is the source of truth for all custom code:

assets/css/tokens.css
assets/css/custom.css
assets/js/custom.js

WordPress only contains a synchronized copy of these files.

3) “Do not edit in WordPress” rule (custom code)

Do not edit CSS/JS directly in WordPress.
If a change is needed, the workflow is:

Update the file(s) in GitHub
Commit with a clear message
Copy/paste updated contents into WordPress (Custom CSS / Custom Code)
Update the “Last Synced” date in the header comment
Add an entry to the change log

Exception: emergency hotfixes are allowed, but must be ported back to GitHub within 24 hours.

4) Where custom code lives in WordPress

CSS: Elementor → Site Settings → Custom CSS

Contains: tokens.css + custom.css (in that order)
No other CSS paste locations allowed

JS: Elementor → Custom Code

One single snippet only
Location: End of Body
Entire site
No inline JS in widgets
5) Component consistency rule

Reusable UI patterns should be built as Elementor templates (“components”), not recreated ad-hoc.
Examples:

Component – Hero
Component – Card (Base)
Component – CTA band
Component – Feature grid
6) Approval and change control (MVP)

Changes that affect global design require approval:

Global Colors / Fonts / Buttons
Header/Footer templates
Any CSS/JS changes

Suggested approval method:

A short note in the Admin Team channel + one screenshot
If no objections within an agreed window, proceed
7) Backups / rollback
   Elementor templates/pages support revisions in many cases.
   Periodically export Elementor templates/site kit (monthly or before major changes).
   Store exports in:
   WordPress Media Library (for non-technical admins)
   and/or docs/exports/ in the repo (for technical archive)
8) Change log

All design-system changes must be recorded in the project change log:

What changed
Why it changed
Date
Who approved