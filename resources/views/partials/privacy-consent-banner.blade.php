<div id="lg-privacy-consent" class="lg-consent" hidden aria-live="polite">
    <div class="lg-consent__panel" role="dialog" aria-modal="true" aria-labelledby="lg-consent-title">
        <div>
            <h2 id="lg-consent-title">Quyền riêng tư và cookie</h2>
            <p>LamGame dùng cookie thiết yếu để vận hành website. Analytics và quảng cáo chỉ được bật khi bạn đồng ý.</p>
            <a href="{{ url('/chinh-sach-bao-mat') }}">Xem chính sách bảo mật</a>
        </div>

        <div id="lg-consent-options" class="lg-consent__options" hidden>
            <label><input type="checkbox" checked disabled> Cookie thiết yếu <span>Luôn bật</span></label>
            <label><input id="lg-consent-analytics" type="checkbox"> Analytics</label>
            <label><input id="lg-consent-advertising" type="checkbox"> Quảng cáo cá nhân hóa/đo lường</label>
        </div>

        <div class="lg-consent__actions">
            <button type="button" id="lg-consent-reject">Từ chối không thiết yếu</button>
            <button type="button" id="lg-consent-customize">Tùy chọn</button>
            <button type="button" id="lg-consent-save" hidden>Lưu lựa chọn</button>
            <button type="button" id="lg-consent-accept" class="lg-consent__primary">Chấp nhận tất cả</button>
        </div>
    </div>
</div>

<style>
.lg-consent{position:fixed;inset:0;z-index:100000;display:flex;align-items:flex-end;justify-content:center;padding:16px;background:rgba(3,7,18,.58)}
.lg-consent[hidden]{display:none}
.lg-consent__panel{width:min(760px,100%);padding:20px;border:1px solid rgba(124,92,255,.35);border-radius:16px;background:#111827;color:#f8fafc;box-shadow:0 20px 60px rgba(0,0,0,.45);font-family:Inter,system-ui,sans-serif}
.lg-consent h2{margin:0 0 8px;font-size:1.2rem}.lg-consent p{margin:0 0 8px;color:#cbd5e1;line-height:1.55}.lg-consent a{color:#a78bfa}
.lg-consent__options{display:grid;gap:8px;margin:16px 0;padding:12px;border-radius:10px;background:#0b1220}.lg-consent__options label{display:flex;align-items:center;gap:8px}.lg-consent__options span{margin-left:auto;color:#94a3b8;font-size:.8rem}
.lg-consent__actions{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:8px;margin-top:16px}.lg-consent button{padding:10px 14px;border:1px solid #475569;border-radius:9px;background:#1e293b;color:#f8fafc;cursor:pointer}.lg-consent button:hover{border-color:#a78bfa}.lg-consent .lg-consent__primary{border-color:#7c3aed;background:#7c3aed}
@media(max-width:640px){.lg-consent{padding:8px}.lg-consent__panel{padding:16px}.lg-consent__actions{display:grid;grid-template-columns:1fr 1fr}.lg-consent button{width:100%}}
</style>

<script>
(function () {
    'use strict';

    function initConsentBanner() {
        const api = window.LamGameConsent;
        const root = document.getElementById('lg-privacy-consent');
        if (!api || !root) return;

        const options = document.getElementById('lg-consent-options');
        const analytics = document.getElementById('lg-consent-analytics');
        const advertising = document.getElementById('lg-consent-advertising');
        const save = document.getElementById('lg-consent-save');
        const customize = document.getElementById('lg-consent-customize');

        function populate() {
            const current = api.get();
            analytics.checked = current.analytics;
            advertising.checked = current.advertising;
        }

        function open(showOptions) {
            populate();
            root.hidden = false;
            options.hidden = !showOptions;
            save.hidden = !showOptions;
            customize.hidden = showOptions;
        }

        function close() {
            root.hidden = true;
        }

        document.getElementById('lg-consent-accept').addEventListener('click', function () {
            api.save({analytics: true, advertising: true});
            close();
        });

        document.getElementById('lg-consent-reject').addEventListener('click', function () {
            api.save({analytics: false, advertising: false});
            close();
        });

        customize.addEventListener('click', function () { open(true); });
        save.addEventListener('click', function () {
            api.save({analytics: analytics.checked, advertising: advertising.checked});
            close();
        });

        window.openPrivacyPreferences = function () { open(true); };
        if (!api.hasDecision()) open(false);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initConsentBanner);
    } else {
        initConsentBanner();
    }
})();
</script>
