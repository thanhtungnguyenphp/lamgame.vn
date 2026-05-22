# Agent Context — Dự án LAM GAME

## Vai trò & Phạm vi

- **Vai trò**: Full Stack Developer (Back-end, Database, DevOps)

- **Nhiệm vụ**:
  - Phát triển API/service
  - Quản lý database
  - Quản lý Docker/server
  - Thiết lập CI/CD
  - Tối ưu hiệu năng hệ thống

- **KHÔNG đảm nhiệm**:
  - Mobile app (`[mobile_app]`)
  - UI/UX Design (`[design]`)
  - Game Client/Unity (`[game_client]`)

- **Chỉ nhận task có prefix**:
  - `[back_end]`
  - `[front_end]`
  - `[database]`
  - `[devops]`
  - `[api]`

## Kết nối Middleware

- URL: https://agent-docflow.ohha.com.vn
- Header: X-Agent-Key: 9f721f2f802744a650372742a9586d4ffac4df5a630a6bec

## Project

- Tên: LAM GAME
- projectId: 0a8164f5-b23e-4c73-9b15-398419651055

## States

| Trạng thái | STATUS_ID |
|---|---|
| Backlog | b29d3342-8d8c-43df-8395-3a27627faf93 |
| Todo | e1d2f3de-81b0-4776-b157-da2d1135a362 |
| In Progress | 5a0fcfba-3e18-4aa7-9abd-f9692d0d0283 |
| Done | 6075293a-559e-40e1-a5b8-9c5192cb658b |
| Cancelled | 9bb1787d-b8a5-4517-92c1-34ed2f7b6eb3 |

## Service names (back-end)

`auth`, `chat`, `voice`, `gamification`, `learning`, `story`, `analytics`, `notifications`, `quiz_ws`, `cache`, `music`, `infra`, `testing`, `docs`, `api_sport`

## Quy tắc đặt tên task

```
[back_end][service_name] mô tả task
```

Ví dụ: `[back_end][auth] Implement JWT refresh token`

## Workflow thực hiện task

1. Bắt đầu task → update status = **In Progress**
2. Hoàn thành → update status = **Done** + comment mô tả công việc đã làm
3. Bị chặn/hủy → update status = **Cancelled** + comment lý do
4. Cuối ngày → gọi daily report
5. Tạo/cập nhật tài liệu kỹ thuật → gọi documents/update

---

## API Reference

### Tổng quan Project
```
GET /api/agent/projects/:projectId/summary
```
- Trả về: project info, progress, tasksByState (grouped), modules

### Lấy danh sách Task theo trạng thái
```
GET /api/agent/projects/:projectId/tasks?status=<StateName>
```
- **StateName** là tên trạng thái (case-sensitive): `Backlog`, `Todo`, `In Progress`, `Done`, `Cancelled`
- Response:
```json
{
  "success": true,
  "count": 20,
  "data": [
    {
      "id": "uuid",
      "sequence_id": 278,
      "title": "[prefix][service] mô tả",
      "state": "Todo",
      "priority": "high|medium|low|urgent|none",
      "labels": [],
      "assignees": []
    }
  ]
}
```
- Ví dụ: `GET /api/agent/projects/0a8164f5-.../tasks?status=Todo`
- Lưu ý: KHÔNG dùng STATUS_ID, dùng tên state trực tiếp

### Tạo Task
```
POST /api/agent/tasks/create
Body: { projectId, name, state?, priority?, description?, assignees?, labels? }
```

### Cập nhật Task
```
POST /api/agent/tasks/update
Body: { projectId, taskId, status?, name?, description?, assignees?, comment? }
```

### Batch Update
```
POST /api/agent/tasks/batch-update
Body: { projectId, updates: [{taskId, status, comment}] }
```

## Đọc nội dung Page (Tài liệu)

```
GET /api/agent/projects/:projectId/documents/:pageId
```

**Path params:**

| Param | Bắt buộc | Mô tả |
|-------|----------|--------|
| projectId | ✅ | UUID project |
| pageId | ✅ | UUID page cần đọc |

**Ví dụ:**
```bash
curl https://agent-docflow.ohha.com.vn/api/agent/projects/PROJECT_UUID/documents/PAGE_UUID \
  -H "X-Agent-Key: YOUR_KEY"
```



### Tạo/Cập nhật Tài liệu
```
POST /api/agent/documents/update
Body: { projectId, title?, pageId?, content? }
```
- Không có pageId → tạo mới (title bắt buộc)
- Có pageId → update

### Báo cáo ngày
```
POST /api/agent/reports/daily
Body: { projectId }
```

### Error Codes
| Code | Ý nghĩa |
|---|---|
| 400 | Thiếu field bắt buộc |
| 401 | Sai/thiếu X-Agent-Key |
| 429 | Rate limit (60 req/phút) |
| 500 | Lỗi server |
