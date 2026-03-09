# Pre-Launch Performance Checklist
**TIAA Forum Rebuild - v3**
**Status**: Parked for pre-launch attention

---

## Overview

Performance work is not blocking MVP build, but must be completed before going live. This checklist covers the two layers of performance optimization available in your stack: Elementor's built-in asset optimization and server-level caching.

**Estimated time to complete**: 2-4 hours  
**When to do it**: After all pages are built and content is in place, before launch

---

## Layer 1: Elementor Performance Settings

These are free, built-in, and require no additional plugins. Turn them on once — they apply site-wide.

**Location**: WordPress Admin → Elementor → Settings → Performance

### Checklist
- [ ] **CSS Print Method**: Set to "External File" (not inline) — generates a separate CSS file that browsers can cache
- [ ] **Load Font Awesome**: Set to "Dynamic Loading" — only loads icons actually used on each page
- [ ] **Improved Asset Loading**: Enable — removes Elementor CSS/JS from pages that don't use Elementor widgets
- [ ] **Inline Font Icons**: Enable — reduces HTTP requests for icons
- [ ] **Critical CSS**: Enable — prioritizes above-the-fold styles so page appears to load faster
- [ ] **Lazy Load Background Images**: Enable — defers off-screen background images
- [ ] **Image Lazy Load**: Confirm enabled (may be set at WordPress level via Settings → Media)

### After enabling:
Go to Elementor → Tools → Regenerate CSS & Data. This rebuilds all asset files with the new settings applied.

---

## Layer 2: Page Caching Plugin

Elementor's settings optimize asset delivery but don't cache full pages. A caching plugin serves pre-built HTML to visitors, bypassing PHP and database queries entirely. This is the single biggest performance win.

### Recommended Options

| Plugin | Cost | Notes |
|--------|------|-------|
| **WP Rocket** | ~$59/yr | Best option — easiest to configure, excellent Elementor compatibility, includes CDN integration |
| **W3 Total Cache** | Free | More complex to configure but capable. Good free option. |
| **LiteSpeed Cache** | Free | Best if your server runs LiteSpeed (yours runs Nginx in Docker, so less relevant) |

**Recommendation**: WP Rocket is worth the cost for a volunteer-maintained site — it's largely set-and-forget and well documented.

### What to configure in WP Rocket (when ready):
- [ ] Enable page caching
- [ ] Enable browser caching
- [ ] Enable GZIP compression
- [ ] Minify CSS files
- [ ] Minify JavaScript files (test carefully — can break plugins)
- [ ] Enable lazy loading for images
- [ ] Configure cache lifespan (24 hours is reasonable for this site)
- [ ] Exclude `/join` page from cache (payment forms should not be cached)
- [ ] Exclude any pages with WP SimplePay forms from cache

---

## Layer 3: Redis Object Cache (Optional — Advanced)

**What it does**: Stores database query results in memory so repeated queries (like your Hot Topics loops) are served from RAM instead of hitting the database each time.

**Why it matters for your stack**: Your Docker Compose setup on DigitalOcean can add Redis as an additional container with relatively little effort. Combined with the **Redis Object Cache** WordPress plugin, this gives you persistent object caching that survives page cache misses.

**When to consider this**: Only if page load times are still unsatisfactory after Layers 1 and 2 are in place. For a site with ~11-50 Hot Topics posts and typical community traffic, Layers 1 and 2 are almost certainly sufficient.

### If you pursue Redis later:
- Add `redis` service to `docker-compose.yml`
- Install **Redis Object Cache** plugin in WordPress
- Configure `WP_REDIS_HOST` in `wp-config.php`
- See repo docs for Docker configuration reference

---

## Performance Testing

Before and after applying these optimizations, test with:

| Tool | URL | What it measures |
|------|-----|-----------------|
| **Google PageSpeed Insights** | pagespeed.web.dev | Core Web Vitals, mobile + desktop scores |
| **GTmetrix** | gtmetrix.com | Waterfall view, load time, page size |
| **WebPageTest** | webpagetest.org | Most detailed — time to first byte, render blocking |

### Target scores (reasonable for this stack):
- PageSpeed mobile: 70+ (good), 85+ (great)
- PageSpeed desktop: 85+ (good), 95+ (great)
- Time to first byte: under 400ms
- Largest Contentful Paint: under 2.5 seconds

---

## Notes

- **Don't optimize prematurely** — get the site built and content-complete first. Premature optimization adds complexity before you know what actually needs fixing.
- **Test after enabling each layer** — especially JS minification, which can occasionally break plugin functionality. Enable, test all forms and interactive elements, then proceed.
- **Clear cache after every content update** — most caching plugins do this automatically for the affected page, but worth knowing.
- **The `/join` page is the most critical to exclude from caching** — payment forms with nonces (security tokens) will break if served from cache.

---

*Created: February 24, 2026 | Revisit at pre-launch phase*
