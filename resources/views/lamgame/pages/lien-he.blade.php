@extends('layouts.master')

@section('page_title', 'Liên hệ — LamGame.vn')
@section('page_description', 'Liên hệ với LamGame để được hỗ trợ về source game, AI tools, hoặc hợp tác kinh doanh. Phản hồi trong 24h.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ContactPage",
    "name": "Liên hệ LamGame",
    "description": "Trang liên hệ hỗ trợ của LamGame.vn",
    "url": "{{ url('/lien-he') }}",
    "mainEntity": {
        "@type": "Organization",
        "name": "LamGame",
        "email": "salegamevui@gmail.com",
        "telephone": "+84-911-118-300",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "11 Đoàn Văn Bơ, Phường 13",
            "addressLocality": "Quận 4",
            "addressRegion": "TP. Hồ Chí Minh",
            "addressCountry": "VN"
        }
    }
}
</script>
@endpush

@section('content')
<div class="lg-contact">
    {{-- Hero --}}
    <section class="lg-contact__hero">
        <div class="lg-v2-container">
            <h1>Liên hệ với chúng tôi</h1>
            <p>Có câu hỏi về source game, AI tools, hoặc muốn hợp tác? Chúng tôi sẵn sàng hỗ trợ!</p>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="lg-contact__main">
        <div class="lg-v2-container">
            <div class="lg-contact__grid">
                {{-- Contact Form --}}
                <div class="lg-contact__form-wrap">
                    <div class="lg-contact__card">
                        <h2>📬 Gửi tin nhắn</h2>
                        <p class="lg-contact__card-desc">Điền form bên dưới, chúng tôi sẽ phản hồi trong 24h.</p>
                        
                        <form id="contactForm" class="lg-contact__form">
                            @csrf
                            <div class="lg-contact__form-row">
                                <div class="lg-contact__field">
                                    <label for="name">Họ và tên *</label>
                                    <input type="text" id="name" name="name" placeholder="Nguyễn Văn A" required>
                                </div>
                                <div class="lg-contact__field">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" placeholder="email@example.com" required>
                                </div>
                            </div>
                            
                            <div class="lg-contact__form-row">
                                <div class="lg-contact__field">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="tel" id="phone" name="phone" placeholder="0912 345 678">
                                </div>
                                <div class="lg-contact__field">
                                    <label for="subject">Chủ đề *</label>
                                    <select id="subject" name="subject" required>
                                        <option value="">Chọn chủ đề</option>
                                        <option value="source-game">Hỏi về Source Game</option>
                                        <option value="ai-tools">Hỏi về AI Tools</option>
                                        <option value="hop-tac">Hợp tác kinh doanh</option>
                                        <option value="ho-tro">Hỗ trợ kỹ thuật</option>
                                        <option value="khac">Khác</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="lg-contact__field">
                                <label for="message">Nội dung *</label>
                                <textarea id="message" name="message" rows="5" placeholder="Mô tả chi tiết câu hỏi hoặc yêu cầu của bạn..." required></textarea>
                            </div>
                            
                            <button type="submit" class="lg-contact__submit">
                                <span>Gửi tin nhắn</span>
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 2L11 13"/><path d="M22 2L15 22l-4-9-9-4 20-7z"/></svg>
                            </button>
                        </form>
                        
                        <div id="contactResult" class="lg-contact__result" style="display: none;"></div>
                    </div>

                    {{-- FAQ --}}
                    <div class="lg-contact__card lg-contact__faq">
                        <h2>❓ Câu hỏi thường gặp</h2>
                        
                        <div class="lg-contact__faq-list">
                            <details class="lg-contact__faq-item">
                                <summary>Source game có bao gồm hướng dẫn không?</summary>
                                <p>Có! Tất cả source game đều có documentation, video hướng dẫn setup, và support qua Discord trong 30 ngày đầu.</p>
                            </details>
                            
                            <details class="lg-contact__faq-item">
                                <summary>Tôi có thể dùng source cho dự án thương mại không?</summary>
                                <p>Có, license cho phép sử dụng trong dự án thương mại. Bạn có thể publish game lên App Store, Google Play mà không cần chia sẻ revenue.</p>
                            </details>
                            
                            <details class="lg-contact__faq-item">
                                <summary>Thời gian hoàn tiền là bao lâu?</summary>
                                <p>Chúng tôi có chính sách hoàn tiền 7 ngày nếu source không đúng mô tả. Liên hệ support để được hỗ trợ.</p>
                            </details>
                            
                            <details class="lg-contact__faq-item">
                                <summary>AI Tools có miễn phí không?</summary>
                                <p>Có! Tất cả AI Tools (GDD Generator, Name Generator, Story Writer) đều miễn phí sử dụng, không giới hạn số lần.</p>
                            </details>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <aside class="lg-contact__sidebar">
                    {{-- Contact Info --}}
                    <div class="lg-contact__card">
                        <h3>📍 Thông tin liên hệ</h3>
                        
                        <div class="lg-contact__info">
                            <div class="lg-contact__info-item">
                                <div class="lg-contact__info-icon">📧</div>
                                <div>
                                    <strong>Email</strong>
                                    <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a>
                                </div>
                            </div>
                            
                            <div class="lg-contact__info-item">
                                <div class="lg-contact__info-icon">📱</div>
                                <div>
                                    <strong>Hotline</strong>
                                    <a href="tel:0911118300">09.1111.8300</a>
                                </div>
                            </div>
                            
                            <div class="lg-contact__info-item">
                                <div class="lg-contact__info-icon">🏢</div>
                                <div>
                                    <strong>Địa chỉ</strong>
                                    <span>E.Town Central, 11 Đoàn Văn Bơ<br>Quận 4, TP.HCM</span>
                                </div>
                            </div>
                            
                            <div class="lg-contact__info-item">
                                <div class="lg-contact__info-icon">⏰</div>
                                <div>
                                    <strong>Giờ làm việc</strong>
                                    <span>T2-T6: 8:00 - 18:00<br>T7: 9:00 - 12:00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Social --}}
                    <div class="lg-contact__card">
                        <h3>🌐 Kết nối với chúng tôi</h3>
                        <div class="lg-contact__social">
                            <a href="https://www.facebook.com/groups/lamgame" target="_blank" class="lg-contact__social-link lg-contact__social-link--fb">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                <span>Facebook Group</span>
                            </a>
                            <a href="https://www.youtube.com/@lamgamevn" target="_blank" class="lg-contact__social-link lg-contact__social-link--yt">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                <span>YouTube</span>
                            </a>
                            <a href="https://discord.gg/lamgame" target="_blank" class="lg-contact__social-link lg-contact__social-link--dc">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 00-4.885-1.515.074.074 0 00-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 00-5.487 0 12.64 12.64 0 00-.617-1.25.077.077 0 00-.079-.037A19.736 19.736 0 003.677 4.37a.07.07 0 00-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 00.031.057 19.9 19.9 0 005.993 3.03.078.078 0 00.084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 00-.041-.106 13.107 13.107 0 01-1.872-.892.077.077 0 01-.008-.128 10.2 10.2 0 00.372-.292.074.074 0 01.077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 01.078.01c.12.098.246.198.373.292a.077.077 0 01-.006.127 12.299 12.299 0 01-1.873.892.077.077 0 00-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 00.084.028 19.839 19.839 0 006.002-3.03.077.077 0 00.032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 00-.031-.03z"/></svg>
                                <span>Discord</span>
                            </a>
                        </div>
                    </div>

                    {{-- Quick Links --}}
                    <div class="lg-contact__card">
                        <h3>🔗 Liên kết nhanh</h3>
                        <div class="lg-contact__links">
                            <a href="{{ route('lamgame.source-game') }}">🎮 Source Game</a>
                            <a href="{{ route('lamgame.ai-tools') }}">🤖 AI Tools</a>
                            <a href="{{ route('lamgame.blog') }}">📝 Blog & Tutorials</a>
                            <a href="{{ route('forum.index') }}">💬 Forum</a>
                            <a href="/page/chinh-sach-hoan-tien-tranh-chap">📋 Chính sách hoàn tiền</a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
/* ===== CONTACT PAGE V2 — Dark Theme ===== */
.lg-contact {
    --contact-accent: #8B5CF6;
    --contact-accent-hover: #7C3AED;
    --contact-bg: #0D0D1A;
    --contact-card: #161625;
    --contact-border: rgba(139, 92, 246, 0.15);
    --contact-text: #E5E7EB;
    --contact-muted: #9CA3AF;
}

/* Hero */
.lg-contact__hero {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    padding: 4rem 0 3rem;
    text-align: center;
    border-bottom: 1px solid var(--contact-border);
}

.lg-contact__hero h1 {
    font-size: 2.25rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 0.75rem;
}

.lg-contact__hero p {
    font-size: 1.125rem;
    color: var(--contact-muted);
    max-width: 500px;
    margin: 0 auto;
}

/* Main */
.lg-contact__main {
    padding: 3rem 0 4rem;
    background: var(--contact-bg);
}

.lg-contact__grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 2rem;
    align-items: start;
}

/* Card */
.lg-contact__card {
    background: var(--contact-card);
    border: 1px solid var(--contact-border);
    border-radius: 12px;
    padding: 1.75rem;
    margin-bottom: 1.5rem;
}

.lg-contact__card h2,
.lg-contact__card h3 {
    color: #fff;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.lg-contact__card-desc {
    color: var(--contact-muted);
    font-size: 0.9375rem;
    margin-bottom: 1.5rem;
}

/* Form */
.lg-contact__form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.lg-contact__field {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
}

.lg-contact__field label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--contact-text);
    margin-bottom: 0.5rem;
}

.lg-contact__field input,
.lg-contact__field select,
.lg-contact__field textarea {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.9375rem;
    color: #fff;
    transition: all 0.2s;
}

.lg-contact__field input::placeholder,
.lg-contact__field textarea::placeholder {
    color: var(--contact-muted);
}

.lg-contact__field input:focus,
.lg-contact__field select:focus,
.lg-contact__field textarea:focus {
    outline: none;
    border-color: var(--contact-accent);
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15);
}

.lg-contact__field select {
    cursor: pointer;
}

.lg-contact__field select option {
    background: #1a1a2e;
    color: #fff;
}

.lg-contact__submit {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--contact-accent);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.875rem 1.5rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.lg-contact__submit:hover {
    background: var(--contact-accent-hover);
    transform: translateY(-1px);
}

.lg-contact__result {
    margin-top: 1rem;
    padding: 1rem;
    border-radius: 8px;
}

.lg-contact__result.success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #10B981;
}

.lg-contact__result.error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #EF4444;
}

/* FAQ */
.lg-contact__faq-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 1rem;
}

.lg-contact__faq-item {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 8px;
    overflow: hidden;
}

.lg-contact__faq-item summary {
    padding: 1rem;
    font-weight: 500;
    color: var(--contact-text);
    cursor: pointer;
    list-style: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.lg-contact__faq-item summary::-webkit-details-marker {
    display: none;
}

.lg-contact__faq-item summary::after {
    content: '+';
    font-size: 1.25rem;
    color: var(--contact-accent);
    transition: transform 0.2s;
}

.lg-contact__faq-item[open] summary::after {
    transform: rotate(45deg);
}

.lg-contact__faq-item p {
    padding: 0 1rem 1rem;
    color: var(--contact-muted);
    font-size: 0.9375rem;
    line-height: 1.6;
}

/* Sidebar */
.lg-contact__sidebar .lg-contact__card {
    margin-bottom: 1rem;
}

/* Info */
.lg-contact__info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1rem;
}

.lg-contact__info-item {
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
}

.lg-contact__info-icon {
    font-size: 1.25rem;
    width: 2rem;
    text-align: center;
}

.lg-contact__info-item strong {
    display: block;
    color: var(--contact-text);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.lg-contact__info-item span,
.lg-contact__info-item a {
    color: var(--contact-muted);
    font-size: 0.875rem;
    text-decoration: none;
    line-height: 1.5;
}

.lg-contact__info-item a:hover {
    color: var(--contact-accent);
}

/* Social */
.lg-contact__social {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 1rem;
}

.lg-contact__social-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}

.lg-contact__social-link--fb {
    background: rgba(24, 119, 242, 0.1);
    color: #1877F2;
}
.lg-contact__social-link--fb:hover {
    background: rgba(24, 119, 242, 0.2);
}

.lg-contact__social-link--yt {
    background: rgba(255, 0, 0, 0.1);
    color: #FF0000;
}
.lg-contact__social-link--yt:hover {
    background: rgba(255, 0, 0, 0.2);
}

.lg-contact__social-link--dc {
    background: rgba(88, 101, 242, 0.1);
    color: #5865F2;
}
.lg-contact__social-link--dc:hover {
    background: rgba(88, 101, 242, 0.2);
}

/* Quick Links */
.lg-contact__links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 1rem;
}

.lg-contact__links a {
    display: block;
    padding: 0.625rem 0;
    color: var(--contact-muted);
    text-decoration: none;
    font-size: 0.9375rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: color 0.2s;
}

.lg-contact__links a:last-child {
    border-bottom: none;
}

.lg-contact__links a:hover {
    color: var(--contact-accent);
}

/* Responsive */
@media (max-width: 1024px) {
    .lg-contact__grid {
        grid-template-columns: 1fr;
    }
    
    .lg-contact__sidebar {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .lg-contact__sidebar .lg-contact__card {
        margin-bottom: 0;
    }
}

@media (max-width: 640px) {
    .lg-contact__hero {
        padding: 3rem 0 2rem;
    }
    
    .lg-contact__hero h1 {
        font-size: 1.75rem;
    }
    
    .lg-contact__form-row {
        grid-template-columns: 1fr;
    }
    
    .lg-contact__sidebar {
        grid-template-columns: 1fr;
    }
    
    .lg-contact__card {
        padding: 1.25rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const result = document.getElementById('contactResult');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = form.querySelector('.lg-contact__submit');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span>Đang gửi...</span>';
            submitBtn.disabled = true;
            
            try {
                const formData = new FormData(form);
                const response = await fetch('/lien-he', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                result.style.display = 'block';
                if (data.success) {
                    result.className = 'lg-contact__result success';
                    result.innerHTML = '✅ ' + data.message;
                    form.reset();
                } else {
                    result.className = 'lg-contact__result error';
                    let errorMsg = data.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
                    if (data.errors) {
                        errorMsg = Object.values(data.errors).flat().join('<br>');
                    }
                    result.innerHTML = '❌ ' + errorMsg;
                }
            } catch (error) {
                result.style.display = 'block';
                result.className = 'lg-contact__result error';
                result.innerHTML = '❌ Có lỗi xảy ra. Vui lòng thử lại hoặc liên hệ trực tiếp qua email.';
            }
            
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }
});
</script>
@endpush
@endsection
