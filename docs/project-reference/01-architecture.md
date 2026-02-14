# System Architecture (Updated)

## Layer Model

### Content Layer
Managed via WordPress.

Contains:
- **Posts** (used for Hot Topics - curated discussion summaries)
- **Pages**:
    - About, Contact
    - Forum Explainer (public, explains what the forum is)
    - Join/Membership page (public, includes payment form)
    - Contribution page (member-only access)
- **Resources** (future Custom Post Type)
- **Navigation menus**
- **Forms**: Contact form, Join form, Contribution form

**Authentication**: Discourse SSO via WP-Discourse plugin
**User States**:
- Anonymous visitors (not logged in)
- Logged-in members (authenticated via Discourse SSO)

Source of Truth: Production Database

---

### Presentation Layer
Managed via Elementor Pro:

- **Theme Builder Templates**:
    - Header (with conditional navigation)
    - Footer
    - Single Post (for Hot Topics)
    - Archive/Category (for Hot Topics listing)
    - Default Page
    - 404
- **Section Templates** (reusable page sections)
- **Loop Templates** (for repeating content like Hot Topic cards)
- **Global Design Tokens** (Colors, Typography)
- **Conditional Visibility** (member-only content, login-state UI changes)

**Navigation Requirements**:
- Static items: Home, Hot Topics, The Forum, About, Contact (visible to all)
- Conditional items toggle based on login state:
    - **Logged out**: "Join" button
    - **Logged in**: "Go to Forum" button, "Contribute" link, "Logout" link
- See `conditional-navigation.md` for full specification

Source of Truth: Elementor + Export Snapshots (committed to Git)

---

### Logic Layer

#### WP-Discourse Plugin
- **Discourse SSO integration** (Discourse is the SSO provider for the site)
- Member authentication and status management
- Profile fields store member status
- Handles automatic forum login for WordPress members
- Source of Truth: Plugin configuration in WordPress database

#### tiaa-wpplugin (Custom Plugin)
- API integrations with Discourse for new member signups
- Supports new member welcome messages
- Custom functionality for TIAA-specific workflows
- Source of Truth: Git Repository (`tiaa-wpsite-v3`)

#### WP SimplePay Plugin
- Payment processing for memberships/donations
- Interface with Stripe API
- Handles subscription management
- Source of Truth: Vendored plugin + Stripe configuration

---

### Infrastructure Layer
- **Hosting**: DigitalOcean VPS with Docker Compose stack
- **Development**: Local macOS with Docker Compose (IntelliJ IDEA + Docker plugin)
- **Test/Staging**: DigitalOcean VPS (same stack as production)
- **CDN**: (TBD - not currently specified)
- **Backups**: Frequent automated backups (minimum monthly, increase as needed)
- **Caching**: WordPress caching plugins (TBD specific solution)

---

## User Flow: Anonymous to Member

1. **Anonymous visitor** arrives at site
2. Sees "Join" button in header
3. Clicks "Join" → goes to `/join` page
4. Fills out join form and pays (WP SimplePay + Stripe)
5. Account created in WordPress
6. tiaa-wpplugin triggers Discourse API to create forum account
7. Welcome message sent via Discourse
8. User can now log in (Discourse SSO)
9. After login, sees "Go to Forum", "Contribute", and "Logout" in header
10. Can access member-only pages (e.g., `/contribute`)

---

## Authentication Flow (Discourse SSO)

**Key Point**: Discourse is the SSO provider. WordPress defers to Discourse for authentication.

1. User clicks "Login" or tries to access member content
2. WordPress redirects to Discourse for authentication
3. User logs in at Discourse
4. Discourse redirects back to WordPress with authentication token
5. WP-Discourse plugin validates token and logs user into WordPress
6. User session maintained across both WordPress and Discourse
7. "Go to Forum" button uses SSO to seamlessly enter Discourse (no second login)

**Logout**: Logging out of WordPress also logs out of Discourse (handled by WP-Discourse)

---

## Content Model: Hot Topics

**What are Hot Topics?**
Hot Topics are **curated summaries** of popular forum discussions, NOT live forum content.

**Structure**:
- Use WordPress **Posts** (not Custom Post Type)
- Category: "Hot Topics" (for filtering/archive)
- Custom fields (via ACF or meta boxes):
    - Forum thread URL (link to original Discourse discussion)
    - Featured toggle (for homepage display)
    - Summary/excerpt (manual curation)
- Anchor links preserved (e.g., `#hotline`, `#webstart`, `#voting`) for existing SEO

**Why Posts not CPT?**
- Better SEO (WordPress optimizes posts for search)
- Built-in archive/category pages
- Easier for volunteers to manage
- Standard WordPress workflow

---

## Why Elementor Pro + WordPress CMS Hybrid

**Advantages**:
- **Designer-friendly editing**: Volunteers with basic WordPress knowledge can update
- **Structured data model**: Separation of content from design
- **Reusable components**: Build once, use everywhere
- **Fast iteration**: No coding required for most changes
- **Conditional display**: Show/hide content based on user state
- **Theme Builder**: Site-wide templates (header, footer) managed visually
- **Global design system**: Colors and typography defined once, applied consistently

**Limitations**:
- Requires Elementor Pro license (paid)
- Some advanced features require custom code
- Template exports needed for version control
- Volunteer maintainers need basic Elementor training

---

## Version Control and Deployment

**What goes in Git:**
- Custom plugin code (`tiaa-wpplugin`)
- Elementor template exports (JSON files)
- Documentation (this file and others)
- Configuration files (Docker Compose, etc.)
- Assets (images, if not in WordPress Media Library)

**What stays in WordPress database:**
- Post/page content (text, images)
- User accounts and member data
- WP-Discourse configuration
- Plugin settings (WP SimplePay, etc.)
- Media Library uploads

**Deployment flow**: See `02-environments-and-deployment.md`

---

## Key Architectural Decisions

### Decision 1: Discourse as SSO Provider
**Why**: Forum is the "source of truth" for member identity. Keeps user management centralized.
**Trade-off**: WordPress authentication depends on Discourse being available.

### Decision 2: Posts for Hot Topics (not CPT)
**Why**: SEO benefits, simpler for volunteers, built-in WordPress features.
**Trade-off**: Less flexibility than CPT, but sufficient for this use case.

### Decision 3: Elementor Pro for All Templates
**Why**: Visual editing, maintainability by non-developers.
**Trade-off**: Vendor lock-in, requires paid license, some features need custom code.

### Decision 4: Conditional Navigation in Header Template
**Why**: Single header template with dynamic content (cleaner than multiple templates).
**Implementation**: Elementor Pro visibility conditions on individual widgets.
**Trade-off**: Slightly more complex setup, but easier long-term maintenance.

---

## Security Considerations

See `09-security-and-governance.md` for full details.

**Key points**:
- Minimal plugins (only essential ones)
- Regular updates (plugins weekly, core monthly)
- Role-based access (Editors content-only, Admins full access)
- Member-only pages use Elementor visibility conditions + WordPress login checks
- Contribution page requires member authentication
- Payment processing via Stripe (PCI compliance handled externally)

---

## Future Expansion Opportunities

**Potential enhancements** (not in MVP):
- Resources Custom Post Type (curated links, downloads)
- User profile pages in WordPress
- Member directory
- Forum stats widget (pull from Discourse API)
- Auto-sync Hot Topics from Discourse (via API)
- Notification system (forum activity)
- Member badges/achievements
- Advanced search across Hot Topics

See `08-integrations-future.md` for details.
