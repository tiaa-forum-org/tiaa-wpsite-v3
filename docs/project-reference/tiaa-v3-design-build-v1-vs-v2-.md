# TIAA Forum v3 — Build Divergences Log

**Version:** 1.0  
**Date:** March 2026  
**Purpose:** Records where the actual v3 homepage build diverged from the original Design Governance (MVP) document. Intended as a handoff reference for new contributors and volunteer maintainers.

---

## 1. Custom Code Placement

### Original Governance Spec
All custom CSS to live in Elementor → Site Settings → Custom CSS (tokens.css + custom.css in order). All custom JS in Elementor → Custom Code (single snippet, end of body). GitHub is source of truth; WordPress holds a synchronized copy.

### What Was Actually Built
Custom code was moved into dedicated WordPress plugins rather than Elementor Custom CSS/JS:

- `tiaa-wpplugin` — contains the clickable cards JavaScript snippet (`wp_footer` hook, `is_front_page()` conditional)
- `Elementor Forms / TIAA Invite Form Action` plugin — intended future home for Elementor-specific JS/CSS
- No CSS currently lives in Elementor → Custom CSS beyond what Elementor itself generates

### Reason for Divergence
Plugin-based code is more maintainable for volunteers, is version-controlled in its own GitHub repo, and survives Elementor re-installs. Elementor Custom CSS/JS requires manual sync discipline that is error-prone in a volunteer environment.

### Impact
**Low.** Behavior is equivalent. Future maintainers need to know that custom JS lives in `tiaa-wpplugin`, not in Elementor Custom Code.

> **Note:** The clickable cards snippet may migrate to the Elementor Forms plugin post-MVP as it is more Elementor-specific.

---

## 2. No Pixel-Perfect Implementation from Replit Vibe-Design

### Original Governance Spec
New design vibe-coded in Replit at https://techtaalk.replit.app/ — design system colors, typography, and layout to be followed.

### What Was Actually Built
The Replit design was used as a directional reference and inspiration, not a pixel-perfect specification. Key adaptations:

- Card layouts were redesigned during build based on content requirements
- Section ordering was adjusted (stats moved into Section 4 alongside What You Can Do)
- Typography sizes adapted for readability at actual content lengths
- The hero section uses a light teal gradient rather than the Replit background image

### Reason for Divergence
The Replit design was vibe-coded without real content. As actual content was placed into the templates, proportions and layouts were adjusted to fit. This is expected and appropriate for an MVP build.

### Impact
**Low.** The design system (colors, fonts, spacing tokens) was faithfully followed. Visual output is consistent with the design intent.

---

## 3. Post/Card/Loop Pattern for Hot Topics and Categories

### Original Governance Spec
Hot Topics implemented as WordPress Posts with a Hot Topics category. Custom fields via ACF: Forum Thread URL and Featured toggle.

### What Was Actually Built
The loop/card pattern evolved significantly during the build:

- Hot Topics: WordPress Posts with Hot Topics category — as specced
- Categories: Separate post type using `discourse-categories` — added during build, not in original spec
- Two separate Loop Item card templates per content type: one for homepage, one for index pages
- ACF Forum Thread URL field implemented; Featured toggle deferred in favour of `menu_order` approach
- `menu_order` used for priority/sort order (supported by custom plugin for order management)
- Card click behaviour: whole-card clickable via JS overlay snippet (Elementor-native link approach not available in this version of Elementor Pro)

### Reason for Divergence
The categories post type was added during build as the site architecture became clearer. The Featured toggle was replaced by `menu_order` as it is simpler for volunteers to manage. The dual card template approach (homepage vs index) was necessary to support different display requirements in different contexts.

### Impact
**Medium.** Volunteers need to understand there are two card templates per content type and that `menu_order` controls display priority. ACF Featured field may still be added post-MVP.

---

## 4. Conditional Navigation Implementation

### Original Governance Spec
Not explicitly specified in the original governance document. `conditional-navigation.md` was added during the project as a separate reference document.

### What Was Actually Built
Conditional navigation implemented using Elementor Pro display conditions on individual button widgets in the Header template:

- Logged out: coral JOIN/SIGN IN button — links to `/join`
- Logged in: teal GO TO FORUM button + coral CONTRIBUTE button + text LOGOUT link
- Contribute button color will eventually change based on funding level (pinned for post-MVP — see Pinned Decision #1 in governance doc)
- All conditions use Elementor Pro built-in User Login Status conditions — no custom code required

### Reason for Divergence
Conditional navigation was not in scope in the original governance document. It was added as a requirement during the build based on the Discourse SSO architecture.

### Impact
**Low.** Well-implemented using Elementor-native features. Future header redesign (pinned) will need to preserve these conditions.

---

## 5. Homepage Stats Are Hardcoded

### Original Governance Spec
Not specified in original governance.

### What Was Actually Built
The Forum Statistics panel on the homepage (1,247 Active Members / 856 Discussions / 12.3k Posts / 15 Categories) contains hardcoded values directly in Elementor widgets.

### Reason for Divergence
A TIAA Settings admin page to manage these values dynamically was identified as the correct solution during the build, but was deferred to post-deployment to meet the Tuesday deadline.

### Impact
**Medium.** Volunteers cannot update stats without opening Elementor. This must be resolved before public launch. See Pinned Decision #3 in the Design Governance document.

---

## 6. Navigation Structure Changes

### Original Governance Spec
Navigation: Home, Hot Topics, The Forum, About, Contact Us. Conditional: Join (logged out), Go to Forum / Contribute / Logout (logged in).

### What Was Actually Built
- "The Forum" removed from nav — replaced by "What is a Forum?" button in the Two Ways section card
- "About" renamed to "About Us"
- "Resources" added as a nav item (stub page, dropdown post-MVP — see Pinned Decision #2)
- "Contribute" added as conditional logged-in button in header (not a nav menu item)
- "Hot Topics" retained in nav after SEO discussion (removing from nav does not harm SEO given sitemap and homepage loop links, but was kept for UX clarity)

### Impact
**Low.** Changes are intentional and documented. Menu items are managed in WordPress Admin → Appearance → Menus.

---

## 7. Component Library Status

### Original Governance Spec
Reusable UI patterns should be built as Elementor templates (components), not recreated ad-hoc.

### What Was Actually Built
The following Elementor templates were created:

| Template Name | Type | Used For |
|---|---|---|
| TIAA-forum Header | Theme Builder — Header | Site-wide header with conditional nav |
| TIAA-forum Footer | Theme Builder — Footer | Site-wide footer |
| Home Page Hot Topics card | Loop Item | Hot Topics cards on homepage grid |
| Category Card - Homepage | Loop Item | Category cards on homepage grid |

The following were built inline (not as reusable templates) and should be considered for componentization post-MVP:

- Two Ways section cards
- What You Can Do feature list items
- Forum Statistics card

> **Note:** Elementor template exports (JSON) should be committed to `docs/exports/elementor/` in the repo before each deployment.

---

*End of Divergences Log*
