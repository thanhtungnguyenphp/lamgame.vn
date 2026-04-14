# LAMGAME.VN — Tài liệu dự án

> Cập nhật: 2026-04-09

## Tổng quan

LAMGAME.VN là nền tảng marketplace mã nguồn game, xây dựng trên Bagisto (Laravel eCommerce) + Vue.js.

**URL:** https://lamgame.vn
**Tech stack:** Laravel 11, Vue.js 3, MySQL 8, Redis, Vite, Docker
**Theme:** emsaigon (custom)

---

## Cấu trúc tài liệu

```
docs/
├── README.md                          # File này — mục lục tổng
├── Installation.md                    # Hướng dẫn cài đặt dev/production
├── THUMBNAIL_GUIDE.md                 # Hướng dẫn tạo thumbnail blog
│
├── STATUS.md                          # ⭐ TRẠNG THÁI TỔNG + TASK LIST
│
├── api/                               # API Xổ số (Lottery)
│   ├── 01_tong_quan.md
│   ├── 02_xs_truyen_thong.md
│   ├── 03_vietlot.md
│   ├── 04_latest_schedule.md
│   ├── 05_huong_dan_trien_khai.md
│   ├── 06_mobile_app_api.md
│   ├── 07_van_hanh_deploy.md
│   └── TECHNICAL_SOLUTION.md
│
├── API-SportPulse/                    # API Thể thao
│   ├── 01_PRODUCT_SPEC.md
│   ├── 02_API_SPEC.md
│   ├── 03_API_GUIDE.md
│   ├── 04_CRAWL_PLAN.md
│   └── TASKS.md
│
├── auth/                              # Xác thực người dùng
│   └── README.md
│
├── banner/                            # Banner system
│   └── BANNER_SPEC.md
│
├── blog-publish-api/                  # Blog Publish API
│   ├── README.md
│   ├── API-GUIDE.md
│   ├── API_BACKEND_REQUIREMENTS_v0.3.md
│   └── REPORT.md
│
├── checkout/                          # Checkout & Payment
│   ├── README.md                      # Checkout flow (Bagisto)
│   └── LemonSqueezy/                  # Cổng thanh toán quốc tế
│       ├── README.md
│       ├── INTEGRATION_PLAN.md
│       ├── TASKS.md
│       ├── api-reference.md
│       ├── dang-ky.md
│       └── tich-hop.md
│
├── job/                               # Tuyển dụng game
│   ├── README.md
│   └── README_vi.md
│
├── job-application/                   # Hệ thống ứng tuyển
│   └── README.md
│
├── landing_page/                      # Landing pages
│   └── yc-lp-app-lotto-live.md
│
├── subscription/                      # AI Subscription Plans
│   ├── AI_SUBSCRIPTION_PLANS.md
│   └── DEPLOY_PRODUCTION.md           # ⭐ Hướng dẫn deploy production
│
├── tool-scraped/                      # Tool scrape & migrate data
│   ├── 01_scrape_architecture.md
│   ├── 02_task_scrape_and_migrate.md
│   └── 03_quick_reference.md
│
└── tracking/                          # Analytics & Tracking
    └── implementation_guide.md
```

---

## Quick Links

| Tài liệu | Mô tả |
|-----------|--------|
| [STATUS.md](STATUS.md) | Trạng thái tổng dự án, task list, roadmap |
| [Installation.md](Installation.md) | Cài đặt dev & production |
| [subscription/DEPLOY_PRODUCTION.md](subscription/DEPLOY_PRODUCTION.md) | ⭐ Deploy AI Subscription lên production |
| [checkout/LemonSqueezy/TASKS.md](checkout/LemonSqueezy/TASKS.md) | Task tracking Lemon Squeezy |
| [API-SportPulse/TASKS.md](API-SportPulse/TASKS.md) | Task tracking SportPulse API |
| [blog-publish-api/REPORT.md](blog-publish-api/REPORT.md) | Báo cáo Blog API |
| [subscription/AI_SUBSCRIPTION_PLANS.md](subscription/AI_SUBSCRIPTION_PLANS.md) | Gói AI subscription |
