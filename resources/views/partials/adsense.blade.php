@php
    $adsenseClient = (string) config('adsense.client', '');
    $adsenseSeller = (string) config('adsense.seller_id', '');
    $adsenseEnabled = (bool) config('adsense.enabled', false)
        && preg_match('/^ca-pub-\d+$/', $adsenseClient) === 1
        && preg_match('/^pub-\d+$/', $adsenseSeller) === 1
        && substr($adsenseClient, 3) === $adsenseSeller;
    $currentRoute = request()->route()?->getName();
    $allowedRoute = in_array($currentRoute, config('adsense.allowed_routes', []), true);
    $requestPath = trim(request()->path(), '/');
    $excludedPath = collect(config('adsense.excluded_path_prefixes', []))->contains(
        fn ($prefix) => $requestPath === $prefix || str_starts_with($requestPath, $prefix.'/')
    );
    $shouldLoadAds = $adsenseEnabled && $allowedRoute && ! $excludedPath;
@endphp

@if($adsenseEnabled)
<meta name="google-adsense-account" content="{{ $adsenseClient }}">
@endif

@if($shouldLoadAds)
<script>
(function () {
    'use strict';

    const client = @json($adsenseClient);
    let loaded = false;

    function advertisingAllowed() {
        return window.LamGameConsent?.allows('advertising') === true;
    }

    function loadAdSense() {
        if (loaded || !advertisingAllowed() || document.getElementById('lg-adsense')) return;
        loaded = true;

        const script = document.createElement('script');
        script.id = 'lg-adsense';
        script.async = true;
        script.crossOrigin = 'anonymous';
        script.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' + encodeURIComponent(client);
        script.addEventListener('load', function () {
            window.dispatchEvent(new CustomEvent('lamgame:adsense-loaded'));
        });
        document.head.appendChild(script);
    }

    window.addEventListener('lamgame:consent-updated', function (event) {
        if (event.detail?.advertising) {
            loadAdSense();
        } else if (document.getElementById('lg-adsense')) {
            // Reload once to remove an already-loaded advertising runtime after consent withdrawal.
            window.location.reload();
        }
    });

    loadAdSense();
})();
</script>
@endif
