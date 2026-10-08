<style>
    .review-stats-section { margin-bottom: 24px; }
    .rating-summary { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
    .rating-big { font-size: 48px; font-weight: 700; color: #f59e0b; }
    .rating-big small { font-size: 20px; color: #9ca3af; }
    .rating-count { color: #6b7280; }
    .rating-bar { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-size: 13px; color: #6b7280; }
    .rating-bar .bar { flex: 1; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden; }
    .rating-bar .bar-fill { height: 100%; background: #f59e0b; border-radius: 4px; }
    .review-item { border-bottom: 1px solid #e5e7eb; padding: 16px 0; }
    .review-header { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 4px; }
    .review-header strong { color: #1f2937; }
    .verified-badge { background: #dcfce7; color: #16a34a; font-size: 11px; padding: 2px 6px; border-radius: 4px; }
    .review-date { color: #9ca3af; font-size: 12px; margin-left: auto; }
    .review-stars { color: #f59e0b; margin-bottom: 4px; }
    .review-title { font-weight: 600; margin-bottom: 4px; }
    .review-pros { color: #16a34a; font-size: 13px; margin-top: 4px; }
    .review-cons { color: #dc2626; font-size: 13px; margin-top: 2px; }
    .no-reviews { color: #9ca3af; text-align: center; padding: 32px 0; }
    .review-form { background: #f9fafb; padding: 20px; border-radius: 8px; margin-top: 24px; }
    .review-form h3 { margin-bottom: 12px; }
    .review-form label { display: block; font-weight: 500; margin-bottom: 4px; margin-top: 12px; font-size: 14px; }
    .review-form input, .review-form textarea, .review-form select { width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
    .review-form textarea { resize: vertical; min-height: 80px; }
    .review-form button { margin-top: 16px; padding: 10px 24px; background: #2563eb; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
    .review-form button:hover { background: #1d4ed8; }
</style>

@auth('customer')
<div class="review-form">
    <h3>{{ __('lamgame.detail.write_review') }}</h3>
    <div id="review-message"></div>
    <form id="review-form" onsubmit="event.preventDefault(); submitReview({{ $productId }})">
        <label>{{ __('lamgame.detail.rating_label') }}</label>
        <select name="rating" required>
            <option value="">{{ __('lamgame.detail.choose_stars') }}</option>
            <option value="5">{{ __('lamgame.detail.star5') }}</option>
            <option value="4">{{ __('lamgame.detail.star4') }}</option>
            <option value="3">{{ __('lamgame.detail.star3') }}</option>
            <option value="2">{{ __('lamgame.detail.star2') }}</option>
            <option value="1">{{ __('lamgame.detail.star1') }}</option>
        </select>
        <label>{{ __('lamgame.detail.title_label') }}</label>
        <input type="text" name="title" maxlength="255" placeholder="{{ __('lamgame.detail.title_ph') }}">
        <label>{{ __('lamgame.detail.content_label') }}</label>
        <textarea name="content" required maxlength="5000" placeholder="{{ __('lamgame.detail.content_ph') }}"></textarea>
        <label>{{ __('lamgame.detail.pros_label') }}</label>
        <input type="text" name="pros" maxlength="1000" placeholder="{{ __('lamgame.detail.pros_ph') }}">
        <label>{{ __('lamgame.detail.cons_label') }}</label>
        <input type="text" name="cons" maxlength="1000" placeholder="{{ __('lamgame.detail.cons_ph') }}">
        <button type="submit">{{ __('lamgame.detail.submit_review') }}</button>
    </form>
</div>
@else
<div class="review-form" style="text-align:center">
    <p>{!! __('lamgame.detail.login_to_review', ['login' => '<a href="'.route('shop.customer.session.index').'">'.__('lamgame.detail.login_word').'</a>']) !!}</p>
</div>
@endauth
