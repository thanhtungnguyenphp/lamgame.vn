<x-layouts.account>
    <x-slot:title>
        Thông tin cá nhân
    </x-slot>

    <div class="profile-container">
        <!-- Header -->
        <div class="profile-header">
            <div>
                <h1 class="profile-title">{{ __('lamgame.profile.title') }}</h1>
                <p class="profile-subtitle">{{ __('lamgame.profile.subtitle') }}</p>
            </div>
            <a href="{{ route('shop.customers.account.profile.edit') }}" class="btn-edit-profile">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                {{ __('lamgame.profile.edit') }}
            </a>
        </div>

        <!-- Profile Info Card -->
        <div class="profile-card">
            <div class="profile-section">
                <h2 class="section-title">{{ __('lamgame.profile.basic_info') }}</h2>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label class="info-label">{{ __('lamgame.profile.first_name') }}</label>
                        <p class="info-value">{{ $customer->first_name }}</p>
                    </div>

                    <div class="info-item">
                        <label class="info-label">{{ __('lamgame.profile.last_name') }}</label>
                        <p class="info-value">{{ $customer->last_name }}</p>
                    </div>

                    <div class="info-item">
                        <label class="info-label">{{ __('lamgame.profile.gender') }}</label>
                        <p class="info-value">
                            @if($customer->gender === 'Male')
                                {{ __('lamgame.profile.male') }}
                            @elseif($customer->gender === 'Female')
                                {{ __('lamgame.profile.female') }}
                            @else
                                Chưa cập nhật
                            @endif
                        </p>
                    </div>

                    <div class="info-item">
                        <label class="info-label">{{ __('lamgame.profile.dob') }}</label>
                        <p class="info-value">{{ $customer->date_of_birth ? date('d/m/Y', strtotime($customer->date_of_birth)) : __('lamgame.profile.not_updated') }}</p>
                    </div>
                </div>
            </div>

            <div class="profile-divider"></div>

            <div class="profile-section">
                <h2 class="section-title">{{ __('lamgame.profile.contact_info') }}</h2>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label class="info-label">{{ __('lamgame.profile.email') }}</label>
                        <p class="info-value">{{ $customer->email }}</p>
                    </div>

                    <div class="info-item">
                        <label class="info-label">{{ __('lamgame.profile.phone') }}</label>
                        <p class="info-value">{{ $customer->phone ?? __('lamgame.profile.not_updated') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="danger-zone">
            <div class="danger-zone-content">
                <div>
                    <h3 class="danger-title">{{ __('lamgame.profile.danger_title') }}</h3>
                    <p class="danger-description">{{ __('lamgame.profile.danger_desc') }}</p>
                </div>

                <button type="button" class="btn-danger" onclick="document.getElementById('deleteAccountModal').style.display='flex'">
                    {{ __('lamgame.profile.danger_title') }}
                </button>

                <!-- Delete account modal (thuần, không dùng Vue component) -->
                <div id="deleteAccountModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.6);align-items:center;justify-content:center;padding:1rem">
                    <div style="background:#111827;border:1px solid rgba(124,92,255,.2);border-radius:14px;max-width:440px;width:100%;padding:1.75rem;color:#F5F7FA">
                        <h2 style="font-size:1.2rem;font-weight:700;margin-bottom:0.75rem">{{ __('lamgame.profile.confirm_delete') }}</h2>
                        <p style="color:#B7C0D1;margin-bottom:1rem;font-size:0.9rem">{{ __('lamgame.profile.confirm_desc') }}</p>
                        <form method="POST" action="{{ route('shop.customers.account.profile.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <input
                                type="password"
                                name="password"
                                required
                                placeholder="{{ __('lamgame.profile.password_ph') }}"
                                style="width:100%;padding:0.75rem;border:1px solid rgba(124,92,255,.2);border-radius:10px;background:#070B14;color:#F5F7FA;margin-bottom:1rem"
                            >
                            <div style="display:flex;gap:0.75rem;justify-content:flex-end">
                                <button type="button" onclick="document.getElementById('deleteAccountModal').style.display='none'" style="padding:0.6rem 1.2rem;border:1px solid rgba(124,92,255,.2);border-radius:10px;background:transparent;color:#B7C0D1;cursor:pointer">{{ __('lamgame.profile.cancel') }}</button>
                                <button type="submit" style="padding:0.6rem 1.2rem;border:none;border-radius:10px;background:#F87171;color:#fff;font-weight:600;cursor:pointer">{{ __('lamgame.profile.confirm_btn') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        :root {
            --pf-surface-2: #111827; --pf-border: rgba(124,92,255,.15);
            --pf-text: #F5F7FA; --pf-text-2: #B7C0D1; --pf-muted: #7A8599;
            --pf-accent: #7C5CFF; --pf-accent-2: #00D1FF; --pf-error: #F87171;
        }
        .profile-container { max-width: 100%; color: var(--pf-text); }
        .profile-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
        }
        .profile-title { font-size: 1.6rem; font-weight: 800; color: var(--pf-text); margin: 0; }
        .profile-subtitle { color: var(--pf-muted); margin: 0.25rem 0 0 0; }
        .btn-edit-profile {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.7rem 1.4rem;
            background: linear-gradient(135deg,var(--pf-accent),var(--pf-accent-2));
            color: #fff; border-radius: 10px; text-decoration: none; font-weight: 600;
            transition: opacity .2s;
        }
        .btn-edit-profile:hover { opacity: .9; }
        .profile-card {
            background: rgba(255,255,255,.03);
            border: 1px solid var(--pf-border);
            border-radius: 14px; padding: 2rem; margin-bottom: 1.5rem;
            text-align: left;
        }
        .profile-section { margin-bottom: 1.5rem; }
        .profile-section:last-child { margin-bottom: 0; }
        .section-title {
            font-size: 1.05rem; font-weight: 700; color: var(--pf-text);
            margin: 0 0 1.25rem 0; text-align: left;
            padding-bottom: 0.6rem; border-bottom: 1px solid var(--pf-border);
            display: flex; align-items: center; gap: 0.5rem;
        }
        .info-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;
        }
        .info-item {
            display: flex; flex-direction: column; gap: 0.4rem;
            background: rgba(255,255,255,.02); border: 1px solid var(--pf-border);
            border-radius: 10px; padding: 0.9rem 1rem;
        }
        .info-label { font-size: 0.8rem; font-weight: 500; color: var(--pf-muted); margin: 0; text-transform: uppercase; letter-spacing: 0.3px; }
        .info-value { font-size: 1rem; font-weight: 600; color: var(--pf-text); margin: 0; }
        .profile-divider { height: 1px; background: var(--pf-border); margin: 2rem 0; }
        .danger-zone {
            background: rgba(248,113,113,.08);
            border: 1px solid rgba(248,113,113,.25);
            border-radius: 14px; padding: 1.5rem;
        }
        .danger-zone-content {
            display: flex; justify-content: space-between; align-items: center;
            gap: 1rem; flex-wrap: wrap;
        }
        .danger-title { font-size: 1rem; font-weight: 700; color: var(--pf-error); margin: 0 0 0.25rem 0; }
        .danger-description { font-size: 0.85rem; color: var(--pf-text-2); margin: 0; }
        .btn-danger {
            padding: 0.6rem 1.25rem; background: transparent; color: var(--pf-error);
            border: 1px solid var(--pf-error); border-radius: 10px; font-weight: 600; cursor: pointer;
            transition: all 0.2s;
        }
        .btn-danger:hover { background: var(--pf-error); color: #fff; }
        @media (max-width: 768px) {
            .profile-header { flex-direction: column; align-items: flex-start; }
            .profile-title { font-size: 1.4rem; }
            .profile-card { padding: 1.5rem; }
            .info-grid { grid-template-columns: 1fr; gap: 1rem; }
            .danger-zone-content { flex-direction: column; align-items: flex-start; }
            .btn-danger { width: 100%; }
        }
    </style>
    @endpush
</x-layouts.account>
