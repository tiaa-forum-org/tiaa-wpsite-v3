# Conditional Navigation

## User States

### Anonymous Visitor (Not Logged In)
- Has NOT joined the forum
- Cannot log into WordPress (only members can authenticate)
- Authentication is via Discourse SSO

### Logged-In Member
- Has paid/joined the forum
- Authenticated via Discourse SSO (WP-Discourse plugin)
- Has access to member-only features
- Member status stored in WP-Discourse profile field

---

## Navigation Requirements by User State

### Anonymous Visitor Sees:

**Main Navigation:**
- Home
- Hot Topics
- The Forum (explainer page about the forum)
- About
- Contact

**Call to Action:**
- **"Join" button** (prominent, coral background)
    - Links to join/signup page with form
    - Allows new user to sign up and pay

**NOT visible:**
- Contribute link
- Logout link
- "Go to Forum" direct link

---

### Logged-In Member Sees:

**Main Navigation:**
- Home
- Hot Topics
- The Forum (may link to explainer OR direct to forum - TBD)
- About
- Contact

**Member-Only Items:**
- **"Contribute" button/link**
    - Links to contribution page/form
    - ONLY visible when logged in
- **"Logout" link**
    - Logs user out of Discourse SSO session
    - ONLY visible when logged in

**Call to Action:**
- **"Go to Forum" button** (replaces "Join" button)
    - Links directly to Discourse forum
    - SSO handled automatically by WP-Discourse plugin

**NOT visible:**
- "Join" button (replaced by "Go to Forum")

---

## Technical Implementation

### Authentication Method
- **SSO Provider**: Discourse (via WP-Discourse plugin)
- **WordPress Role**: Only members have WordPress accounts
- **Member Status Check**: WP-Discourse profile field
- **Session Management**: Handled by WP-Discourse plugin

### Elementor Implementation Approach

#### Recommended: Conditional Widget Visibility

Use Elementor Pro's built-in visibility conditions on individual widgets:

**For "Join" Button Widget:**
- Visibility Condition: Show when `User is Logged Out`
- OR: Show when `User Role is NOT Subscriber` (or whatever role members have)

**For "Go to Forum" Button Widget:**
- Visibility Condition: Show when `User is Logged In`
- Links to: Discourse forum URL (SSO handled automatically)

**For "Contribute" Link/Button:**
- Visibility Condition: Show when `User is Logged In`
- Links to: `/contribute` or contribution page URL

**For "Logout" Link:**
- Visibility Condition: Show when `User is Logged In`
- Links to: WordPress logout URL with redirect

---

## Page Access Control

### Join/Signup Page
- **URL**: `/join` or similar
- **Access**: Public (anyone can view)
- **Contains**: Signup form + payment processing (WP SimplePay + Stripe)
- **Redirect after signup**: To forum or welcome page

### Contribution Page
- **URL**: `/contribute` or similar
- **Access**: Members only (check required)
- **Contains**: Contribution form/information
- **If non-member accesses**: Redirect to join page or show "Members Only" message

**Implementation Options for Page Restriction:**
1. Use Elementor visibility conditions on page content sections
2. Use a membership plugin (if available)
3. Custom PHP in theme functions (check user login status, redirect if not logged in)
4. WP-Discourse may have built-in member checking functions

---

## Navigation Structure in Header

### Desktop Layout:
```
[Logo]  Home  Topics  Forum  About  Contact     [Contribute] [Go to Forum|Join] [Logout]
                                                 └─member only─┘ └─conditional─┘ └─member only─┘
```

### Mobile Layout (Hamburger Menu):
```
☰ Menu                                          [Go to Forum|Join]
                                                 └─conditional─┘

When opened:
- Home
- Hot Topics
- The Forum
- About
- Contact
- Contribute (if logged in)
- Logout (if logged in)
```

---

## WordPress Menu Setup

### Option A: Single Menu with Conditional Items (Recommended)

**Create one menu: "Main Navigation"**

**Static Items (always visible):**
- Home
- Hot Topics
- The Forum
- About
- Contact

**Conditional Items (added separately in Elementor, not in WordPress menu):**
- "Join" button → Elementor Button widget with logged-out visibility
- "Go to Forum" button → Elementor Button widget with logged-in visibility
- "Contribute" link → Elementor Button/Link widget with logged-in visibility
- "Logout" link → Elementor Link widget with logged-in visibility

**Why separate?**
- WordPress menus don't have built-in conditional logic
- Elementor widgets have easy visibility conditions
- Cleaner to manage in Theme Builder template

---

### Option B: Two Separate Menus (Alternative)

**Menu 1: "Public Navigation"**
- Home, Hot Topics, Forum (explainer), About, Contact

**Menu 2: "Member Navigation"**
- Home, Hot Topics, Forum (explainer or direct?), About, Contact, Contribute

**In Header Template:**
- Use two Nav Menu widgets
- Set visibility conditions on each widget
- Still need separate Join/Forum/Logout buttons

---

## URL Configuration

### Critical URLs to Define:

| Purpose | URL | Notes |
|---------|-----|-------|
| Join/Signup Page | `/join` or `/membership` | Public access, includes payment form |
| Discourse Forum | `https://forum.tiaa-forum.org` (or subdomain) | WP-Discourse handles SSO automatically |
| Contribution Page | `/contribute` | Member-only access |
| Logout | `wp_logout_url()` with redirect | WordPress function, redirects to homepage |
| Forum Explainer | `/the-forum` or `/about-forum` | Public page explaining what the forum is |

---

## Testing Checklist

### Test as Anonymous Visitor:
- [ ] "Join" button visible and links to signup page
- [ ] "Go to Forum" button NOT visible
- [ ] "Contribute" link NOT visible
- [ ] "Logout" link NOT visible
- [ ] Can access: Home, Hot Topics, Forum explainer, About, Contact
- [ ] Cannot access: Contribution page (redirected or shown message)

### Test as Logged-In Member:
- [ ] "Join" button NOT visible
- [ ] "Go to Forum" button visible and SSO works
- [ ] "Contribute" link visible and accessible
- [ ] "Logout" link visible and logs out properly
- [ ] Can access all public pages + contribution page

### Test Mobile:
- [ ] Hamburger menu shows correct items for user state
- [ ] Conditional buttons appear correctly
- [ ] Menu closes after clicking item

---

## Member Status Detection

### How to Check if User is Member (for developers):

The WP-Discourse plugin provides user profile fields. Check documentation for exact field name.

**Possible methods:**
```php
// Check if user is logged in
is_user_logged_in()

// Check user role (if members have specific role)
current_user_can('subscriber') // or whatever role members have

// WP-Discourse specific (check plugin docs for exact method)
// May have a function like: is_discourse_user()
```

For Elementor visibility conditions, use:
- **User State**: Logged In / Logged Out
- **User Role**: If members have a specific WordPress role

---

## Implementation Priority

**Phase 1 - MVP (Minimum Viable Product):**
1. ✅ "Join" button (logged-out users only)
2. ✅ "Go to Forum" button (logged-in users only, replaces Join)
3. ✅ Basic navigation (same for everyone)

**Phase 2 - Member Features:**
4. ✅ "Contribute" link (logged-in users only)
5. ✅ "Logout" link (logged-in users only)
6. ✅ Contribution page with access restriction

**Phase 3 - Polish:**
7. User avatar/name display in header
8. Dropdown user menu (Profile, Settings, Logout)
9. Welcome message for new members

---

## Notes for Maintainers

### Adding a new member-only page:
1. Create the page in WordPress
2. Add visibility condition to page sections: User is Logged In
3. Add link to navigation (with logged-in visibility)
4. Test as both logged-out and logged-in user

### Changing button text:
1. Go to Elementor → Theme Builder → Header → Edit
2. Find the button widget
3. Change text in Content tab
4. Update template

### Troubleshooting "Join" button shows for members:
- Check button widget visibility conditions
- Verify user is actually logged in (check WP admin bar)
- Clear Elementor cache and browser cache
- Check WP-Discourse plugin is active and configured

---

## Future Considerations

### Potential Enhancements:
- **User menu dropdown**: Avatar with Profile, Settings, Contribute, Logout
- **Member badge**: Visual indicator of member status
- **Welcome message**: "Welcome back, [Username]!" in header
- **Notification icon**: For forum notifications (if WP-Discourse supports)
- **Direct forum categories**: Links to specific forum sections for members

### Questions to Resolve Later:
- Should "The Forum" navigation item link to different pages for members vs. visitors?
- Do we want a user profile page in WordPress, or just redirect to Discourse profile?
- Should we show member count or stats in header?
- Consider adding a "Contribute" option in user menu for members
- Explore integrating member stats into header for engagement tracking
