# Step-by-Step: Building the Header Template

## Why We're Doing This

The Header is a **Theme Builder Template** - it appears on every page of your site automatically. This is different from building individual pages.

**Benefits:**
- Edit header once, updates everywhere
- Ensures consistent navigation across the site
- Volunteer maintainers can update menu links without touching layouts

**IMPORTANT**: This header uses **conditional navigation** - different buttons show based on whether the user is logged in or not. This is a critical feature for member-only functionality.

From your Replit design and requirements, the header should be:
- Clean and minimal
- Navy background with white text
- Logo on left, navigation in center/right
- **Conditional action buttons** on far right (toggle based on login state)
- Sticky (stays visible when scrolling)
- Responsive (hamburger menu on mobile)

**See `01a-conditional-navigation.md` for full specification of user states and navigation requirements.**

---

## Prerequisites

- [ ] Global Colors set up
- [ ] Global Fonts set up
- [ ] WordPress menu created (we'll create this first)
- [ ] Logo image uploaded to Media Library
- [ ] **Elementor Pro activated** (required for conditional visibility features)

**Note**: This guide includes setting up conditional navigation buttons. If Elementor Pro's conditional visibility feature is not available in your version, you can still build all the buttons - they'll just all show at once until you configure visibility settings later.

---

## Part 1: Prepare Your Navigation Menu

Before building the header template, you need a WordPress menu.

### Step 1: Create a WordPress Menu

1. **Go to Appearance → Menus** in WordPress admin

2. **Create a new menu:**
   - Click "create a new menu" link
   - Name it: **"Main Navigation"** or **"Primary Menu"**
   - Check the box: **"Header Menu"** (if available) or **"Primary"**
   - Click **"Create Menu"**

3. **Add menu items:**

   For now, add these standard items (you can refine later):
   - **Home** (link to homepage)
   - **Hot Topics** (link to Hot Topics archive or category)
   - **The Forum** (link to forum explainer page)
   - **About** (link to About page)
   - **Contact** (link to Contact page)

   **How to add:**
   - Look in the left panel for "Pages" or "Custom Links"
   - Check the boxes next to pages you want
   - Click "Add to Menu"
   - Drag items to reorder them

4. **Save Menu**
   - Click "Save Menu" button

**Note:** We won't add the "Join" button to the menu - it'll be a separate button in the header design.

---

## Part 2: Upload Your Logo

### Step 2: Add Logo to Media Library

1. **Go to Media → Add New**

2. **Upload your logo image**
   - Recommended: SVG format (scales perfectly) or PNG with transparent background
   - Recommended size: 200-400px wide
   - Name it clearly: "tiaa-forum-logo.svg"

3. **Note the image ID or URL** (you'll select it in Elementor)

**Don't have a logo yet?**
- Use a text logo for now (just site name in your typography)
- You can replace it later without rebuilding the header

---

## Part 3: Create the Header Template

### Step 3: Access Theme Builder

1. **Go to Elementor → Theme Builder** (from WordPress dashboard)
   - Or: Templates → Theme Builder

2. **Click "Add New"** next to **Header**
   - If you see existing headers, click the "+" icon to add another
   - Or click "Add New Template" → Header

3. **Name your template:**
   - Enter: **"Main Header"** or **"Site Header"**
   - Click "Create Template"
   - Elementor editor will open

---

### Step 4: Set Up Header Container Structure

When the editor opens, you'll see an empty canvas.

1. **Add a Section (or Container)**
   - Click the **"+"** icon
   - In Elementor Pro, use **Container** (newer, more flexible)
   - If you see "Section", use that (older method, still works)

2. **Set Container/Section Layout:**
   - Select the container you just added
   - In the left panel (Layout tab):
      - **Content Width**: Full Width (edge to edge)
      - **Direction**: Row (horizontal)
      - **Justify Content**: Space Between
      - **Align Items**: Center
      - **Min Height**: 80px (gives the header height)

3. **Set Background Color:**
   - Still in the container settings
   - Go to **Style** tab
   - **Background Type**: Classic
   - **Color**: Click the color picker
      - Select **Global → Text (Navy)** (#2b2e60)

4. **Set Padding:**
   - Still in Style tab
   - **Padding**:
      - Left: 40px (or 5%)
      - Right: 40px (or 5%)
      - Top: 15px
      - Bottom: 15px
   - This creates space around the header content

**Your header should now be a navy bar spanning the full width.**

---

### Step 5: Add Logo Section

1. **Inside the container, click the "+" icon to add a widget**

2. **Search for and add: "Image" widget**

3. **Configure the Image widget:**
   - Click "Choose Image"
   - Select your logo from Media Library
   - **Size**: Medium or Full (depending on your logo size)

4. **Style the logo:**
   - Go to **Style** tab
   - **Width**:
      - Desktop: 180px (adjust based on your logo)
      - Mobile: 140px (smaller on phones)
   - **Alignment**: Left

5. **Make it clickable:**
   - Go to **Content** tab
   - Under **Link**, click the dynamic tag icon (if available)
   - Or manually enter your homepage URL: `/` or `https://yourdomain.org`

**Your logo should now appear on the left side of the navy header.**

---

### Step 6: Add Navigation Menu

1. **Click the "+" icon to add another widget** (next to the logo)

2. **Search for and add: "Nav Menu" widget**
   - This is an Elementor Pro widget
   - Displays your WordPress menu

3. **Configure the Nav Menu:**
   - **Content** tab:
      - **Menu**: Select "Main Navigation" (the menu you created)
      - **Layout**: Horizontal
      - **Pointer**: Underline or None (your choice)

4. **Style the menu items:**
   - Go to **Style** tab → **Main Menu** section

   **Typography:**
   - Click the typography pencil icon
   - Set font: **Inter** (or use Global → Primary, then adjust weight)
   - Weight: **500** (Medium)
   - Size: **16px**

   **Colors:**
   - **Text Color**: Global → Background (White) #FFFFFF
   - **Hover Color**: Global → Primary (Teal) #31bba6
   - **Active Color**: Global → Primary (Teal) #31bba6

   **Spacing:**
   - **Space Between** (horizontal padding): 20px or 30px
   - Gives room between menu items

5. **Set up mobile menu (hamburger):**
   - In **Style** tab → **Dropdown** section
   - **Breakpoint**: 1024px (menu turns into hamburger below this width)
   - **Toggle Button** color: White
   - **Dropdown Background**: Navy (same as header)
   - **Dropdown Text Color**: White

**Your navigation menu should now appear with white text on the navy background.**

---

### Step 7: Add Conditional Action Buttons

**IMPORTANT**: This header needs to show different buttons based on user login status. See `conditional-navigation.md` for full details.

For the MVP, we'll add TWO buttons that toggle based on login state:

#### Button 1: "Join" (for logged-out visitors)

1. **Click the "+" icon to add a widget** (after the nav menu)

2. **Add: "Button" widget**

3. **Configure the button:**
   - **Content** tab:
      - **Text**: "Join the Forum" or "Join"
      - **Link**: URL to your join/membership page (e.g., `/join`)
      - **Icon**: Optional - arrow icon after text

4. **Style the button:**
   - **Style** tab → **Typography**: Global → **Button Text**
   - **Style** tab → **Button**:
      - **Text Color**: Global → Background (White)
      - **Background**: Global → Secondary (Coral) #f37758
      - **Border Radius**: 8px
      - **Padding**: 24px horizontal, 12px vertical
   - **Hover effects**:
      - **Hover Background**: Global → Coral Dark #e06545
      - **Transition**: 0.3s

5. **Set Visibility (CRITICAL):**
   - Select the button widget
   - Go to **Advanced** tab
   - **Conditional Display** section (or similar - Pro feature)
   - **Show when**: User is **Logged Out**
   - OR if no conditional display: Leave as-is, we'll add this in a future step

#### Button 2: "Go to Forum" (for logged-in members)

1. **Add another "Button" widget** (next to the Join button)

2. **Configure the button:**
   - **Content** tab:
      - **Text**: "Go to Forum" or "Forum"
      - **Link**: URL to Discourse forum (WP-Discourse handles SSO)

3. **Style identically to Join button** (copy style, or use same settings)
   - OR use Teal instead of Coral to differentiate

4. **Set Visibility (CRITICAL):**
   - **Advanced** tab → **Conditional Display**
   - **Show when**: User is **Logged In**

**Result**: Only ONE button shows at a time - "Join" for visitors, "Go to Forum" for members.

#### Button 3: "Contribute" (for logged-in members only)

1. **Add another "Button" or "Link" widget**

2. **Configure:**
   - **Text**: "Contribute"
   - **Link**: `/contribute` or contribution page URL

3. **Style**: Simpler than primary CTA - maybe just text link with teal color on hover

4. **Set Visibility:**
   - **Show when**: User is **Logged In**

#### Button 4: "Logout" (for logged-in members only)

1. **Add a text link or small button**

2. **Configure:**
   - **Text**: "Logout" or "Sign Out"
   - **Link**: `/tiaa-logout?redirect_to=/`
   - ⚠️ **Do NOT use `/wp-login?action=logout` or `wp_logout_url()`** — see
     "Why the Logout Link Uses `/tiaa-logout`" under Notes for Future
     Maintainers below before changing this.

3. **Style**: Minimal - small text link

4. **Set Visibility:**
   - **Show when**: User is **Logged In**

**Note**: If Elementor Pro's conditional visibility isn't available yet, you can:
- Build both buttons now
- Add visibility conditions later using a plugin or custom code
- For now, just build the "Join" button to get started

---

### Step 8: Adjust Container Layout (Fine-tuning)

Select the main container again and verify spacing:

1. **Layout** tab:
   - **Justify Content**: Space Between
      - This pushes logo left, button right, menu in middle

   - **Gap**: 30px
      - Space between logo, menu, and button

   - **Align Items**: Center
      - Vertically centers everything

**Your header should now have: Logo (left) → Menu (center-ish) → Join button (right)**

---

### Step 9: Make Header Sticky (Optional but Recommended)

This makes the header stay at the top when users scroll.

1. **Select the main container**

2. **Go to Advanced tab → Motion Effects**

3. **Sticky**: On
   - **Sticky On**: Desktop, Tablet, Mobile (all devices)
   - **Offset**: 0
   - **Effects Offset**: 0

4. **(Optional) Add sticky effects:**
   - **Background**: You can add a slight shadow or darker background when sticky
   - Go to Style tab → Background
   - Add a box-shadow on scroll: `0 2px 10px rgba(0,0,0,0.1)`

**Your header will now stick to the top as users scroll down the page.**

---

### Step 10: Set Display Conditions

This tells WordPress where to show this header.

1. **Look at the bottom left of the Elementor editor**
   - You should see a purple bar with "Display Conditions" or "Set Conditions"

2. **Click "Add Condition"** or "Display Settings"

3. **Set to: "Entire Site"**
   - This means this header appears on every page
   - You can get more specific later (e.g., exclude from certain pages)

4. **Save and Publish**

---

### Step 11: Preview and Test

1. **Click "Publish"** or "Update" (top left)

2. **Exit Elementor editor**
   - Click the hamburger menu → Exit

3. **Test as LOGGED-OUT user:**
   - Visit your site's homepage (in incognito/private browsing)
   - Header should appear at top
   - Should be sticky (stays when you scroll)
   - **Should see**: "Join" button (coral)
   - **Should NOT see**: "Go to Forum", "Contribute", "Logout"

4. **Test as LOGGED-IN member:**
   - Log in to WordPress
   - Visit homepage
   - **Should see**: "Go to Forum" button, "Contribute" link, "Logout" link
   - **Should NOT see**: "Join" button

5. **Test responsiveness:**
   - Resize browser window to mobile size
   - Below 1024px, menu should turn into hamburger icon
   - Click hamburger - menu should slide out
   - Conditional buttons should still show/hide based on login state

6. **Test all links:**
   - Click menu items → should navigate to pages
   - Click "Join" button (logged out) → should go to join page
   - Click "Go to Forum" (logged in) → should go to forum with SSO
   - Click "Contribute" (logged in) → should go to contribution page
   - Click "Logout" (logged in) → should log out and redirect
   - Click logo → should go home

**IMPORTANT**: If conditional visibility isn't working yet:
- All buttons may show at once (that's OK for now)
- Refer to `conditional-navigation.md` for implementation details
- This can be configured later as part of Phase 2

---

## Header Design Variations (Optional Enhancements)

Once the basic header works, you can enhance it:

### Add a subtle divider line:
- Select main container
- Style → Border → Bottom
- Width: 1px
- Color: White at 20% opacity (`rgba(255, 255, 255, 0.2)`)

### Add user menu (for logged-in users):
- Add another button or menu
- Use dynamic visibility: Show only when user is logged in
- Advanced → Custom CSS or use visibility conditions

### Add search icon:
- Add an Icon widget
- Set icon to magnifying glass
- Link to search page or trigger search popup

---

## Common Issues and Solutions

### Issue 1: Logo is too big/small
**Solution:**
- Select the Image widget (logo)
- Style → Width → adjust pixel value
- Set different sizes for desktop/tablet/mobile

### Issue 2: Menu items too close together
**Solution:**
- Select Nav Menu widget
- Style → Main Menu → Space Between → increase value

### Issue 3: Header not sticky
**Solution:**
- Select the container
- Advanced → Motion Effects → Sticky → make sure it's ON
- Check offset is 0

### Issue 4: Mobile menu not showing
**Solution:**
- Select Nav Menu widget
- Advanced → Responsive → Breakpoint → set to 1024px or 768px
- Make sure mobile menu colors are set (white text on navy)

### Issue 5: Button too close to edge on mobile
**Solution:**
- Select main container
- Advanced → Responsive → Mobile settings
- Reduce padding-right or hide button on mobile

---

## Mobile Optimization Tips

The header needs special attention on mobile:

### Recommended mobile adjustments:

1. **Simplify on small screens:**
   - Hide "About" or less important links
   - Right-click Nav Menu → Responsive → Hide on Mobile
   - Only show: Home, Hot Topics, Forum, Join button

2. **Reduce button size:**
   - Select Join button
   - Advanced → Responsive → Mobile
   - Reduce padding and font size slightly

3. **Adjust logo size:**
   - Make logo smaller on mobile (120-140px)

4. **Test hamburger menu:**
   - Make sure it's easy to tap (at least 44x44px)
   - Ensure dropdown menu is readable

---

## Header Checklist

Before moving on, verify:

- [ ] Logo displays and links to homepage
- [ ] Navigation menu shows correct pages
- [ ] "Join" button is prominent and clickable
- [ ] Colors use Global Colors (navy background, white text, coral button)
- [ ] Typography uses Global Fonts
- [ ] Header is sticky on scroll
- [ ] Mobile hamburger menu works
- [ ] Header displays on all pages (Display Conditions set)

---

## What's Next?

Now that you have a header:

✅ **Done**: Global Colors  
✅ **Done**: Global Fonts  
✅ **Done**: Header Template  
🔲 **Next**: Footer Template (quick, similar process)  
🔲 **Next**: Homepage sections (hero, features, etc.)  
🔲 **Next**: Hot Topics templates

---

## Quick Reference: Header Structure

```
Container (Navy background, full width, sticky)
├── Image Widget (Logo - left aligned)
├── Nav Menu Widget (WordPress menu - center)
└── Button Widget (Join - right aligned, coral background)
```

---

## Notes for Future Maintainers

### Updating menu items:
1. Go to Appearance → Menus in WordPress
2. Add/remove/reorder items
3. Save Menu
4. Changes appear automatically in header

### Changing header colors:
1. Go to Elementor → Theme Builder → Header → Edit
2. Select main container
3. Change background color (use Global Colors)
4. Update and publish

### Updating logo:
1. Edit header in Theme Builder
2. Select Image widget
3. Click "Choose Image" → upload new logo
4. Update and publish

### Making header transparent (for specific pages):
- Create a new Header template
- Set background to transparent
- Set Display Conditions to specific page (e.g., homepage only)
- Use darker logo version for visibility

### Conditional visibility:
- Ensure buttons (Join, Forum, Contribute, Logout) show/hide based on user login state
- Refer to `01a-conditional-navigation.md` for implementation details
- This can be configured later as part of Phase 2

### Why the Logout Link Uses `/tiaa-logout`

The Logout link/button href must be **`/tiaa-logout?redirect_to=/`** —
**never** `/wp-login?action=logout` or `wp_logout_url()`.

**Why:** Wordfence's Brute Force Protection locks out `wp-login.php` after a
handful of failed login attempts. Because the old logout link pointed at
`wp-login.php?action=logout`, a locked-out visitor couldn't log out either —
logout and login shared the same script, so a login lockout became a logout
lockout too. `/tiaa-logout` (added in `tiaa-wpplugin` v0.0.12) is a separate
endpoint that never touches `wp-login.php`, so it stays reachable during a
lockout. It still logs the member out of Discourse correctly — see the
`tiaa-wpplugin` README for how.

**If a future Elementor header re-import or redesign resets this link**,
re-enter the href above. It will look unfamiliar (not a WordPress-standard
URL) — that's expected, don't "fix" it back to `/wp-login?action=logout`.
