# Implementation Roadmap

This document outlines the build sequence for the site rebuild, from foundational setup through MVP launch.

---

## Phase 1: Foundation (Global Design System)

**Goal**: Establish the design system that everything else builds on.

**Duration**: 1-2 hours

### Tasks:

1. **Set up Global Colors** ✅ COMPLETED
    - Extract colors from Replit design
    - Configure in Elementor Site Settings
    - Document: `01-elementor-global-colors-setup.md`

2. **Set up Global Fonts** ✅ COMPLETED
    - Configure typography system (Inter font family)
    - Set up 7 font styles (4 main + 3 custom)
    - Document: `02-elementor-global-fonts-setup.md`

3. **Test Design System**
    - Create test page
    - Verify colors and fonts work correctly
    - Document: Included in setup guides

**Deliverable**: Functional design system ready for template building

**Dependencies**: None

**Blocker Risk**: Low - straightforward Elementor configuration

---

## Phase 2: Core Templates (Site Structure)

**Goal**: Build the templates that appear on every page.

**Duration**: 2-3 hours

### Tasks:

4. **Prepare Navigation**
    - Create WordPress menu: "Main Navigation"
    - Upload logo to Media Library
    - Document: `03-elementor-header-template.md` (Part 1)

5. **Build Header Template** ⚠️ IN PROGRESS
    - Create Theme Builder template
    - Add logo, navigation, conditional buttons
    - Implement sticky behavior
    - Configure display conditions
    - Document: `03-elementor-header-template.md`
    - Reference: `conditional-navigation.md`

6. **Build Footer Template**
    - Create Theme Builder template
    - Add footer navigation, copyright, contact info
    - Configure display conditions
    - Document: TBD - `04-elementor-footer-template.md`

7. **Create 404 Template**
    - Simple error page with helpful message
    - Link back to homepage and main sections
    - Document: Can be combined with footer guide

**Deliverable**: Functional site-wide templates (header, footer, 404)

**Dependencies**:
- WordPress menu created
- Logo uploaded
- Global design system completed

**Blocker Risk**: Medium
- Conditional visibility may require Elementor Pro setup
- SSO integration testing needed for "Go to Forum" button

---

## Phase 3: Content Pages (Static Pages)

**Goal**: Build essential static pages.

**Duration**: 3-4 hours

### Tasks:

8. **Create Default Page Template**
    - Theme Builder template for standard pages
    - Simple layout: header, content area, footer
    - Document: `05-default-page-template.md`

9. **Build About Page**
    - Create page in WordPress
    - Add content sections (who we are, mission, team, etc.)
    - Apply default template
    - Test responsiveness

10. **Build Contact Page**
    - Create page in WordPress
    - Add contact form (Contact Form 7 or similar plugin)
    - Display contact information
    - Apply default template

11. **Build Forum Explainer Page** (aka "The Forum")
    - Create page explaining what the forum is
    - Benefits of joining
    - How it works
    - CTA to join
    - Link appears in main navigation for all users

**Deliverable**: Functional About, Contact, and Forum Explainer pages

**Dependencies**:
- Default page template created
- Contact form plugin installed (if not already)

**Blocker Risk**: Low - standard WordPress pages

---

## Phase 4: Membership Flow (Critical Path)

**Goal**: Enable new users to join and pay.

**Duration**: 4-6 hours

### Tasks:

12. **Set Up Payment Processing**
    - Configure WP SimplePay plugin
    - Connect to Stripe account
    - Set up membership pricing
    - Test payment flow in test mode
    - Document: `06-payment-setup.md` (create)

13. **Build Join/Signup Page**
    - Create `/join` page
    - Add join form with payment integration
    - Style with Global Colors/Fonts
    - Add benefits/features section above form
    - Test submission flow
    - Document: `07-join-page-build.md` (create)

14. **Configure tiaa-wpplugin**
    - Verify Discourse API integration works
    - Test new member account creation in Discourse
    - Test welcome message delivery
    - Document: May need developer assistance

15. **Test End-to-End Signup Flow**
    - Submit test signup (Stripe test mode)
    - Verify WordPress account created
    - Verify Discourse account created
    - Verify welcome message sent
    - Verify user can log in via SSO
    - Document test results

**Deliverable**: Functional membership signup and payment flow

**Dependencies**:
- WP SimplePay configured
- Stripe account connected
- tiaa-wpplugin active and configured
- WP-Discourse plugin active

**Blocker Risk**: HIGH
- Payment integration can be complex
- API integrations may require debugging
- SSO flow must work correctly
- **Recommendation**: Tackle this early, allow time for troubleshooting

---

## Phase 5: Hot Topics (Content Foundation)

**Goal**: Enable creation and display of Hot Topics.

**Duration**: 3-4 hours

### Tasks:

16. **Configure Hot Topics Category**
    - Create "Hot Topics" category in WordPress
    - Set up category description
    - Configure permalink structure (if needed)

17. **Set Up Custom Fields** (Optional but recommended)
    - Install Advanced Custom Fields (ACF) plugin
    - Create field group for Hot Topics:
        - Forum Thread URL (link to original Discourse post)
        - Featured toggle (for homepage display)
    - Assign to "Hot Topics" category
    - Document: `08-hot-topics-acf-setup.md` (create)

18. **Build Single Hot Topic Template**
    - Theme Builder → Single Post template
    - Display title, date, content
    - Display forum thread link prominently
    - Add "Related Hot Topics" section (optional)
    - Style with design system
    - Document: `09-single-hot-topic-template.md` (create)

19. **Build Hot Topics Archive Template**
    - Theme Builder → Archive template
    - Display grid of Hot Topic cards using Loop Grid
    - Add search/filter (optional for MVP)
    - Add pagination
    - Style with design system
    - Document: `10-hot-topics-archive-template.md` (create)

20. **Create Sample Hot Topics**
    - Migrate 3-5 existing Hot Topics from old site
    - Preserve anchor links (e.g., #hotline, #webstart)
    - Test that links work
    - Test archive display

**Deliverable**: Functional Hot Topics system with sample content

**Dependencies**:
- Single and Archive templates created
- Category configured
- Sample content available

**Blocker Risk**: Medium
- Anchor link preservation requires careful setup
- Loop Grid configuration can be tricky
- Migration of existing content takes time

---

## Phase 6: Homepage (Public Face)

**Goal**: Build an engaging homepage that converts visitors to members.

**Duration**: 4-5 hours

### Tasks:

21. **Build Homepage Hero Section**
    - Create homepage in WordPress
    - Edit with Elementor (not using template)
    - Build hero section:
        - Large headline
        - Subheadline
        - Dual CTA buttons (Join + Explore Topics)
        - Background pattern/gradient from Replit design
    - Test responsiveness
    - Document: `11-homepage-hero.md` (create)

22. **Add Dual Option Cards**
    - Below hero: two side-by-side cards
    - Card 1: "Explore Hot Topics" (links to archive)
    - Card 2: "Join the Forum" (links to join page)
    - Style with design system

23. **Add Hot Topics Grid**
    - Use Elementor Loop Grid widget
    - Display 3-6 most recent Hot Topics
    - Link to archive for "See All"
    - Style cards with design system

24. **Add Category Grid** (Optional for MVP)
    - Grid of forum categories
    - Links to Discourse category pages
    - Can be manual entry for MVP

25. **Add Stats Panel** (Optional for MVP)
    - Display member count, topic count, etc.
    - Manual entry for MVP
    - CTA button to join
    - Style with teal/navy gradient background

26. **Polish and Test Homepage**
    - Test all links
    - Verify mobile responsive
    - Check loading speed
    - Optimize images

**Deliverable**: Functional, engaging homepage

**Dependencies**:
- Hot Topics exist and display correctly
- Join page exists
- Sample stats available

**Blocker Risk**: Medium
- Loop Grid setup can be complex
- Design/layout iteration may take time
- Replit design pattern/gradient may need recreation

---

## Phase 7: Member-Only Features

**Goal**: Build features only members can access.

**Duration**: 2-3 hours

### Tasks:

27. **Build Contribution Page**
    - Create `/contribute` page
    - Add contribution form or guidelines
    - Set Elementor visibility: User is Logged In
    - Test access control (logged-out users can't see)
    - Document: `12-contribution-page.md` (create)

28. **Implement Conditional Navigation**
    - Add "Contribute" link to header (member-only)
    - Add "Logout" link to header (member-only)
    - Test visibility toggling
    - Document: Already covered in `03-elementor-header-template.md`

29. **Test Member Experience**
    - Log in as test member
    - Verify "Go to Forum" button appears
    - Verify "Contribute" link visible
    - Click "Go to Forum" → verify SSO to Discourse works
    - Access `/contribute` → verify accessible
    - Log out → verify buttons change

**Deliverable**: Functional member-only features

**Dependencies**:
- Header template built
- Test member account exists
- WP-Discourse SSO working

**Blocker Risk**: Medium
- SSO testing requires working Discourse connection
- Visibility conditions must be configured correctly

---

## Phase 8: Testing & Refinement

**Goal**: Ensure everything works correctly before launch.

**Duration**: 3-5 hours

### Tasks:

30. **Cross-Browser Testing**
    - Test in: Chrome, Firefox, Safari, Edge
    - Verify layout consistency
    - Fix any browser-specific issues

31. **Mobile Testing**
    - Test on actual mobile devices (iOS, Android)
    - Verify touch interactions work
    - Check text readability
    - Test hamburger menu
    - Verify forms work on mobile

32. **User Flow Testing**
    - Test full visitor → member journey:
        1. Land on homepage
        2. Explore Hot Topics
        3. Click Join
        4. Complete payment
        5. Receive confirmation
        6. Log in
        7. Access forum via SSO
        8. Access contribution page
    - Document any issues

33. **Accessibility Check**
    - Test keyboard navigation
    - Verify color contrast (WCAG AA minimum)
    - Check alt text on images
    - Test with screen reader (basic check)
    - Document: `13-accessibility-checklist.md` (create)

34. **Performance Optimization**
    - Check page load speeds (aim for <3 seconds)
    - Optimize images (compress, correct sizes)
    - Minimize CSS/JS (Elementor has built-in tools)
    - Enable caching
    - Document: `14-performance-optimization.md` (create)

35. **Content Review**
    - Proofread all text
    - Verify all links work
    - Check contact form delivers emails
    - Ensure join form processes correctly
    - Test payment flow with real card (test mode)

**Deliverable**: Fully tested, polished site ready for staging review

**Dependencies**: All previous phases completed

**Blocker Risk**: Medium
- Issues discovered may require rework
- Performance optimization can uncover problems

---

## Phase 9: Documentation & Handoff

**Goal**: Prepare for volunteer maintainers.

**Duration**: 2-3 hours

### Tasks:

36. **Create Editor Guide**
    - Update `06-editor-guide.md` with specific instructions
    - How to add/edit Hot Topics
    - How to update menu items
    - How to change homepage content
    - What NOT to touch
    - Include screenshots

37. **Create Maintenance Runbook**
    - Update `07-operations-runbook.md`
    - Backup procedures
    - Update cycle
    - Emergency contacts
    - Rollback procedures

38. **Export Templates**
    - Export all Elementor templates (JSON)
    - Commit to Git repository
    - Document export process
    - Store in `/exports/elementor/`

39. **Create Video Tutorials** (Optional but helpful)
    - Screen recordings of common tasks:
        - Adding a Hot Topic
        - Updating header menu
        - Changing homepage hero text
    - Upload to private YouTube or similar

40. **Handoff Meeting**
    - Walk through key features with volunteer team
    - Demonstrate editing capabilities
    - Answer questions
    - Provide login credentials (secure method)

**Deliverable**: Complete documentation and trained maintainers

**Dependencies**: All features built and tested

**Blocker Risk**: Low

---

## Phase 10: Deployment to Production

**Goal**: Launch the new site.

**Duration**: 2-4 hours

### Tasks:

41. **Final Staging Review**
    - Stakeholder review of staging site
    - Make any last-minute changes
    - Get approval to proceed

42. **Production Deployment**
    - Follow deployment process in `02-environments-and-deployment.md`
    - Deploy code to production
    - Import Elementor templates
    - Verify all integrations work (Stripe, Discourse)
    - Test critical flows on production

43. **DNS/Domain Setup** (if needed)
    - Point domain to new site
    - Verify SSL certificate
    - Test all pages after DNS propagation

44. **Post-Launch Monitoring**
    - Monitor for errors (404s, form failures)
    - Check analytics tracking (if implemented)
    - Verify payment processing works
    - Monitor member signups

45. **Backup Immediately**
    - Full database backup
    - Full file backup
    - Store securely

**Deliverable**: Live, functional production site

**Dependencies**: All testing passed, stakeholder approval

**Blocker Risk**: Medium
- DNS changes can cause downtime
- Production environment differences may cause issues
- Payment processing must work flawlessly

---

## Timeline Estimates

**Total estimated time**: 35-50 hours

**Breakdown by phase**:
- Phase 1 (Foundation): 1-2 hours ✅
- Phase 2 (Templates): 2-3 hours ⚠️
- Phase 3 (Content Pages): 3-4 hours
- Phase 4 (Membership Flow): 4-6 hours ⚠️ HIGH PRIORITY
- Phase 5 (Hot Topics): 3-4 hours
- Phase 6 (Homepage): 4-5 hours
- Phase 7 (Member Features): 2-3 hours
- Phase 8 (Testing): 3-5 hours
- Phase 9 (Documentation): 2-3 hours
- Phase 10 (Deployment): 2-4 hours

**Critical Path** (must be completed in order):
1. Foundation (Phases 1-2)
2. Membership Flow (Phase 4) - HIGH PRIORITY
3. Content (Phases 3, 5)
4. Integration (Phases 6-7)
5. Testing (Phase 8)
6. Launch (Phases 9-10)

**Parallelization opportunities**:
- Content pages (Phase 3) can be built alongside Hot Topics setup (Phase 5)
- Documentation (Phase 9) can start earlier and be updated as you build

---

## Risk Mitigation

**High-Risk Items**:
1. **Payment processing integration** (Phase 4)
    - Mitigation: Start early, allocate extra time, have developer on standby
2. **Discourse SSO** (Phases 4, 7)
    - Mitigation: Test thoroughly in staging, have WP-Discourse docs handy
3. **Anchor link preservation** (Phase 5)
    - Mitigation: Document existing links, test each one individually
4. **Mobile responsiveness** (All phases)
    - Mitigation: Test on mobile throughout build, not just at end

**Medium-Risk Items**:
1. Loop Grid configuration (Phases 5, 6)
    - Mitigation: Follow Elementor Pro tutorials, test with sample data
2. Conditional visibility (Phases 2, 7)
    - Mitigation: Verify Elementor Pro features available, use simple logic
3. Performance optimization (Phase 8)
    - Mitigation: Optimize as you build, not all at end

---

## Success Criteria (MVP Launch)

**Must Have** (blocking launch):
- [ ] Header with conditional navigation works
- [ ] Footer displays correctly
- [ ] Join/signup page processes payments
- [ ] New members get Discourse accounts automatically
- [ ] SSO to forum works for logged-in members
- [ ] Hot Topics display correctly
- [ ] Homepage is engaging and functional
- [ ] Mobile responsive on all key pages
- [ ] All critical links work

**Should Have** (launch without if necessary):
- [ ] Contribution page functional
- [ ] Hot Topics archive with search/filter
- [ ] Stats panel on homepage
- [ ] Category grid
- [ ] 404 page styled

**Nice to Have** (post-launch):
- [ ] User profile pages
- [ ] Live stats from Discourse API
- [ ] Advanced Hot Topics filtering
- [ ] Newsletter signup
- [ ] Social share buttons

---

## Next Steps

**Immediate** (continue current work):
1. Complete Header Template (Phase 2, Task 5)
    - Finish conditional button implementation
    - Test visibility toggling
    - Document any issues

**After Header Complete**:
2. Build Footer Template (Phase 2, Task 6)
3. OR jump to Membership Flow (Phase 4) if payment setup urgent

**Decision point**:
Should we prioritize getting the payment flow working (Phase 4) before polishing all templates? This ensures critical revenue path is functional ASAP.

**Recommendation**:
- Finish header and footer (Phase 2)
- Jump to payment setup (Phase 4, Tasks 12-15)
- Return to content pages (Phase 3) while payment is in testing
- This keeps momentum and tackles high-risk item early
