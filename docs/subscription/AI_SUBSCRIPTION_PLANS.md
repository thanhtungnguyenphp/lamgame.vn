# AI Tools Subscription Plans — LamGame.vn

**Cập nhật:** 2026-04-02

---

## 1. Tổng quan

Subscription system phục vụ bán gói AI tools cho game developer. User mua gói qua PayPal → nhận quota AI features → sử dụng qua API.

AI service phát triển riêng, tích hợp vào LamGame qua API. Subscription system quản lý quota và billing.

---

## 2. Plans

### Free ($0/tháng)

Mục đích: Cho user trải nghiệm AI concept → thấy giá trị → upgrade.

| Feature | Quota |
|---------|-------|
| AI Game Concept | 3/tháng |
| AI Code Generate | ❌ |
| AI Debug | ❌ |
| AI Unit Test | ❌ |
| AI Asset Generate | ❌ |
| AI Code Review | ❌ |
| AI Model | GPT-4o mini |
| Export project | ❌ |
| Chat history | 7 ngày |
| Priority queue | ❌ |

### Pro ($9/tháng)

Mục đích: Indie dev / freelancer — đủ dùng cho 1 người.

| Feature | Quota |
|---------|-------|
| AI Game Concept | 100/tháng |
| AI Code Generate | 50/tháng |
| AI Debug | 30/tháng |
| AI Unit Test | 20/tháng |
| AI Asset Generate | ❌ |
| AI Code Review | 10/tháng |
| AI Model | GPT-4o |
| Export project | ✅ |
| Chat history | 30 ngày |
| Priority queue | ✅ |

### Business ($29/tháng)

Mục đích: Studio / team — unlimited AI + asset generation.

| Feature | Quota |
|---------|-------|
| AI Game Concept | Unlimited |
| AI Code Generate | Unlimited |
| AI Debug | Unlimited |
| AI Unit Test | Unlimited |
| AI Asset Generate | 100/tháng |
| AI Code Review | Unlimited |
| AI Model | GPT-4o + Claude |
| Export project | ✅ |
| Chat history | Unlimited |
| Priority queue | ✅ |

---

## 3. Upsell Flow

```
User đăng ký Free
    → Dùng 3 lần AI Concept, thấy hay
    → Hết quota, muốn dùng Code Generate
    → Upgrade Pro ($9/mo)

Dev dùng Pro
    → Hết 50 code gen/tháng
    → Cần AI Asset Generate (sprite, texture)
    → Cần model mạnh hơn (Claude)
    → Upgrade Business ($29/mo)
```

---

## 4. Features JSON (DB)

```json
// Free
{
    "ai_concept": 3,
    "ai_generate": 0,
    "ai_debug": 0,
    "ai_test": 0,
    "ai_asset": 0,
    "ai_code_review": 0,
    "ai_model": "gpt-4o-mini",
    "export_project": false,
    "chat_history": 7,
    "priority_queue": false
}

// Pro ($9/mo)
{
    "ai_concept": 100,
    "ai_generate": 50,
    "ai_debug": 30,
    "ai_test": 20,
    "ai_asset": 0,
    "ai_code_review": 10,
    "ai_model": "gpt-4o",
    "export_project": true,
    "chat_history": 30,
    "priority_queue": true
}

// Business ($29/mo)
{
    "ai_concept": -1,
    "ai_generate": -1,
    "ai_debug": -1,
    "ai_test": -1,
    "ai_asset": 100,
    "ai_code_review": -1,
    "ai_model": "gpt-4o,claude",
    "export_project": true,
    "chat_history": -1,
    "priority_queue": true
}
```

Giá trị: `-1` = unlimited, `0` = không có, `N` = N lần/tháng, `true/false` = boolean.

---

## 5. Kiến trúc kỹ thuật

### Billing Flow

```
App/Web → POST /api/v1/subscription/subscribe {plan: "pro"}
    → SubscriptionService::createPaypalSubscription()
    → PayPal tạo subscription → return approval_url
    → User approve trên PayPal
    → PayPal webhook BILLING.SUBSCRIPTION.ACTIVATED
    → SubscriptionService::activateSubscription()
    → UserSubscription status = active
```

### Quota Check Flow (AI service gọi LamGame API)

```
AI Service → GET /api/v1/subscription/usage
    → SubscriptionService::checkQuota(userId, 'ai_generate')
    → Return: {allowed: true, limit: 50, used: 12, remaining: 38}

AI Service → POST /api/v1/subscription/use-quota {feature: 'ai_generate'}
    → SubscriptionService::useQuota(userId, 'ai_generate')
    → SubscriptionUsage::increment()
    → Return: {success: true, remaining: 37}
```

### Database

```
subscription_plans        — 3 plans (free, pro, business)
user_subscriptions        — user ↔ plan, PayPal subscription ID, status
subscription_transactions — payment history
subscription_usages       — quota tracking per user per feature per month
```

---

## 6. API Endpoints

| Method | Endpoint | Auth | Mô tả |
|--------|----------|------|-------|
| GET | `/api/v1/subscription/plans` | Public | Danh sách gói |
| POST | `/api/v1/subscription/subscribe` | Auth | Đăng ký gói |
| GET | `/api/v1/subscription/status` | Auth | Trạng thái subscription |
| GET | `/api/v1/subscription/usage` | Auth | Quota đã dùng (tất cả features) |
| POST | `/api/v1/subscription/check-quota` | Auth | Kiểm tra quota 1 feature `{feature}` |
| POST | `/api/v1/subscription/use-quota` | Auth | Trừ quota 1 feature `{feature}` |
| POST | `/api/v1/subscription/cancel` | Auth | Hủy subscription |
| GET | `/api/v1/subscription/paypal/return` | Public | PayPal callback |
| POST | `/api/v1/subscription/webhook` | Public | PayPal webhook (verified signature) |

---

## 7. Triển khai

### Cần làm để vận hành

| # | Task | Ước tính | Trạng thái |
|---|------|----------|-----------|
| 1 | Tạo PayPal Billing Plans (Pro + Business) | 1 giờ | ✅ Done (02/04) — Pro: `P-06S19361AU003084UNHHA4XY`, Business: `P-6GY50545CA5254507NHHA42I` |
| 2 | Cập nhật `paypal_plan_id` vào DB | 10 phút | ✅ Done (02/04) — migration `2026_04_02_130000` |
| 3 | Config `.env` production (PayPal credentials) | 30 phút | ✅ Done (02/04) — sandbox mode |
| 4 | Chạy migration production | 5 phút | ⬜ Chờ deploy |
| 5 | Thêm endpoint `POST /use-quota` cho AI service gọi | 1 giờ | ✅ Done (02/04) |
| 6 | Verify PayPal webhook signature | 2 giờ | ✅ Done (02/04) |
| 7 | Test end-to-end (sandbox) | 2 giờ | ⬜ |
| 8 | Switch sang PayPal production | 30 phút | ⬜ |

### Tích hợp AI Service

AI service cần:
1. Gọi `GET /api/v1/subscription/usage` để check quota trước khi xử lý request
2. Gọi `POST /api/v1/subscription/use-quota` sau khi xử lý thành công
3. Đọc `ai_model` từ plan features để chọn model phù hợp
4. Check `priority_queue` để xếp hàng ưu tiên

---

## 8. Pricing Analysis

### Chi phí AI (ước tính)

| Model | Input cost | Output cost | Avg/request |
|-------|-----------|-------------|-------------|
| GPT-4o mini | $0.15/1M | $0.60/1M | ~$0.001 |
| GPT-4o | $2.50/1M | $10.00/1M | ~$0.01 |
| Claude Sonnet | $3.00/1M | $15.00/1M | ~$0.015 |

### Margin per plan

| Plan | Revenue | Est. AI cost (max usage) | Margin |
|------|---------|-------------------------|--------|
| Free | $0 | 3 × $0.001 = $0.003 | -$0.003 (marketing cost) |
| Pro | $9 | 210 × $0.01 = $2.10 | ~$6.90 (77%) |
| Business | $29 | 500 × $0.015 = $7.50 | ~$21.50 (74%) |

Margin tốt. Ngay cả khi user dùng hết quota, profit vẫn > 70%.
