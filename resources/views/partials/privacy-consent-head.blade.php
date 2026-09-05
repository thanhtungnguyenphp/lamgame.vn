<script>
(function () {
    'use strict';

    const STORAGE_KEY = 'lamgame_privacy_consent_v1';
    const CONSENT_VERSION = 1;
    const defaults = Object.freeze({
        version: CONSENT_VERSION,
        necessary: true,
        analytics: false,
        advertising: false,
        decided: false,
    });

    function normalize(value) {
        if (!value || value.version !== CONSENT_VERSION) return {...defaults};

        return {
            version: CONSENT_VERSION,
            necessary: true,
            analytics: value.analytics === true,
            advertising: value.advertising === true,
            decided: value.decided === true,
        };
    }

    function read() {
        try {
            return normalize(JSON.parse(localStorage.getItem(STORAGE_KEY)));
        } catch (_) {
            return {...defaults};
        }
    }

    function applyGoogleConsent(consent) {
        window.dataLayer = window.dataLayer || [];
        window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
        window.gtag('consent', 'update', {
            analytics_storage: consent.analytics ? 'granted' : 'denied',
            ad_storage: consent.advertising ? 'granted' : 'denied',
            ad_user_data: consent.advertising ? 'granted' : 'denied',
            ad_personalization: consent.advertising ? 'granted' : 'denied',
        });
    }

    let current = read();
    window.dataLayer = window.dataLayer || [];
    window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
    window.gtag('consent', 'default', {
        analytics_storage: 'denied',
        ad_storage: 'denied',
        ad_user_data: 'denied',
        ad_personalization: 'denied',
        wait_for_update: 500,
    });
    applyGoogleConsent(current);

    window.LamGameConsent = {
        get: function () { return {...current}; },
        hasDecision: function () { return current.decided; },
        allows: function (category) { return current[category] === true; },
        save: function (preferences) {
            current = normalize({...preferences, decided: true, version: CONSENT_VERSION});
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(current));
            } catch (_) {}
            applyGoogleConsent(current);
            window.dispatchEvent(new CustomEvent('lamgame:consent-updated', {detail: {...current}}));
            return {...current};
        },
    };
})();
</script>
