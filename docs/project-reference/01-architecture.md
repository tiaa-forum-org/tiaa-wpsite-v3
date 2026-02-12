# System Architecture

## Layer Model

### Content Layer
Managed via WordPress.

Contains:
- Posts
- Hot Topics (likely to be renamed)
- Resources
- Navigation
- Membership link
- Contact form
- Contribution link (visible only to forum members)

Source of Truth: Production Database

---

### Presentation Layer
Managed via Elementor:

- Theme Builder Templates
- Section Templates
- Loop Templates
- Global Design Tokens

Source of Truth: Elementor + Export Snapshots

---

### Logic Layer
##### Interfaces with tiaa-wpplugin WP plugin

- API integrations with Discourse for new member signups
- Supports new member welcome messages
-  Source of Truth: Git Repository

##### WP SimplePay WP plugin
- Payment processing
- Interface with Stripe API
- Source of Truth: Vendored

---

### Infrastructure Layer
Hosting, CDN, caching, backups.

---

## Why Elementor + CMS Hybrid

Allows:
- Designer-friendly editing
- Structured data model
- Reusable components
- Fast iteration
