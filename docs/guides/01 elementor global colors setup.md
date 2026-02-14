# Step-by-Step: Setting Up Elementor Pro Global Colors

## Why We're Doing This First

Global Colors in Elementor Pro work like design tokens - you define your color palette once, and then use those colors throughout your site. When you need to adjust a color (say, make the teal slightly lighter), you change it in ONE place and it updates everywhere automatically.

This is essential for volunteer maintainers who may not be familiar with hex codes or design systems. They can just select "Primary" instead of trying to remember "#31bba6".

---

## Prerequisites

- [ ] WordPress admin access
- [ ] Elementor Pro installed and activated
- [ ] Hello Elementor theme (or any Elementor-compatible theme) active

---

## Step 1: Access Global Colors

1. **Log into WordPress admin**
   - Go to your WordPress dashboard

2. **Navigate to Elementor settings**
   - In the left sidebar, hover over **Elementor**
   - Click **Site Settings**

   ![Screenshot: WordPress sidebar with Elementor menu expanded]

3. **Open Global Colors panel**
   - In the Site Settings interface, look for the tabs at the top
   - Click on **Global Colors** (might be under Settings → Site Settings → Global Colors)

   **Alternative path** (if above doesn't work):
   - Open any page in Elementor editor
   - Click the hamburger menu (three lines) in the top left
   - Select **Site Settings**
   - Click **Global Colors** in the left panel

---

## Step 2: Understanding the Default Colors

Elementor provides 4 default global colors:
- **Primary** (usually blue)
- **Secondary** (usually pink/purple)
- **Text** (usually dark gray/black)
- **Accent** (usually another bright color)

You can also add unlimited **Custom Colors** below these.

**What we're going to do:**
- Replace the default colors with your Replit design colors
- Add custom colors for specific uses (backgrounds, borders, etc.)

---

## Step 3: Set Primary Color (Teal)

1. **Click on the Primary color swatch** (the colored circle next to "Primary")

2. **Enter the hex code**: `#31bba6`
   - You can also use the color picker if you prefer
   - This is your main teal color from the Replit design

3. **Rename it** (optional but recommended):
   - Click the pencil icon next to "Primary"
   - Change the name to **"Primary (Teal)"**
   - This helps maintainers understand what the color represents

4. **Save** (usually saves automatically)

**What this color is used for:**
- Main CTAs (Call to Action buttons like "Join the Forum")
- Links (can configure this)
- Highlights and accents throughout the site

---

## Step 4: Set Secondary Color (Coral)

1. **Click on the Secondary color swatch**

2. **Enter the hex code**: `#f37758`
   - This is your coral/orange color

3. **Rename to**: **"Secondary (Coral)"**

**What this color is used for:**
- Secondary CTAs (alternative actions)
- Highlights that need to contrast with teal
- Category badges or tags

---

## Step 5: Set Text Color (Navy)

1. **Click on the Text color swatch**

2. **Enter the hex code**: `#2b2e60`
   - This is your navy color for main text

3. **Rename to**: **"Text (Navy)"**

**What this color is used for:**
- Body text throughout the site
- Headings (unless you choose to use a different color)
- Default text color for most elements

**Important note:** This is a fairly dark navy, which should provide good contrast on white backgrounds. Elementor will use this automatically for text elements unless you override it.

---

## Step 6: Set Accent Color (Dark Navy)

1. **Click on the Accent color swatch**

2. **Enter the hex code**: `#1e2050`
   - This is a darker shade of navy

3. **Rename to**: **"Accent (Dark Navy)"**

**What this color is used for:**
- Hover states on buttons
- Borders or dividers that need more emphasis
- Background for sections that need strong contrast

---

## Step 7: Add Custom Colors

Below the 4 main colors, you'll see a section to add **Custom Colors**. Let's add the essential ones:

### Custom Color 1: Background
1. **Click "+ Add Color"**
2. **Enter hex code**: `#FFFFFF` (white)
3. **Name it**: **"Background"**
4. **Purpose**: Main background color for the site

### Custom Color 2: Muted Background
1. **Click "+ Add Color"**
2. **Enter hex code**: `#f9fafb` (very light gray)
3. **Name it**: **"Muted Background"**
4. **Purpose**: Subtle backgrounds for cards, alternating sections

### Custom Color 3: Border
1. **Click "+ Add Color"**
2. **Enter hex code**: `#e4e4ed` (light purple-gray)
3. **Name it**: **"Border"**
4. **Purpose**: Dividers, card borders, input outlines

### Custom Color 4: Error/Destructive
1. **Click "+ Add Color"**
2. **Enter hex code**: `#ef4958` (red)
3. **Name it**: **"Error"**
4. **Purpose**: Error messages, destructive actions (like "Delete")

### Custom Color 5: Teal Dark (for gradients)
1. **Click "+ Add Color"**
2. **Enter hex code**: `#2aa090`
3. **Name it**: **"Teal Dark"**
4. **Purpose**: Gradient endpoints, hover states on teal buttons

### Custom Color 6: Coral Dark (for gradients)
1. **Click "+ Add Color"**
2. **Enter hex code**: `#e06545`
3. **Name it**: **"Coral Dark"**
4. **Purpose**: Gradient endpoints, hover states on coral buttons

---

## Step 8: Verify Your Setup

After adding all colors, your Global Colors panel should show:

**Main Colors:**
1. ⬤ Primary (Teal) - #31bba6
2. ⬤ Secondary (Coral) - #f37758
3. ⬤ Text (Navy) - #2b2e60
4. ⬤ Accent (Dark Navy) - #1e2050

**Custom Colors:**
5. ⬤ Background - #FFFFFF
6. ⬤ Muted Background - #f9fafb
7. ⬤ Border - #e4e4ed
8. ⬤ Error - #ef4958
9. ⬤ Teal Dark - #2aa090
10. ⬤ Coral Dark - #e06545

---

## Step 9: Test the Colors

Let's make sure everything works:

1. **Open a page in Elementor editor** (or create a new test page)
   - Pages → Add New → Edit with Elementor

2. **Add a heading widget**
   - Drag a "Heading" widget onto the page
   - Type "Test Heading"

3. **Change the heading color**
   - Select the heading widget
   - Go to **Style** tab
   - Under **Text Color**, click the color picker
   - You should see a **Global** tab at the top of the picker
   - Click it - you should see all your global colors listed!

4. **Select "Primary (Teal)"**
   - The heading should turn teal
   - This confirms global colors are working

5. **Test a button**
   - Add a "Button" widget
   - In the Style tab, set:
     - **Background Color** → Primary (Teal)
     - **Text Color** → Background (White)
   - Hover over the button preview
   - Set **Hover Background Color** → Teal Dark
   - You should see the color transition

**If you see your colors in the Global tab of the color picker, you're done with this step!**

---

## Common Issues and Solutions

### Issue 1: Can't find Site Settings
**Solution**: Make sure you're using Elementor Pro (not the free version). Global Colors is a Pro feature.

### Issue 2: Colors not showing in the Global tab
**Solution**:
- Make sure you clicked "Update" or "Publish" after setting up the colors
- Try refreshing your browser
- Clear Elementor cache: Elementor → Tools → Regenerate CSS

### Issue 3: Colors look different on the site than in the color picker
**Solution**:
- Check if there's any custom CSS overriding your colors
- Make sure you're viewing the page after publishing (not in preview mode)

---

## What's Next?

Now that you have Global Colors set up:

✅ **Done**: Color palette defined
🔲 **Next**: Set up Global Fonts (typography)
🔲 **Next**: Create Header template using these colors
🔲 **Next**: Build homepage sections

---

## Notes for Future Maintainers

**Adding a new color:**
1. Go to Elementor → Site Settings → Global Colors
2. Click "+ Add Color"
3. Choose your hex code and name it descriptively
4. That color will now be available throughout the site

**Changing a color:**
1. Find the color in Global Colors
2. Click the swatch and change the hex code
3. The change applies site-wide automatically
4. Always test on a staging site first!

**Best practices:**
- Always use Global Colors (not custom colors) for consistency
- Name colors by their purpose ("Primary") not their appearance ("Blue")
- Document any color changes in the project repo decision log