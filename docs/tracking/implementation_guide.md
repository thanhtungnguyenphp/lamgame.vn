# Google Analytics Tracking Implementation Guide

## Overview

The website now includes comprehensive Google Analytics 4 (GA4) tracking with privacy-compliant settings and custom event tracking for job-related activities, blog engagement, and user interactions.

## Configuration

### Environment Variables

Add these to your `.env` file:

```bash
GOOGLE_ANALYTICS_ENABLED=true
GOOGLE_ANALYTICS_ID=G-WPXBBHC7XJ
GOOGLE_ANALYTICS_ANONYMIZE_IP=true
GOOGLE_ANALYTICS_ALLOW_SIGNALS=false
GOOGLE_ANALYTICS_ENHANCED_ECOMMERCE=true
```

### Config File

The configuration is managed in `config/google_analytics.php` which allows fine-grained control over tracking features.

## Tracked Events

### Job-Related Events

1. **Job View** - Automatically tracked when users view job detail pages
   - Event: `job_view`
   - Parameters: job_id, job_title, company, category

2. **Job Application Modal Open** - When users click "Apply Now"
   - Event: `job_application_modal_open`
   - Parameters: job_id, job_title, company

3. **Job Application Success** - When application is submitted successfully
   - Event: `job_application`
   - Parameters: job_id, job_title, company, application_code

4. **Job Save/Unsave** - When users save/unsave jobs
   - Events: `job_save`, `job_unsave`
   - Parameters: job_id, job_title, company

### Blog Events

1. **Blog View** - Automatically tracked when users view blog posts
   - Event: `blog_view`  
   - Parameters: blog_id, blog_title, blog_category

### General Events

1. **CTA Clicks** - Call-to-action button interactions
   - Event: `cta_click`
   - Parameters: action, category

2. **Form Submissions** - Contact forms, newsletters, etc.
   - Event: `form_submit`
   - Parameters: form_type

3. **User Registration** - New account creation
   - Event: `registration`
   - Parameters: user_type

## JavaScript API

### Global Functions

Available throughout the site:

```javascript
// Generic event tracking
window.trackEvent(eventName, parameters);

// Page view tracking (for SPAs)
window.trackPageView(pageTitle, pagePath);

// Specialized tracking functions
window.trackCTA(action, category);
window.trackJobApplication(jobId, jobTitle, company);
window.trackBlogView(blogId, blogTitle, category);
window.trackFormSubmit(formType);
```

### Usage Examples

```javascript
// Track a custom CTA click
trackCTA('download_portfolio', 'engagement');

// Track a blog view
trackBlogView(123, 'Unity Game Development Tips', 'tutorials');

// Track a form submission
trackFormSubmit('contact_form');
```

## Privacy & GDPR Compliance

- **IP Anonymization**: Enabled by default
- **Google Signals**: Disabled to enhance privacy
- **Secure Cookies**: SameSite=None;Secure flag set
- **No PII Tracking**: Personal information is not tracked in events

## Implementation Details

### Master Layout Integration

The tracking code is conditionally loaded in `resources/views/layouts/master.blade.php`:

- Only loads in production or when explicitly enabled
- Uses configuration from `config/google_analytics.php`
- Provides enhanced tracking functions globally

### Job Detail Page

Enhanced tracking in `public/js/job-detail-modal.js`:

- Tracks modal interactions
- Monitors application success/failure
- Records job save/unsave actions

### Blog Pages

Automatic tracking in blog detail views via meta push sections.

## Testing

### Development Testing

In development, enable tracking by setting:
```bash
GOOGLE_ANALYTICS_ENABLED=true
```

### Verification

1. Use Google Analytics Real-Time reports
2. Check browser console for tracking confirmations
3. Use Google Analytics Debugger extension
4. Use `gtag` debug mode:

```javascript
// Enable debug mode (in browser console)
gtag('config', 'G-WPXBBHC7XJ', {
  debug_mode: true
});
```

## Custom Event Parameters

### Standard Parameters

- `event_category`: Groups related events (jobs, blog, forms, etc.)
- `event_label`: Descriptive text for the event
- `value`: Numeric value when applicable
- `job_id`: Job identifier for job-related events
- `blog_id`: Blog post identifier
- `company`: Company name for job events

### Enhanced E-commerce Parameters

For future implementation:
- `item_id`: Product/service identifier
- `item_name`: Product/service name
- `item_category`: Product category
- `currency`: Currency code
- `value`: Monetary value

## Troubleshooting

### Common Issues

1. **Events not showing in GA**: Check if GA_ENABLED is true and tracking ID is correct
2. **Multiple events firing**: Ensure single page load isn't triggering multiple trackers
3. **AdBlockers**: Some users may have tracking blocked

### Debug Commands

```javascript
// Check if tracking is loaded
typeof gtag !== 'undefined'

// Check current configuration
window.trackEvent('test_event', {test: 'parameter'});

// Get current job ID (job detail pages)
window.getJobId();
```

## Performance Considerations

- Tracking scripts are loaded asynchronously
- Events are batched and sent efficiently by GA4
- Minimal impact on page load speed
- Uses modern GA4 which is more efficient than Universal Analytics

## Next Steps

1. Set up custom audiences in Google Analytics
2. Create conversion goals for job applications
3. Set up custom reports for job engagement metrics
4. Consider adding Facebook Pixel or other analytics tools
5. Implement enhanced e-commerce tracking for premium features