{{-- Shared form partial for create/edit --}}
@if($errors->any())
<div class="mt-4 rounded bg-red-50 p-4 text-sm text-red-600">
    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<div class="mt-4 flex gap-4 max-xl:flex-wrap">
    {{-- Left Column --}}
    <div class="flex flex-1 flex-col gap-4 max-xl:flex-auto">

        {{-- Basic Info --}}
        <div class="rounded bg-white p-4 shadow dark:bg-gray-900">
            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Thông tin cơ bản</p>

            <div class="mb-4">
                <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Tên trang *</label>
                <input type="text" name="name" value="{{ old('name', $page->name ?? '') }}" required
                    class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
            </div>

            <div class="mb-4">
                <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Slug (URL)</label>
                <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" placeholder="Tự tạo từ tên nếu để trống"
                    class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
                @if($page)<p class="mt-1 text-xs text-gray-500">URL: {{ config('app.url') }}/p/{{ $page->slug }}</p>@endif
            </div>

            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Template *</label>
                    <select name="template" required class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
                        @foreach($templates as $key => $label)
                            <option value="{{ $key }}" {{ old('template', $page->template ?? 'general') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Trạng thái</label>
                    <select name="status" class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
                        <option value="0" {{ old('status', $page->status ?? 0) == 0 ? 'selected' : '' }}>Nháp</option>
                        <option value="1" {{ old('status', $page->status ?? 0) == 1 ? 'selected' : '' }}>Xuất bản</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Hero Section --}}
        <div class="rounded bg-white p-4 shadow dark:bg-gray-900">
            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Hero Section</p>

            <div class="mb-4">
                <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Tiêu đề Hero</label>
                <input type="text" name="hero_title" value="{{ old('hero_title', $page->hero_title ?? '') }}"
                    class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
            </div>

            <div class="mb-4">
                <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Phụ đề Hero</label>
                <input type="text" name="hero_subtitle" value="{{ old('hero_subtitle', $page->hero_subtitle ?? '') }}"
                    class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
            </div>

            <div class="flex gap-4 mb-4">
                <div class="flex-1">
                    <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">CTA Text</label>
                    <input type="text" name="hero_cta_text" value="{{ old('hero_cta_text', $page->hero_cta_text ?? '') }}" placeholder="Ví dụ: Tham gia ngay"
                        class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
                </div>
                <div class="flex-1">
                    <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">CTA URL</label>
                    <input type="text" name="hero_cta_url" value="{{ old('hero_cta_url', $page->hero_cta_url ?? '') }}" placeholder="https://..."
                        class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Ảnh nền Hero</label>
                    <input type="file" name="hero_bg_image" accept="image/*"
                        class="w-full rounded-md border px-3 py-1.5 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
                    @if($page && $page->hero_bg_image_url)
                        <img src="{{ $page->hero_bg_image_url }}" alt="Hero BG" class="mt-2 max-w-[200px] rounded-lg">
                    @endif
                </div>
                <div class="w-32">
                    <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Màu nền</label>
                    <input type="color" name="hero_bg_color" value="{{ old('hero_bg_color', $page->hero_bg_color ?? '#6a4c93') }}"
                        class="h-10 w-full cursor-pointer rounded-md border">
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="rounded bg-white p-4 shadow dark:bg-gray-900">
            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Nội dung chính</p>
            <textarea name="description" id="description" rows="15"
                class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">{{ old('description', $page->description ?? '') }}</textarea>
        </div>

        {{-- Sections JSON --}}
        <div class="rounded bg-white p-4 shadow dark:bg-gray-900">
            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Content Blocks (JSON)</p>
            <textarea name="sections" rows="8" placeholder='[{"type":"text","title":"Giới thiệu","content":"<p>Nội dung...</p>","bg":true}]'
                class="w-full rounded-md border px-3 py-2 font-mono text-xs text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">{{ old('sections', $page && $page->sections ? json_encode($page->sections, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '') }}</textarea>
            <p class="mt-1 text-xs text-gray-500">Types: <code>text</code>, <code>image-text</code>, <code>cards</code>, <code>cta</code></p>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="flex w-[360px] max-w-full flex-col gap-4">

        {{-- Schedule --}}
        <div class="rounded bg-white p-4 shadow dark:bg-gray-900">
            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">Lên lịch</p>
            <div class="mb-4">
                <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Bắt đầu</label>
                <input type="datetime-local" name="start_at" value="{{ old('start_at', $page && $page->start_at ? $page->start_at->format('Y-m-d\TH:i') : '') }}"
                    class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Kết thúc</label>
                <input type="datetime-local" name="end_at" value="{{ old('end_at', $page && $page->end_at ? $page->end_at->format('Y-m-d\TH:i') : '') }}"
                    class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
            </div>
        </div>

        {{-- SEO --}}
        <div class="rounded bg-white p-4 shadow dark:bg-gray-900">
            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">SEO</p>
            <div class="mb-4">
                <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}" maxlength="70"
                    class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
            </div>
            <div class="mb-4">
                <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Meta Description</label>
                <textarea name="meta_description" rows="3" maxlength="160"
                    class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">Meta Keywords</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}"
                    class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">OG Image</label>
                <input type="file" name="og_image" accept="image/*"
                    class="w-full rounded-md border px-3 py-1.5 text-sm text-gray-600 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300">
                @if($page && $page->og_image_url)
                    <img src="{{ $page->og_image_url }}" alt="OG" class="mt-2 max-w-full rounded-lg">
                @endif
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="primary-button w-full justify-center">
            {{ $page ? 'Cập nhật' : 'Tạo Landing Page' }}
        </button>
    </div>
</div>
