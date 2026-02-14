# Color Palette Extraction from Replit Design

## Source: index-TMxO1IMj.css

### Primary Colors (from :root CSS variables)

#### Teal/Primary
- **Base**: `hsl(170, 58%, 46%)` → **#31bba6**
- **50 (lightest)**: `hsl(170, 58%, 96%)`
- **100**: `hsl(170, 58%, 90%)`
- **600**: `hsl(170, 58%, 46%)` (same as base)
- **700 (darker)**: `hsl(170, 58%, 40%)`

#### Coral/Secondary
- **Base**: `hsl(12, 87%, 65%)` → **#f37758**
- **50 (lightest)**: `hsl(12, 87%, 96%)`
- **100**: `hsl(12, 87%, 90%)`
- **600**: `hsl(12, 87%, 65%)` (same as base)
- **700 (darker)**: `hsl(12, 87%, 55%)`

#### Navy
- **Base**: `hsl(236, 38%, 27%)` → **#2b2e60**
- **Light**: `hsl(236, 38%, 35%)`
- **Dark**: `hsl(236, 38%, 20%)` → **#1e2050**

#### Red/Destructive (used for errors, warnings)
- **Base**: `hsl(355, 84%, 61%)` → **#ef4958**

### Neutral Colors

#### Backgrounds
- **White**: `hsl(0, 0%, 100%)` → **#FFFFFF**
- **Background**: `hsl(0, 0%, 100%)` → **#FFFFFF** (same)
- **Muted**: `hsl(240, 1.9608%, 90%)` → light gray
- **Card**: `hsl(180, 6.6667%, 97.0588%)` → very light gray

#### Text
- **Foreground (primary text)**: `hsl(236, 38%, 27%)` → **#2b2e60** (Navy)
- **Muted Foreground**: Navy at reduced opacity or gray
- **Gray scale used**:
  - gray-50: `#f9fafb`
  - gray-100: `#f3f4f6`
  - gray-200: `#e5e7eb`
  - gray-300: `#d1d5db`
  - gray-400: `#9ca3af`
  - gray-500: `#6b7280`
  - gray-600: `#4b5563`
  - gray-700: `#374151`
  - gray-800: `#1f2937`
  - gray-900: `#111827`

#### Borders
- **Border**: `hsl(236, 20%, 90%)` → light purple-gray
- **Input**: `hsl(200, 23.0769%, 97.451%)` → very light blue-gray

### Accent Colors (from Tailwind classes)

These appear in the CSS as background utilities:

- **Blue-100**: `rgb(219 234 254)` → **#dbeafe**
- **Green-100**: `rgb(220 252 231)` → **#dcfce7**
- **Indigo-100**: `rgb(224 231 255)` → **#e0e7ff**
- **Purple-100**: `rgb(243 232 255)` → **#f3e8ff**
- **Red-100**: `rgb(254 226 226)` → **#fee2e2**
- **Yellow-100**: `rgb(254 249 195)` → **#fef9c3**

### Usage Patterns from CSS

#### Gradients
The CSS shows several gradient patterns:
```css
.from-[\#31bba6] → Teal
.to-[\#2aa090] → Slightly darker teal
.from-[\#f37758] → Coral
.to-[\#e06545] → Darker coral
.from-[\#2b2e60] → Navy
.to-[\#1e2050] → Darker navy
```

#### Opacity Variations
Many colors used at 5%, 10%, 15%, 20% opacity for subtle backgrounds:
- `.bg-[\#2b2e60]\/10` → Navy at 10%
- `.bg-[\#31bba6]\/15` → Teal at 15%
- `.bg-[\#f37758]\/10` → Coral at 10%

---

## Recommended Elementor Global Color Setup

Based on this extraction, here's what to configure:

### Core Colors (4 required by Elementor)
1. **Primary** → #31bba6 (Teal)
2. **Secondary** → #f37758 (Coral)
3. **Text** → #2b2e60 (Navy)
4. **Accent** → #1e2050 (Dark Navy) or keep for special highlights

### Custom Colors (additional)
5. **Background** → #FFFFFF
6. **Muted Background** → #f9fafb (gray-50)
7. **Border** → Calculate from `hsl(236, 20%, 90%)`
8. **Error/Destructive** → #ef4958 (Red)
9. **Success** → #dcfce7 (Green-100) or derive from teal
10. **Warning** → #fef9c3 (Yellow-100)

### For Gradients
Create additional custom colors for gradient endpoints:
- **Teal Dark** → #2aa090
- **Coral Dark** → #e06545
- **Navy Dark** → #1e2050

---

## HSL to HEX Conversion Reference

For colors defined in HSL, here are the HEX equivalents:

| HSL | HEX | Color Name |
|-----|-----|------------|
| hsl(170, 58%, 46%) | #31bba6 | Teal Primary |
| hsl(170, 58%, 40%) | #2aa090 | Teal Dark |
| hsl(12, 87%, 65%) | #f37758 | Coral Primary |
| hsl(12, 87%, 55%) | #e06545 | Coral Dark |
| hsl(236, 38%, 27%) | #2b2e60 | Navy Primary |
| hsl(236, 38%, 20%) | #1e2050 | Navy Dark |
| hsl(355, 84%, 61%) | #ef4958 | Red/Error |
| hsl(236, 20%, 90%) | #e4e4ed | Border (approximate) |

Use an HSL to HEX converter online to verify these if needed.