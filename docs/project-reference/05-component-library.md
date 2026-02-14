# Component Library (Updated)

This document catalogs the reusable UI components that make up the site. All components use the Global Colors and Global Fonts defined in Elementor Site Settings.

---

## Global Components (Site-Wide)

### Header – Conditional Navigation
**Location**: Theme Builder → Header template  
**Displays on**: All pages

**Structure**:
- Logo (left) - links to homepage
- Main navigation menu (center) - static items for all users
- Conditional action buttons (right) - toggle based on login state

**Static Navigation Items** (visible to all):
- Home
- Hot Topics
- The Forum
- About
- Contact

**Conditional Elements**:

**For Logged-Out Visitors:**
- **"Join" button** (coral background, prominent CTA)
    - Links to: `/join` (signup/payment page)
    - Visibility: Show when User is Logged Out

**For Logged-In Members:**
- **"Go to Forum" button** (teal or coral background)
    - Links to: Discourse forum URL (SSO automatic)
    - Visibility: Show when User is Logged In
- **"Contribute" link** (text link or small button)
    - Links to: `/contribute` (member-only page)
    - Visibility: Show when User is Logged In
- **"Logout" link** (minimal text link)
    - Links to: WordPress logout URL with redirect
    - Visibility: Show when User is Logged In

**Design**:
- Background: Navy (#2b2e60)
- Text: White
- Hover: Teal (#31bba6)
- Sticky positioning (stays at top on scroll)
- Mobile: Hamburger menu with same conditional logic

**Editable**:
- Menu items (WordPress Menus admin)
- Button text and URLs (Elementor template editor)
- Colors (via Global Colors)

**Not Editable** (without developer):
- Conditional visibility logic
- Layout structure
- Responsive breakpoints

---

### Footer
**Location**: Theme Builder → Footer template  
**Displays on**: All pages

**Contains**:
- Site logo or name
- Footer navigation links (About, Contact, Privacy Policy, etc.)
- Social media links (if applicable)
- Copyright notice
- Optional: Newsletter signup

**Design**:
- Background: Light gray or navy (TBD based on final design)
- Text: Navy or white (contrast with background)
- Links: Teal on hover

**Editable**:
- Text content
- Links
- Social icons

---

## Homepage Components

### Hero – Pattern Background
**Location**: Homepage only  
**Purpose**: Main entry point, primary CTA

**Contains**:
- Large headline (Display Large typography, 60px)
- Subheadline (smaller, explanatory text)
- Primary CTA button ("Join the Forum" or "Explore Hot Topics")
- Secondary CTA button (alternative action)
- Background: Pattern or gradient (from Replit design)

**Design**:
- Full-width section
- Text: Navy on light background OR white on dark background
- Buttons: Teal (primary) and Coral (secondary)
- Minimum height: 500-600px

**Editable**:
- Headline text
- Subheadline text
- Button text and links
- Background image/pattern

---

### Dual Option Cards
**Location**: Homepage (below hero)  
**Purpose**: Present two main pathways (Hot Topics vs Forum)

**Contains**:
- Two side-by-side cards (50% width each on desktop, stack on mobile)
- Each card has:
    - Icon or image
    - Heading
    - Short description
    - CTA button or link

**Example**:
- **Card 1**: "Explore Hot Topics" → links to Hot Topics archive
- **Card 2**: "Join the Forum" → links to forum explainer or join page

**Design**:
- Cards: White background with subtle shadow
- Border: Light border or none
- Hover: Slight lift effect (shadow increase)

**Editable**:
- Card headings
- Card descriptions
- Images/icons
- Button text and links

---

### Hot Topic Cards (Featured List)
**Location**: Homepage, Hot Topics archive page  
**Purpose**: Display curated Hot Topics in grid layout

**Contains**:
- Grid of Hot Topic cards (3-4 columns on desktop, 1-2 on mobile)
- Each card has:
    - Category badge (optional, shows topic category)
    - Title
    - Excerpt/summary (1-2 sentences)
    - "Read More" link
    - Metadata (date, author - optional)

**Design**:
- Card background: White or very light gray
- Border: Subtle border or shadow
- Category badge: Teal or coral background
- Title: Navy, bold (H3 typography)
- Text: Navy, regular weight
- Hover: Border color changes to teal

**Data Source**:
- WordPress Posts in "Hot Topics" category
- Uses Elementor Loop Grid (Pro feature)
- Pulls: Title, excerpt, featured image, category

**Editable** (via WordPress):
- Post title, content, excerpt
- Category assignment
- Featured image

**Editable** (via Elementor):
- Card layout
- Number of columns
- Visible fields (show/hide date, author, etc.)

---

### Category Grid (Forum Categories)
**Location**: Homepage (optional), Forum Explainer page  
**Purpose**: Preview of forum discussion categories

**Contains**:
- Grid of category cards
- Each card:
    - Category icon
    - Category name
    - Brief description
    - Number of topics/posts (if pulling from Discourse API - future)
    - Link to that category in forum

**Design**:
- Similar to Hot Topic cards but simpler
- Icons: Custom icons or emoji for each category
- Background: Light color matching category theme (optional)

**Data Source**:
- Manual entry (for MVP)
- Future: Pull from Discourse API

**Editable**:
- Category names and descriptions
- Icons
- Links

---

### Stats Panel
**Location**: Homepage (typically near bottom)  
**Purpose**: Display forum metrics, build credibility

**Contains**:
- 3-4 stat counters:
    - Number of members
    - Number of topics
    - Number of posts
    - Years active (or similar)
- CTA button ("Join the Community")

**Design**:
- Full-width or contained section
- Background: Teal or navy gradient
- Text: White
- Large numbers (bold, prominent)
- Labels below numbers (smaller text)

**Data Source**:
- Manual entry (for MVP)
- Future: Pull from Discourse API via cron job

**Editable**:
- Stat numbers and labels
- CTA text and link

---

## Content Page Components

### Page Header
**Location**: All standard pages (About, Contact, etc.)  
**Purpose**: Introduce page topic

**Contains**:
- Page title (H1)
- Optional: Breadcrumb navigation
- Optional: Hero image or background

**Design**:
- Full-width or contained
- Title: Large, bold (Secondary/H1 typography)
- Background: Light gray or white

---

### Content Sections (Generic)
**Location**: Used on multiple pages  
**Purpose**: Reusable content blocks

**Types**:

**Text Section**:
- Heading (H2)
- Paragraph text
- Optional: Button or link
- Optional: Image alongside text

**Image + Text (Two Column)**:
- Image on one side, text on other
- Responsive: Stacks on mobile

**Call-to-Action Block**:
- Centered heading
- Short description
- Prominent button
- Background: Teal or coral (stands out from page)

**Quote/Testimonial Block**:
- Large quote text
- Attribution (name, role)
- Optional: Photo

---

## Hot Topics Specific

### Single Hot Topic Template
**Location**: Theme Builder → Single Post template  
**Used for**: Individual Hot Topic pages

**Contains**:
- Post title (large, bold)
- Metadata: Date published, category, author (optional)
- Featured image (optional)
- Post content (curated summary of forum discussion)
- **Forum thread link** (prominent button/link to original Discourse thread)
- Related Hot Topics (sidebar or bottom) - optional
- Social share buttons - optional

**Design**:
- Clean, readable layout
- Max content width: 800px (optimal reading)
- Generous line spacing
- Typography: Primary (body text)

**Preserves**:
- Anchor links (e.g., `#hotline`) for SEO

---

### Hot Topics Archive Template
**Location**: Theme Builder → Archive template  
**Used for**: Hot Topics category page, search results

**Contains**:
- Page title ("Hot Topics")
- Optional: Search/filter bar (search by keyword, filter by category)
- Grid or list of Hot Topic cards (using Loop Grid)
- Pagination (if >10 posts per page)

**Design**:
- Similar to homepage Hot Topics section but full page
- Grid: 3 columns desktop, 2 tablet, 1 mobile

---

## Form Components

### Join/Signup Form
**Location**: `/join` page  
**Purpose**: New member signup and payment

**Contains**:
- Heading: "Join the TIAA Forum"
- Description of membership benefits
- Form fields:
    - Name
    - Email
    - Optional: Additional profile fields
- **Payment section** (WP SimplePay integration):
    - Stripe payment form
    - Price display
    - Payment button
- Terms acceptance checkbox
- Submit button

**After submission**:
- Redirect to welcome page or forum
- Trigger: tiaa-wpplugin creates Discourse account
- Send welcome message

---

### Contribution Form
**Location**: `/contribute` page (member-only)  
**Purpose**: Members submit contributions (topics, resources, etc.)

**Contains**:
- Heading: "Contribute to the Forum"
- Description/guidelines
- Form fields (TBD based on contribution type):
    - Title
    - Description
    - Category selection
    - File upload (optional)
- Submit button

**Access control**:
- Page visible only to logged-in members
- Use Elementor visibility conditions

---

### Contact Form
**Location**: `/contact` page  
**Purpose**: General inquiries

**Contains**:
- Heading: "Contact Us"
- Form fields:
    - Name
    - Email
    - Subject
    - Message
- Submit button

**Sends to**: Admin email (configured in WordPress or form plugin)

---

## Mobile-Specific Considerations

All components must be responsive:

### Header (Mobile):
- Logo smaller (120-140px)
- Hamburger menu icon (replaces horizontal nav)
- Conditional buttons still apply (Join OR Go to Forum + Contribute + Logout)
- Menu slides in from side or drops down

### Cards/Grids (Mobile):
- 3-column → 1-column
- Increased tap target sizes (min 44x44px)
- Stacked layout

### Hero (Mobile):
- Smaller headline (30-36px)
- Reduced padding
- Buttons may stack vertically

---

## Component Naming Convention

For organization in Elementor:

**Format**: `[Location] - [Component Name]`

Examples:
- `Homepage - Hero Section`
- `Homepage - Hot Topics Grid`
- `Global - Footer`
- `Hot Topic - Single Post Layout`

---

## Design System Reference

All components use:

**Colors**:
- Primary (Teal): #31bba6
- Secondary (Coral): #f37758
- Text (Navy): #2b2e60
- Background: #FFFFFF
- (See Global Colors for full palette)

**Typography**:
- Body: Inter 400, 16px
- Headings: Inter 600-700, responsive sizes
- Buttons: Inter 500, 16px
- (See Global Fonts for full system)

**Spacing**:
- Section padding: 60-80px vertical, 5% horizontal (desktop)
- Card gap: 24-30px
- Element spacing: 16-24px between related items

**Border Radius**:
- Buttons: 8px
- Cards: 8-12px
- Input fields: 4-6px

---

## Notes for Editors

### Safe to Edit:
- Text content (headings, descriptions)
- Images
- Button labels and links
- Colors (via Global Colors)
- Spacing (via Elementor padding/margin settings)

### Do NOT Edit Without Developer:
- Template structure (widget order, container layout)
- Conditional visibility logic
- Responsive breakpoints
- Loop query settings
- Form integrations

---

## Component Implementation Priority

**Phase 1 - MVP:**
1. ✅ Header (with conditional navigation)
2. ✅ Footer
3. ✅ Homepage Hero
4. ✅ Hot Topics Grid (homepage)
5. ✅ Single Hot Topic template
6. ✅ Join form page

**Phase 2:**
7. Category Grid
8. Stats Panel
9. Contribution form page
10. Hot Topics Archive template with search/filter

**Phase 3 - Enhancements:**
11. Testimonial blocks
12. Member directory (if needed)
13. Advanced filtering
14. Discourse API integration for live stats
