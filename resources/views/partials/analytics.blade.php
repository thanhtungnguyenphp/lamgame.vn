@if(config('google_analytics.enabled') && config('google_analytics.tracking_id'))
@php
    $gaId = config('google_analytics.tracking_id');
    $anonymizeIp = config('google_analytics.anonymize_ip') ? 'true' : 'false';
    $allowSignals = config('google_analytics.allow_google_signals') ? 'true' : 'false';
@endphp
<script>
(function () {
    'use strict';

    const gaId = @json($gaId);
    let loaded = false;

    function analyticsAllowed() {
        return window.LamGameConsent?.allows('analytics') === true;
    }

    function loadAnalytics() {
        if (loaded || !analyticsAllowed()) return;
        loaded = true;
        window.__lamgameAnalyticsLoaded = true;

        const script = document.createElement('script');
        script.async = true;
        script.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(gaId);
        script.id = 'lg-google-analytics';
        document.head.appendChild(script);

        window.gtag('js', new Date());
        window.gtag('config', gaId, {
            page_title: document.title,
            page_location: window.location.href,
            anonymize_ip: {{ $anonymizeIp }},
            allow_google_signals: {{ $allowSignals }} && window.LamGameConsent.allows('advertising'),
            cookie_flags: 'SameSite=None;Secure',
        });
    }

    window.addEventListener('lamgame:consent-updated', function (event) {
        if (event.detail?.analytics) loadAnalytics();
    });
    loadAnalytics();

    window.trackEvent = function (eventName, parameters = {}) {
        if (!loaded || !analyticsAllowed()) return false;
        window.gtag('event', eventName, parameters);
        return true;
    };

    window.trackRevenueEvent = function (eventName, parameters = {}, dedupKey = null) {
        if (!loaded || !analyticsAllowed()) return false;

        const blockedKeys = ['email', 'phone', 'name', 'full_name', 'description', 'prompt', 'message'];
        const safeParameters = Object.fromEntries(
            Object.entries(parameters).filter(([key]) => !blockedKeys.includes(key.toLowerCase()))
        );

        if (dedupKey) {
            const storageKey = 'lg_event_' + dedupKey;
            if (sessionStorage.getItem(storageKey)) return false;
            sessionStorage.setItem(storageKey, '1');
        }

        return window.trackEvent(eventName, safeParameters);
    };

    window.trackPageView = function (pageTitle, pagePath) {
        if (!loaded || !analyticsAllowed()) return false;
        window.gtag('config', gaId, {page_title: pageTitle, page_path: pagePath});
        return true;
    };

    window.trackCTA = function (action, category = 'engagement') {
        return window.trackEvent('cta_click', {
            event_category: category,
            event_label: action,
            value: 1,
        });
    };

    window.trackJobApplication = function (jobId, jobTitle, company) {
        return window.trackEvent('job_application', {
            event_category: 'jobs',
            event_label: jobTitle,
            job_id: jobId,
            company: company,
            value: 1,
        });
    };

    window.trackBlogView = function (blogId, blogTitle, category) {
        return window.trackEvent('blog_view', {
            event_category: 'blog',
            event_label: blogTitle,
            blog_id: blogId,
            blog_category: category,
            value: 1,
        });
    };

    window.trackFormSubmit = function (formType) {
        return window.trackEvent('form_submit', {
            event_category: 'forms',
            event_label: formType,
            value: 1,
        });
    };
})();
</script>
@endif
