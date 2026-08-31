# Analytics & Tracking Setup Guide

## Current Status

| Tool | Status | Config |
|------|--------|--------|
| Google Analytics 4 | ✅ Active | `G-2RC7JK6Y8M` |
| Facebook Pixel | ⏳ Ready | Needs Pixel ID |
| Microsoft Clarity | ⏳ Ready | Needs Project ID |

---

## 1. Google Analytics 4 (Already Setup)

**Status:** ✅ Active

**Tracking ID:** `G-2RC7JK6Y8M`

**Features enabled:**
- Page views
- Custom events
- Anonymous IP
- CTA tracking
- Form submission tracking
- Job application tracking
- Blog view tracking

**Access:** https://analytics.google.com

---

## 2. Facebook Pixel Setup

**Purpose:** Retargeting ads on Facebook/Instagram

### Step 1: Create Pixel
1. Go to https://business.facebook.com/events_manager
2. Click "Connect Data Sources"
3. Select "Web"
4. Choose "Facebook Pixel"
5. Name it: "LamGame Pixel"
6. Copy the Pixel ID (numbers only, e.g., `123456789012345`)

### Step 2: Enable in .env
```bash
FACEBOOK_PIXEL_ENABLED=true
FACEBOOK_PIXEL_ID=YOUR_PIXEL_ID_HERE
```

### Step 3: Clear cache
```bash
docker exec lg-php php artisan config:clear
```

### Step 4: Verify
1. Install "Facebook Pixel Helper" Chrome extension
2. Visit https://lamgame.vn/hire
3. Extension should show pixel firing

### Events Tracked Automatically:
- `PageView` - Every page load
- `Lead` - Hire form submission
- `Contact` - Contact form submission

### Custom Events (call from JS):
```javascript
// Track a lead
window.trackFBLead('Hire Form');

// Track contact
window.trackFBContact();

// Custom event
window.trackFBEvent('ViewContent', { content_name: 'Portfolio' });
```

---

## 3. Microsoft Clarity Setup (FREE Heatmaps)

**Purpose:** Free heatmaps, session recordings, scroll depth

### Step 1: Create Account
1. Go to https://clarity.microsoft.com
2. Sign up with Microsoft account
3. Click "Add new project"
4. Enter: `lamgame.vn`
5. Copy the Project ID (e.g., `abc123xyz`)

### Step 2: Enable in .env
```bash
CLARITY_ENABLED=true
CLARITY_PROJECT_ID=YOUR_PROJECT_ID_HERE
```

### Step 3: Clear cache
```bash
docker exec lg-php php artisan config:clear
```

### Step 4: Verify
1. Go to Clarity dashboard
2. Wait 24-48 hours for data
3. View heatmaps and recordings

### Features:
- **Heatmaps** - See where users click
- **Session Recordings** - Watch user sessions
- **Scroll Depth** - How far users scroll
- **Dead Clicks** - Clicks that do nothing
- **Rage Clicks** - Frustrated clicking
- **Insights** - AI-powered suggestions

---

## 4. Event Tracking Reference

### Google Analytics Events

```javascript
// Track CTA click
window.trackCTA('Start Project', 'hire_page');

// Track form submission
window.trackFormSubmit('hire_form');

// Track page view
window.trackPageView('Hire Page', '/hire');

// Custom event
window.trackEvent('custom_event', {
  event_category: 'engagement',
  event_label: 'action_name'
});
```

### Facebook Pixel Events

```javascript
// Standard events
window.trackFBEvent('Lead');
window.trackFBEvent('Contact');
window.trackFBEvent('ViewContent', { content_name: 'Portfolio' });
window.trackFBEvent('CompleteRegistration');

// Custom events
window.trackFBEvent('HireFormStart');
window.trackFBEvent('CalendlyClick');
```

---

## 5. GDPR Compliance

### Cookie Consent
Consider adding a cookie consent banner. Current tracking:
- GA4: Anonymous IP enabled
- FB Pixel: Only loads if enabled
- Clarity: Privacy-focused by default

### Recommended: Add Cookie Banner
```html
<!-- Simple cookie consent (add to footer) -->
<div id="cookie-consent" style="display:none; position:fixed; bottom:0; left:0; right:0; background:#1E1E30; padding:16px; z-index:9999;">
  <div style="max-width:1200px; margin:0 auto; display:flex; justify-content:space-between; align-items:center;">
    <p style="margin:0; color:#F0F0F5; font-size:14px;">
      We use cookies to improve your experience. 
      <a href="/chinh-sach-bao-mat" style="color:#8B5CF6;">Learn more</a>
    </p>
    <button onclick="acceptCookies()" style="background:#8B5CF6; color:#fff; border:none; padding:8px 16px; border-radius:6px; cursor:pointer;">
      Accept
    </button>
  </div>
</div>
<script>
if (!localStorage.getItem('cookies_accepted')) {
  document.getElementById('cookie-consent').style.display = 'block';
}
function acceptCookies() {
  localStorage.setItem('cookies_accepted', 'true');
  document.getElementById('cookie-consent').style.display = 'none';
}
</script>
```

---

## 6. Conversion Tracking Goals

### Priority Conversions to Track

| Conversion | GA4 Event | FB Event | Priority |
|------------|-----------|----------|----------|
| Hire form submit | `form_submit` | `Lead` | 🔴 High |
| Contact form | `form_submit` | `Contact` | 🔴 High |
| Calendly click | `cta_click` | `Schedule` | 🟡 Medium |
| Portfolio view | `page_view` | `ViewContent` | 🟢 Low |
| Source game purchase | `purchase` | `Purchase` | 🔴 High |

### Setting up GA4 Conversions
1. Go to GA4 > Admin > Events
2. Find your event (e.g., `form_submit`)
3. Toggle "Mark as conversion"

### Setting up FB Conversions
1. Go to Events Manager > Custom Conversions
2. Create conversion for `Lead` event
3. Set optimization goal

---

## 7. Dashboards & Reports

### Weekly Metrics to Check

**Traffic:**
- Total sessions
- New vs returning users
- Traffic sources
- Top pages

**Engagement:**
- Avg. session duration
- Pages per session
- Bounce rate
- Scroll depth (Clarity)

**Conversions:**
- Form submissions
- Calendly bookings
- Source purchases

### Monthly Reports
Create a simple spreadsheet tracking:
```
| Week | Sessions | New Users | Form Submits | Revenue |
|------|----------|-----------|--------------|---------|
| W1   | 500      | 400       | 3            | $0      |
| W2   | 600      | 480       | 5            | $500    |
```

---

## 8. Troubleshooting

### Analytics not showing data?
1. Check browser console for errors
2. Verify config: `docker exec lg-php php artisan tinker --execute="echo config('google_analytics.tracking_id');"`
3. Clear cache: `docker exec lg-php php artisan config:clear`
4. Check ad blockers

### Facebook Pixel not firing?
1. Install FB Pixel Helper extension
2. Check if enabled in .env
3. Verify Pixel ID is correct
4. Check for console errors

### Clarity not recording?
1. Wait 24-48 hours after setup
2. Check project ID in .env
3. Try different browser/incognito
4. Check Clarity dashboard for blocked pages

---

*Last updated: 2026-08-31*
