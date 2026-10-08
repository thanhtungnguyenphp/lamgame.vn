@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)

@push('meta')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": "LamGame.vn - Thuê Team Dev",
    "description": "{{ $page_description }}",
    "url": "{{ url()->current() }}",
    "areaServed": "VN",
    "serviceType": ["Game Development", "Web Development", "Mobile App Development", "AI Solutions"]
}
</script>
@endpush

@push('styles')
<style>
    .hire-hero { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); color: #fff; padding: 80px 0 60px; text-align: center; }
    .hire-hero h1 { font-size: 2.5rem; margin-bottom: 16px; }
    .hire-hero p { font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto; }
    .services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px; padding: 60px 0; }
    .service-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px 24px; text-align: center; transition: box-shadow 0.2s; }
    .service-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
    .service-icon { font-size: 48px; margin-bottom: 16px; }
    .service-card h3 { font-size: 1.2rem; margin-bottom: 8px; color: #1f2937; }
    .service-card p { color: #6b7280; font-size: 14px; line-height: 1.6; }
    .process-section { background: #f9fafb; padding: 60px 0; }
    .process-steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 32px; margin-top: 32px; }
    .step { text-align: center; }
    .step-num { width: 48px; height: 48px; background: #2563eb; color: #fff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem; margin-bottom: 12px; }
    .step h4 { margin-bottom: 8px; }
    .step p { color: #6b7280; font-size: 14px; }
    .quote-section { padding: 60px 0; max-width: 640px; margin: 0 auto; }
    .quote-section h2 { text-align: center; margin-bottom: 32px; }
    .quote-form label { display: block; font-weight: 500; margin-bottom: 4px; margin-top: 16px; }
    .quote-form input, .quote-form select, .quote-form textarea { width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; }
    .quote-form textarea { resize: vertical; min-height: 120px; }
    .quote-form button { margin-top: 24px; width: 100%; padding: 14px; background: #2563eb; color: #fff; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
    .quote-form button:hover { background: #1d4ed8; }
    .quote-form button:disabled { opacity: 0.6; cursor: not-allowed; }
    .section-title { text-align: center; font-size: 1.8rem; margin-bottom: 8px; }
    .section-subtitle { text-align: center; color: #6b7280; margin-bottom: 32px; }
</style>
@endpush

@section('content')
    <section class="hire-hero">
        <div class="container">
            <h1>{{ __('lamgame.hire.hero_title') }}</h1>
            <p>{{ __('lamgame.hire.hero_sub') }}</p>
        </div>
    </section>

    <section class="section-content">
        <div class="container">
            <h2 class="section-title">{{ __('lamgame.hire.services') }}</h2>
            <p class="section-subtitle">{{ __('lamgame.hire.services_sub') }}</p>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">🎮</div>
                    <h3>Game Development</h3>
                    <p>{{ __('lamgame.hire.svc_game_d') }}</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">🌐</div>
                    <h3>Web Development</h3>
                    <p>{{ __('lamgame.hire.svc_web_d') }}</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">📱</div>
                    <h3>Mobile App</h3>
                    <p>{{ __('lamgame.hire.svc_app_d') }}</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">🤖</div>
                    <h3>AI Solutions</h3>
                    <p>{{ __('lamgame.hire.svc_ai_d') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="process-section">
        <div class="container">
            <h2 class="section-title">{{ __('lamgame.hire.process') }}</h2>
            <div class="process-steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <h4>{{ __('lamgame.hire.step1') }}</h4>
                    <p>{{ __('lamgame.hire.step1_d') }}</p>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <h4>{{ __('lamgame.hire.step2') }}</h4>
                    <p>{{ __('lamgame.hire.step2_d') }}</p>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <h4>{{ __('lamgame.hire.step3') }}</h4>
                    <p>{{ __('lamgame.hire.step3_d') }}</p>
                </div>
                <div class="step">
                    <div class="step-num">4</div>
                    <h4>{{ __('lamgame.hire.step4') }}</h4>
                    <p>{{ __('lamgame.hire.step4_d') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-content">
        <div class="container">
            <div class="quote-section">
                <h2>{{ __('lamgame.hire.quote') }}</h2>
                <div id="quote-message"></div>
                <form class="quote-form" id="hireForm" onsubmit="event.preventDefault(); submitHireForm()">
                    <label>{{ __('lamgame.hire.f_name') }}</label>
                    <input type="text" name="name" required maxlength="100">

                    <label>Email *</label>
                    <input type="email" name="email" required maxlength="255">

                    <label>{{ __('lamgame.hire.f_phone') }}</label>
                    <input type="tel" name="phone" maxlength="20">

                    <label>{{ __('lamgame.hire.f_company') }}</label>
                    <input type="text" name="company" maxlength="255">

                    <label>{{ __('lamgame.hire.f_type') }}</label>
                    <select name="project_type" required>
                        <option value="">{{ __('lamgame.hire.choose_type') }}</option>
                        <option value="game">🎮 Game Development</option>
                        <option value="web">🌐 Web Development</option>
                        <option value="app">📱 Mobile App</option>
                        <option value="ai">🤖 AI Solutions</option>
                        <option value="other">{{ __('lamgame.hire.type_other') }}</option>
                    </select>

                    <label>{{ __('lamgame.hire.f_budget') }}</label>
                    <select name="budget_range">
                        <option value="">{{ __('lamgame.hire.budget_none') }}</option>
                        <option value="< 10M">{{ __('lamgame.hire.budget_1') }}</option>
                        <option value="10M - 50M">{{ __('lamgame.hire.budget_2') }}</option>
                        <option value="50M - 200M">{{ __('lamgame.hire.budget_3') }}</option>
                        <option value="> 200M">{{ __('lamgame.hire.budget_4') }}</option>
                    </select>

                    <label>{{ __('lamgame.hire.f_desc') }}</label>
                    <textarea name="description" required maxlength="5000" placeholder="{{ __('lamgame.hire.f_desc_ph') }}"></textarea>

                    <button type="submit" id="hireSubmitBtn">{{ __('lamgame.hire.submit') }}</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
const LG_I18N = { sending: @json(__('lamgame.hire.sending')), submit: @json(__('lamgame.hire.submit')), error: @json(__('lamgame.hire.error')), errorRetry: @json(__('lamgame.hire.error_retry')) };
function submitHireForm() {
    const form = document.getElementById('hireForm');
    const btn = document.getElementById('hireSubmitBtn');
    const msg = document.getElementById('quote-message');
    const data = Object.fromEntries(new FormData(form));

    btn.disabled = true;
    btn.textContent = LG_I18N.sending;

    fetch('/api/v1/hire-request', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(d => {
        btn.disabled = false;
        btn.textContent = LG_I18N.submit;
        if (d.status === 'success') {
            msg.innerHTML = '<div style="background:#f0fdf4;color:#16a34a;padding:16px;border-radius:8px;margin-bottom:16px;text-align:center">' + d.message + '</div>';
            form.reset();
        } else {
            const errors = d.errors ? Object.values(d.errors).flat().join('<br>') : (d.message || LG_I18N.error);
            msg.innerHTML = '<div style="background:#fef2f2;color:#dc2626;padding:16px;border-radius:8px;margin-bottom:16px">' + errors + '</div>';
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.textContent = LG_I18N.submit;
        msg.innerHTML = '<div style="background:#fef2f2;color:#dc2626;padding:16px;border-radius:8px;margin-bottom:16px"'+LG_I18N.errorRetry+'</div>';
    });
}
</script>
@endpush
