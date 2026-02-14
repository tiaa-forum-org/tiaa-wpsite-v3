# Step-by-Step: Setting Up Elementor Pro Global Fonts

## Why We're Doing This

Global Fonts work just like Global Colors - you define your typography system once, and use it everywhere. This ensures:
- Consistent text styling across the entire site
- Easy updates (change a heading size in one place, updates everywhere)
- Volunteer maintainers don't need to remember font sizes or weights

From your Replit design, the site uses **one main font family** (Inter) with different weights for hierarchy. This is a modern, clean approach that's easy to maintain.

---

## Prerequisites

- [ ] Global Colors already set up (you just did this!)
- [ ] WordPress admin access
- [ ] Elementor Pro activated

---

## What We Learned from the Replit CSS

From the CSS file, I extracted:

**Font Family:**
- **Inter** (loaded from Google Fonts)
- Fallbacks: "Open Sans", sans-serif

**Weights Used:**
- 400 = Regular (body text)
- 500 = Medium (emphasis, subheadings)
- 600 = Semi-Bold (section headings)
- 700 = Bold (main headings, buttons)

**Line Heights:**
- Body text: 1.5 to 1.75 (comfortable reading)
- Headings: Tighter (1.1 to 1.3) for impact

**Font Sizes (from Tailwind classes in CSS):**
- text-xs: 0.75rem (12px)
- text-sm: 0.875rem (14px)
- text-base: 1rem (16px) ← body text
- text-lg: 1.125rem (18px)
- text-xl: 1.25rem (20px)
- text-2xl: 1.5rem (24px)
- text-3xl: 1.875rem (30px)
- text-4xl: 2.25rem (36px)
- text-5xl: 3rem (48px)
- text-6xl: 3.75rem (60px)

---

## Step 1: Access Global Fonts

1. **Open Elementor Site Settings**
   - Method 1: **Elementor → Site Settings** (from WordPress dashboard)
   - Method 2: Open any page in Elementor Editor → Hamburger menu → **Site Settings**

2. **Click on the "Typography" or "Global Fonts" tab**
   - This is usually right next to "Global Colors"
   - You should see default font styles listed

---

## Step 2: Understanding the Default Font Styles

Elementor provides 4 default typography styles:
- **Primary** (usually for body text)
- **Secondary** (usually for headings)
- **Text** (another text option)
- **Accent** (for special emphasis)

**What we're going to do:**
- Replace these with a clear hierarchy based on the Replit design
- Set up one font family (Inter) with different weights/sizes
- Add custom font styles for specific uses

---

## Step 3: Set Up Primary Font (Body Text)

This is your main body text - paragraphs, descriptions, general content.

1. **Click on "Primary"** to expand its settings

2. **Set Font Family**
   - Click the dropdown under "Family"
   - Type "Inter" in the search box
   - Select **Inter** from the Google Fonts list
   - (Elementor automatically loads it from Google Fonts)

3. **Set Font Weight**
   - Select **400 (Regular)**
   - This is comfortable for reading long passages

4. **Set Font Size**
   - Desktop: **16px** (or 1rem)
   - Tablet: **16px** (keep same)
   - Mobile: **16px** (keep same)
   - *16px is the web standard for body text and maintains readability*

5. **Set Line Height**
   - Enter: **1.6** (no unit needed, this is a multiplier)
   - This gives comfortable spacing between lines for reading

6. **Optional: Rename it**
   - Click the pencil icon
   - Change name to **"Body Text"**

**What this is used for:**
- All paragraph text
- Descriptions
- General content
- Default text style

---

## Step 4: Set Up Secondary Font (Main Headings)

This is for your primary headings - H1s, hero headlines, page titles.

1. **Click on "Secondary"** to expand

2. **Set Font Family**
   - Select **Inter** (same as body)

3. **Set Font Weight**
   - Select **700 (Bold)**
   - This creates strong visual hierarchy

4. **Set Font Size**
   - Desktop: **48px** (3rem - for H1s)
   - Tablet: **36px** (2.25rem)
   - Mobile: **30px** (1.875rem)
   - *Responsive sizing makes headings readable on all devices*

5. **Set Line Height**
   - Enter: **1.2**
   - Tighter line height for impact

6. **Set Text Transform** (optional)
   - Usually "None"
   - Or "Capitalize" if your design capitalizes headings

7. **Rename to**: **"Heading Large"** or **"H1"**

**What this is used for:**
- Page titles
- Hero headlines
- Main section headings
- H1 elements

---

## Step 5: Set Up Text Font (Subheadings)

This is for H2s, H3s - secondary levels of hierarchy.

1. **Click on "Text"** to expand

2. **Set Font Family**
   - Select **Inter**

3. **Set Font Weight**
   - Select **600 (Semi-Bold)**

4. **Set Font Size**
   - Desktop: **30px** (1.875rem - H2 size)
   - Tablet: **24px** (1.5rem)
   - Mobile: **22px**

5. **Set Line Height**
   - Enter: **1.3**

6. **Rename to**: **"Heading Medium"** or **"H2"**

**What this is used for:**
- Section headings
- H2 elements
- Card titles
- Feature headings

---

## Step 6: Set Up Accent Font (Small Headings / Emphasis)

This is for H3s, labels, button text, and emphasized content.

1. **Click on "Accent"** to expand

2. **Set Font Family**
   - Select **Inter**

3. **Set Font Weight**
   - Select **600 (Semi-Bold)** or **500 (Medium)**
   - Use 500 if you want it lighter than H2s

4. **Set Font Size**
   - Desktop: **20px** (1.25rem - H3 size)
   - Tablet: **18px**
   - Mobile: **16px**

5. **Set Line Height**
   - Enter: **1.4**

6. **Rename to**: **"Heading Small"** or **"H3"**

**What this is used for:**
- H3 elements
- Card headings
- Widget titles
- Emphasized text

---

## Step 7: Add Custom Font Styles

Below the main 4 fonts, you can add custom styles. Here are recommended additions:

### Custom Font 1: Button Text
1. **Click "+ Add Custom Font"**
2. **Name**: "Button Text"
3. **Family**: Inter
4. **Weight**: 500 (Medium) or 600 (Semi-Bold)
5. **Size**:
   - Desktop: 16px
   - Mobile: 14px
6. **Transform**: Uppercase (optional - if buttons use all caps)
7. **Letter Spacing**: 0.5px (slight spacing for uppercase)

**Purpose**: Consistent button text styling

### Custom Font 2: Small Text / Captions
1. **Click "+ Add Custom Font"**
2. **Name**: "Caption" or "Small Text"
3. **Family**: Inter
4. **Weight**: 400 (Regular)
5. **Size**:
   - Desktop: 14px (0.875rem)
   - Mobile: 12px
6. **Line Height**: 1.5

**Purpose**: Image captions, meta info, timestamps, footnotes

### Custom Font 3: Large Display (for Hero sections)
1. **Click "+ Add Custom Font"**
2. **Name**: "Display Large" or "Hero Text"
3. **Family**: Inter
4. **Weight**: 700 (Bold)
5. **Size**:
   - Desktop: 60px (3.75rem)
   - Tablet: 48px
   - Mobile: 36px
6. **Line Height**: 1.1

**Purpose**: Extra large hero headlines, homepage hero

---

## Step 8: Typography Settings (Optional Fine-Tuning)

While you're in Site Settings → Typography, check these global settings:

### Default Font
- This applies to any text that doesn't use a Global Font
- Set it to: **Inter, 400, 16px** (same as your body text)

### Default Color
- Should be set to your **Text (Navy)** global color (#2b2e60)
- Elementor might have already picked this up from Global Colors

---

## Step 9: Verify Your Setup

Your Global Fonts panel should now show:

**Main Fonts:**
1. **Primary** → Body Text (Inter 400, 16px, 1.6)
2. **Secondary** → H1/Heading Large (Inter 700, 48px, 1.2)
3. **Text** → H2/Heading Medium (Inter 600, 30px, 1.3)
4. **Accent** → H3/Heading Small (Inter 600, 20px, 1.4)

**Custom Fonts:**
5. **Button Text** (Inter 500/600, 16px, uppercase)
6. **Caption/Small Text** (Inter 400, 14px)
7. **Display Large/Hero** (Inter 700, 60px, 1.1)

---

## Step 10: Test the Typography

Let's make sure it works:

1. **Open a test page in Elementor**

2. **Add a Heading widget**
   - Drag a "Heading" widget onto the page
   - Type "This is a Main Heading"

3. **Apply Global Font**
   - Select the widget
   - Go to **Style** tab
   - Under **Typography**, click the pencil icon
   - You should see a **Global** dropdown at the top
   - Select **"Secondary" (Heading Large)**
   - The heading should become Inter 700, 48px

4. **Add a Text Editor widget**
   - Add some paragraph text
   - Select it
   - In Style → Typography → Global
   - Select **"Primary" (Body Text)**
   - Should be Inter 400, 16px

5. **Add a Button widget**
   - Add a button
   - In Style → Typography → Global
   - Select **"Button Text"**
   - Should be Inter 500/600

**If you see your font styles in the Global dropdown, you're done!**

---

## Typography Quick Reference Chart

| Element | Global Font | Size (Desktop) | Weight | Line Height |
|---------|-------------|----------------|--------|-------------|
| H1 / Page Title | Secondary | 48px | 700 | 1.2 |
| H2 / Section Heading | Text | 30px | 600 | 1.3 |
| H3 / Card Heading | Accent | 20px | 600 | 1.4 |
| Body / Paragraph | Primary | 16px | 400 | 1.6 |
| Button Text | Button Text | 16px | 500 | - |
| Caption / Meta | Small Text | 14px | 400 | 1.5 |
| Hero Display | Display Large | 60px | 700 | 1.1 |

---

## Common Typography Patterns from Replit Design

Based on the CSS, here are common text styling patterns you'll use:

### Hero Section
- **Main headline**: Display Large (60px, 700)
- **Subheadline**: Text/H2 (30px, 600) or larger body (18-20px, 400)
- **CTA buttons**: Button Text (16px, 500, uppercase)

### Section Headers
- **Section title**: Secondary/H1 (48px, 700) or Text/H2 (30px, 600)
- **Section description**: Primary body (16px, 400) or slightly larger (18px, 400)

### Cards
- **Card title**: Accent/H3 (20px, 600)
- **Card description**: Primary body (16px, 400)
- **Card meta** (author, date): Small Text (14px, 400)

### Navigation
- **Nav links**: Body size (16px) with weight 500 (Medium)
- **Active/current**: Same size, weight 600 (Semi-Bold)

---

## Tips for Using Global Fonts

### DO:
✅ Always use Global Fonts from the dropdown (don't set custom sizes)
✅ Use the responsive settings (desktop/tablet/mobile) built into Global Fonts
✅ Stick to the weight hierarchy: 400 → 500 → 600 → 700
✅ Keep line heights consistent: tight for headings (1.1-1.3), comfortable for body (1.5-1.6)

### DON'T:
❌ Manually set font sizes on individual elements (defeats the purpose)
❌ Use more than one font family (keeps design cohesive)
❌ Make mobile text too small (minimum 14px for body text)
❌ Forget to set responsive sizes for headings

---

## Common Issues and Solutions

### Issue 1: Font doesn't load / looks like default
**Solution**:
- Check your internet connection (Google Fonts loads from CDN)
- Clear Elementor cache: Elementor → Tools → Regenerate CSS
- Make sure Inter is selected from Google Fonts, not "Default"

### Issue 2: Text too small on mobile
**Solution**:
- Go back to Global Fonts
- Set mobile-specific sizes (click the mobile icon in size field)
- Minimum 14px for body, 24px+ for main headings

### Issue 3: Line height looks weird
**Solution**:
- Use unitless values (1.5 not 1.5em or 150%)
- Headings: 1.1 to 1.3
- Body text: 1.5 to 1.75
- Never use line height less than 1.0

### Issue 4: Bold text not loading
**Solution**:
- Go to Elementor → Custom Fonts
- Check that Google Fonts weights are loaded (400, 500, 600, 700)
- If missing, manually add: Elementor → Custom Fonts → Add Google Font weights

---

## What's Next?

Now that you have Typography set up:

✅ **Done**: Color palette defined
✅ **Done**: Typography system defined
🔲 **Next**: Create Header template using colors + fonts
🔲 **Next**: Build homepage hero section
🔲 **Next**: Set up templates for Hot Topics

---

## Notes for Future Maintainers

### Changing text styles site-wide:
1. Go to Elementor → Site Settings → Global Fonts
2. Edit the relevant font style (e.g., "Primary" for body text)
3. Change size, weight, or other properties
4. The change applies everywhere that font style is used
5. Always test on staging first!

### Adding a new heading level:
1. Click "+ Add Custom Font"
2. Name it descriptively (e.g., "H4 Small Heading")
3. Set family (Inter), weight (500 or 600), size (18px)
4. Use it consistently throughout the site

### Best practices:
- Use Global Fonts, not custom typography on individual widgets
- Maintain the weight hierarchy (don't use 700 for body text)
- Keep responsive sizes in mind (test on mobile)
- Document any changes in the project decision log