# TIAA Forum Rebuild - Quick Start Guide

**Last Updated**: Based on conditional navigation requirements  
**For**: Volunteer maintainers and developers  
**Purpose**: Step-by-step path from setup to launch

---

## Overview

This guide walks you through rebuilding tiaa-forum.org using Elementor Pro. Follow the guides in order - each one builds on the previous.

**Key Features of This Build**:
- ✅ Modern design from Replit prototype
- ✅ Conditional navigation (changes based on login state)
- ✅ Discourse SSO integration
- ✅ Member-only features (Contribution page)
- ✅ Payment processing (WP SimplePay + Stripe)
- ✅ Hot Topics as WordPress posts
- ✅ Volunteer-friendly editing

**Estimated Total Time**: 35-50 hours

---

## Before You Start

### Required Tools & Access:
- [ ] WordPress admin access
- [ ] Elementor Pro activated and licensed
- [ ] WP-Discourse plugin installed and configured
- [ ] WP SimplePay plugin installed
- [ ] Stripe account set up (test mode for now)
- [ ] Logo file ready (SVG or PNG)
- [ ] Sample content available (for testing)

### Required Knowledge:
- Basic WordPress administration
- Basic Elementor familiarity (helpful but not required)
- Understanding of your authentication flow (Discourse SSO)

### Reference Documents to Have Open:
1. `conditional-navigation.md` - Navigation requirements
2. `UPDATED-01-architecture.md` - System overview
3. `UPDATED-05-component-library.md` - Component catalog
4. `IMPLEMENTATION-ROADMAP.md` - Full build sequence

---

## Build Sequence (Follow in Order)

### 🟢 Phase 1: Foundation (1-2 hours)

**Goal**: Set up the design system

#### Step 1.1: Set Up Global Colors
**Guide**: `01-elementor-global-colors-setup.md`  
**Time**: 15-20 minutes  
**What you'll do**:
- Configure 10 global colors in Elementor
- Primary (Teal), Secondary (Coral), Text (Navy), etc.
- Test that colors appear in color picker

**Result**: Design palette ready to use

---

#### Step 1.2: Set Up Global Fonts
**Guide**: `02-elementor-global-fonts-setup.md`  
**Time**: 15-20 minutes  
**What you'll do**:
- Configure 7 font styles using Inter font family
- Body text, headings (H1, H2, H3), button text, etc.
- Set responsive sizes for mobile

**Result**: Typography system ready to use

---

#### Step 1.3: Test Design System
**No separate guide - covered in above**  
**Time**: 5-10 minutes  
**What you'll do**:
- Create a test page
- Add heading and button widgets
- Verify Global Colors and Fonts appear in dropdowns
- Test that selections apply correctly

**Result**: Confirmed design system is functional

---

### 🟡 Phase 2: Core Templates (2-3 hours)

**Goal**: Build site-wide templates (header, footer, 404)

#### Step 2.1: Prepare for Header Build
**Guide**: `03-elementor-header-template.md` (Part 1)  
**Time**: 10-15 minutes  
**What you'll do**:
- Create WordPress menu: "Main Navigation"
- Add menu items: Home, Hot Topics, The Forum, About, Contact
- Upload logo to Media Library

**Result**: Assets ready for header template

---

#### Step 2.2: Build Header Template (CRITICAL)
**Guide**: `03-elementor-header-template.md` (Parts 2-3)  
**Time**: 45-60 minutes  
**What you'll do**:
- Create Theme Builder Header template
- Add logo, navigation menu, conditional buttons
- Set up 4 conditional buttons:
    1. **"Join"** (logged-out users only)
    2. **"Go to Forum"** (logged-in users only, replaces Join)
    3. **"Contribute"** (logged-in users only)
    4. **"Logout"** (logged-in users only)
- Configure visibility conditions for each
- Make header sticky
- Set display conditions (Entire Site)
- Test both user states (logged in vs. logged out)

**Result**: Functional header with conditional navigation

**⚠️ CRITICAL**: This is the most complex template. Read `conditional-navigation.md` first to understand the requirements.

---

#### Step 2.3: Build Footer Template
**Guide**: `04-elementor-footer-template.md` (TO BE CREATED)  
**Time**: 30-45 minutes  
**What you'll do**:
- Create Theme Builder Footer template
- Add footer navigation, copyright, contact info
- Style with Global Colors/Fonts
- Set display conditions (Entire Site)

**Result**: Functional footer

**Note**: If this guide doesn't exist yet, follow the header guide pattern but simplified (no conditional visibility needed).

---

#### Step 2.4: Build 404 Template
**Guide**: Can follow footer guide pattern  
**Time**: 15-20 minutes  
**What you'll do**:
- Create Theme Builder 404 template
- Add helpful error message
- Add search box or navigation links
- Style with design system

**Result**: Custom 404 page

---

### 🔴 Phase 3: Membership Flow (4-6 hours) **HIGH PRIORITY**

**Goal**: Enable new members to join and pay

**⚠️ CRITICAL PATH**: This must work for site to function. Tackle early!

#### Step 3.1: Configure Payment Processing
**Guide**: `06-payment-setup.md` (TO BE CREATED)  
**Time**: 1-2 hours  
**What you'll do**:
- Configure WP SimplePay plugin
- Connect to Stripe (test mode)
- Set up membership pricing
- Create payment form
- Test payment flow

**Result**: Payment system ready

**Note**: This may require developer assistance if not experienced with Stripe.

---

#### Step 3.2: Build Join/Signup Page
**Guide**: `07-join-page-build.md` (TO BE CREATED)  
**Time**: 1-2 hours  
**What you'll do**:
- Create `/join` page in WordPress
- Add benefits/features section
- Embed payment form
- Style with Global Colors/Fonts
- Test form submission

**Result**: Functional join page

---

#### Step 3.3: Configure Discourse Integration
**Guide**: Developer documentation + plugin docs  
**Time**: 1-2 hours  
**What you'll do**:
- Verify tiaa-wpplugin is active
- Configure Discourse API connection
- Test new member account creation
- Verify welcome message sends
- Test SSO login flow

**Result**: WordPress → Discourse integration working

**⚠️ BLOCKER RISK**: API issues may require debugging

---

#### Step 3.4: Test End-to-End Signup
**Guide**: `conditional-navigation.md` (testing section)  
**Time**: 30-60 minutes  
**What you'll do**:
- Complete test signup (Stripe test mode)
- Verify all steps work:
    1. Payment processes
    2. WordPress account created
    3. Discourse account created
    4. Welcome message sent
    5. Can log in via SSO
    6. Header shows correct buttons (Go to Forum, Contribute, Logout)
    7. Can access forum directly
    8. Can access Contribution page

**Result**: Confirmed full member journey works

---

### 🟢 Phase 4: Content Pages (3-4 hours)

**Goal**: Build static pages (About, Contact, Forum Explainer)

#### Step 4.1: Create Default Page Template
**Guide**: `05-default-page-template.md` (TO BE CREATED)  
**Time**: 30-45 minutes  
**What you'll do**:
- Create Theme Builder Page template
- Simple layout: header, content area, footer
- No special features needed

**Result**: Template for standard pages

---

#### Step 4.2: Build About Page
**Guide**: Use default template  
**Time**: 45-60 minutes  
**What you'll do**:
- Create `/about` page
- Add content sections (mission, team, history)
- Style with Global Colors/Fonts
- Test responsiveness

---

#### Step 4.3: Build Contact Page
**Guide**: Use default template  
**Time**: 30-45 minutes  
**What you'll do**:
- Create `/contact` page
- Add contact form (Contact Form 7 or similar)
- Add contact information
- Test form submission

---

#### Step 4.4: Build Forum Explainer Page
**Guide**: Use default template  
**Time**: 45-60 minutes  
**What you'll do**:
- Create `/the-forum` page
- Explain what the forum is
- List benefits of joining
- Add "Join" CTA button
- This page linked from main navigation for ALL users

**Result**: Public-facing pages complete

---

### 🟢 Phase 5: Hot Topics (3-4 hours)

**Goal**: Set up Hot Topics content type and display

#### Step 5.1: Configure Hot Topics
**Guide**: `08-hot-topics-setup.md` (TO BE CREATED)  
**Time**: 30 minutes  
**What you'll do**:
- Create "Hot Topics" category
- Set up custom fields (ACF):
    - Forum Thread URL
    - Featured toggle
- Configure permalink structure

---

#### Step 5.2: Build Single Hot Topic Template
**Guide**: `09-single-hot-topic-template.md` (TO BE CREATED)  
**Time**: 1-1.5 hours  
**What you'll do**:
- Create Theme Builder Single Post template
- Display title, date, content
- Add prominent forum thread link
- Preserve anchor links (#hotline, etc.)
- Style with design system

---

#### Step 5.3: Build Hot Topics Archive
**Guide**: `10-hot-topics-archive-template.md` (TO BE CREATED)  
**Time**: 1-1.5 hours  
**What you'll do**:
- Create Theme Builder Archive template
- Use Loop Grid to display Hot Topic cards
- Add search/filter (optional for MVP)
- Add pagination
- Style with design system

---

#### Step 5.4: Create Sample Hot Topics
**Guide**: WordPress post editor  
**Time**: 30-60 minutes  
**What you'll do**:
- Migrate 3-5 existing Hot Topics
- Test anchor links work
- Verify archive displays correctly
- Test forum thread links

**Result**: Functional Hot Topics system

---

### 🟡 Phase 6: Homepage (4-5 hours)

**Goal**: Build engaging homepage

#### Step 6.1: Build Homepage Hero
**Guide**: `11-homepage-build.md` (TO BE CREATED)  
**Time**: 1-1.5 hours  
**What you'll do**:
- Create homepage (edit with Elementor)
- Build hero section:
    - Large headline (Display Large typography)
    - Subheadline
    - Dual CTAs (Join + Explore)
    - Background pattern/gradient from Replit design

---

#### Step 6.2: Add Dual Option Cards
**Time**: 30-45 minutes  
**What you'll do**:
- Two side-by-side cards below hero
- Card 1: "Explore Hot Topics" → archive
- Card 2: "Join the Forum" → join page

---

#### Step 6.3: Add Hot Topics Grid
**Time**: 45-60 minutes  
**What you'll do**:
- Use Loop Grid widget
- Display 3-6 most recent Hot Topics
- Link to "See All"

---

#### Step 6.4: Add Stats Panel (Optional)
**Time**: 30 minutes  
**What you'll do**:
- Display member count, topic count, etc.
- Manual entry for MVP
- CTA to join

---

#### Step 6.5: Polish Homepage
**Time**: 30-60 minutes  
**What you'll do**:
- Test all links
- Verify mobile responsive
- Optimize images
- Check loading speed

**Result**: Functional, engaging homepage

---

### 🟡 Phase 7: Member-Only Features (2-3 hours)

**Goal**: Build member-only pages

#### Step 7.1: Build Contribution Page
**Guide**: `12-contribution-page.md` (TO BE CREATED)  
**Time**: 1-1.5 hours  
**What you'll do**:
- Create `/contribute` page
- Add contribution form or guidelines
- **Set Elementor visibility**: User is Logged In
- Test access control (logged-out can't see)

**Result**: Member-only contribution page

---

#### Step 7.2: Test Member Experience
**Guide**: `conditional-navigation.md` (testing section)  
**Time**: 30-60 minutes  
**What you'll do**:
- Test as logged-in member
- Verify all member-only features work:
    - Header shows: Go to Forum, Contribute, Logout
    - Can access /contribute
    - SSO to forum works
    - Can log out

**Result**: Confirmed member experience works

---

### 🔴 Phase 8: Testing & Refinement (3-5 hours)

**Goal**: Ensure everything works before launch

#### Step 8.1: Cross-Browser Testing
**Time**: 1 hour  
**What you'll do**:
- Test in Chrome, Firefox, Safari, Edge
- Fix browser-specific issues

---

#### Step 8.2: Mobile Testing
**Time**: 1-2 hours  
**What you'll do**:
- Test on actual iOS and Android devices
- Verify touch interactions
- Check hamburger menu
- Test forms on mobile

---

#### Step 8.3: User Flow Testing
**Time**: 1-2 hours  
**What you'll do**:
- Test complete visitor → member journey
- Document any issues
- Fix blockers

---

#### Step 8.4: Accessibility & Performance
**Time**: 1 hour  
**What you'll do**:
- Check keyboard navigation
- Verify color contrast
- Optimize images
- Enable caching
- Check page load speeds (<3 seconds)

**Result**: Polished, tested site

---

### 🟢 Phase 9: Documentation (2-3 hours)

**Goal**: Prepare for handoff to volunteers

#### Step 9.1: Update Editor Guide
**Time**: 1-1.5 hours  
**What you'll do**:
- Document how to add/edit Hot Topics
- How to update menus
- How to change homepage
- What NOT to touch
- Include screenshots

---

#### Step 9.2: Export Templates
**Time**: 30 minutes  
**What you'll do**:
- Export all Elementor templates (JSON)
- Commit to Git
- Store in `/exports/elementor/`

---

#### Step 9.3: Create Video Tutorials (Optional)
**Time**: 1 hour  
**What you'll do**:
- Record common tasks
- Upload to private location

---

#### Step 9.4: Handoff Meeting
**Time**: 30-60 minutes  
**What you'll do**:
- Walk through features with team
- Demonstrate editing
- Answer questions

**Result**: Volunteers ready to maintain site

---

### 🔴 Phase 10: Launch (2-4 hours)

**Goal**: Deploy to production

#### Step 10.1: Final Staging Review
**Time**: 30-60 minutes  
**What you'll do**:
- Stakeholder review
- Last-minute fixes
- Get approval

---

#### Step 10.2: Production Deployment
**Time**: 1-2 hours  
**What you'll do**:
- Deploy code to production
- Import templates
- Verify integrations work
- Test critical flows

---

#### Step 10.3: Post-Launch Monitoring
**Time**: Ongoing  
**What you'll do**:
- Monitor for errors
- Check payment processing
- Verify member signups work
- Fix any issues immediately

---

#### Step 10.4: Backup
**Time**: 15 minutes  
**What you'll do**:
- Full database backup
- Full file backup
- Store securely

**Result**: Live, functional production site! 🎉

---

## Progress Tracking

Use this checklist to track your progress:

### Foundation
- [x] Global Colors set up
- [x] Global Fonts set up
- [x] Design system tested

### Templates
- [ ] Header template built
- [ ] Footer template built
- [ ] 404 template built

### Membership Flow
- [ ] Payment processing configured
- [ ] Join page built
- [ ] Discourse integration working
- [ ] End-to-end signup tested

### Content
- [ ] Default page template created
- [ ] About page built
- [ ] Contact page built
- [ ] Forum Explainer built
- [ ] Hot Topics configured
- [ ] Single Hot Topic template built
- [ ] Hot Topics Archive template built
- [ ] Sample Hot Topics created

### Homepage
- [ ] Hero section built
- [ ] Dual option cards added
- [ ] Hot Topics grid added
- [ ] Stats panel added (optional)
- [ ] Homepage polished

### Member Features
- [ ] Contribution page built
- [ ] Member experience tested

### Testing
- [ ] Cross-browser tested
- [ ] Mobile tested
- [ ] User flow tested
- [ ] Accessibility checked
- [ ] Performance optimized

### Documentation & Launch
- [ ] Editor guide updated
- [ ] Templates exported
- [ ] Videos created (optional)
- [ ] Team trained
- [ ] Staging approved
- [ ] Production deployed
- [ ] Post-launch monitoring active
- [ ] Backups complete

---

## Getting Help

**If you get stuck:**

1. **Check the specific guide** for that step (listed above)
2. **Consult reference docs**:
    - `conditional-navigation.md` for auth questions
    - `UPDATED-01-architecture.md` for system overview
    - `UPDATED-05-component-library.md` for component specs
3. **Review Elementor Pro documentation** at docs.elementor.com
4. **Check WP-Discourse plugin docs** for SSO issues
5. **Start a new chat** with updated context if you need guidance

**Common Issues:**
- Conditional visibility not working → Verify Elementor Pro activated
- SSO not working → Check WP-Discourse configuration
- Payment not processing → Verify Stripe test mode settings
- Templates not applying → Check display conditions

---

## Key Success Factors

✅ **Follow the order** - each phase builds on previous  
✅ **Test as you go** - don't wait until end  
✅ **Use Global Colors/Fonts** - ensures consistency  
✅ **Read guides before starting** - saves time  
✅ **Document issues** - helps with troubleshooting  
✅ **Test both user states** - logged in AND logged out  
✅ **Get feedback early** - show stakeholders at key milestones

---

## Estimated Timeline

**Full-time work** (8 hours/day): 4-6 days  
**Part-time work** (4 hours/day): 8-12 days  
**Spare time** (1 hour/day): 5-7 weeks

**Critical path** (must be done first):
1. Foundation (Phases 1-2): 3-5 hours
2. Membership Flow (Phase 3): 4-6 hours
3. Everything else can be more flexible

---

## Ready to Start?

**Current Status**: Foundation complete (Phase 1) ✅

**Next Step**: Phase 2, Step 2.1 - Prepare for Header Build

**Guide to Open**: `03-elementor-header-template.md`

**Time Needed**: ~3 hours for all of Phase 2

Good luck! 🚀
