# Project Tracker - Hệ thống quản lý dự án và tác vụ

- Vai trò của Agent: là lập trình viên chuyên về back-end, front-end và devOps, database
- Nhiệm vụ chính: phát triển api, code back end, code front-end, deploy dự án sever, quản lý docker, tối ưu và cải tiến tốc độ chất lượng dự án

## Kết nối

- Middleware: https://agent-docflow.ohha.com.vn
- Header: X-Agent-Key: 9f721f2f802744a650372742a9586d4ffac4df5a630a6bec

## Project hiện tại

- Project: LAM GAME
- projectId: 0a8164f5-b23e-4c73-9b15-398419651055

## States

| Trạng thái | STATUS_ID |
|------------|-----|
| Backlog | b29d3342-8d8c-43df-8395-3a27627faf93 |
| Todo | e1d2f3de-81b0-4776-b157-da2d1135a362 |
| In Progress | 5a0fcfba-3e18-4aa7-9abd-f9692d0d0283 |
| Done | 6075293a-559e-40e1-a5b8-9c5192cb658b |
| Cancelled | 9bb1787d-b8a5-4517-92c1-34ed2f7b6eb3 |

 

## Quy tắc đặt tên task và Workflow

Khi tạo task mới, **bắt buộc** thêm tiền tố phân loại:

- Task liên quan backend/API: `[back_end][service_name] mô tả task`
- Task liên quan mobile app: `[mobile_app][app_name] mô tả task`

**Service names cho backend:**
`auth`, `chat`, `voice`, `gamification`, `learning`, `story`, `analytics`, `notifications`, `quiz_ws`, `cache`, `music`, `infra`, `testing`, `docs`, `api_sport`

**App names cho front-end:**
`lamgame`


## Workflow

1. Khi bắt đầu task → gọi update status = In Progress
2. Khi hoàn thành → gọi update status = Done + comment mô tả công việc
3. Khi bị chặn → gọi update status = Cancelled + comment lý do
4. Cuối ngày → gọi daily report
5. Khi tạo/cập nhật tài liệu kỹ thuật → gọi documents/update
6. Khi tạo task mới → luôn thêm tiền tố `[back_end][service]` hoặc `[mobile_app][app]`


---

## 1. Health Check

```
GET /health
```

Không yêu cầu xác thực.

**Response:**
```json
{"status": "ok"}
```

---

## 2. Liệt kê Projects

```
GET /api/agent/projects
```

**Response:**
```json
{
  "success": true,
  "data": [
    {"id": "uuid", "name": "Project Name", "identifier": "PROJ", ...}
  ]
}
```

---

## 3. Tổng quan Project

```
GET /api/agent/projects/:projectId/summary
```

**Response:**
```json
{
  "project": {"name": "LAM GAME", "id": "uuid"},
  "progress": {"total": 7, "done": 2, "percent": 29},
  "tasksByState": {
    "Done": [{"id": "uuid", "title": "Task name"}],
    "In Progress": [...],
    "Todo": [...],
    "Backlog": [...]
  },
  "modules": [{"id": "uuid", "name": "Module name", "status": "in-progress"}]
}
```

---

## 4. Liệt kê States

```
GET /api/agent/projects/:projectId/states
```

**Response:**
```json
{
  "success": true,
  "data": [
    {"id": "uuid", "name": "Backlog", "group": "backlog", "color": "#A3A3A3"},
    {"id": "uuid", "name": "Todo", "group": "unstarted", "color": "#3A3A3A"},
    {"id": "uuid", "name": "In Progress", "group": "started", "color": "#F59E0B"},
    {"id": "uuid", "name": "Done", "group": "completed", "color": "#16A34A"},
    {"id": "uuid", "name": "Cancelled", "group": "cancelled", "color": "#EF4444"}
  ]
}
```

---

## 5. Tạo Task

```
POST /api/agent/tasks/create
```

**Body:**

| Field | Bắt buộc | Type | Mô tả |
|-------|----------|------|--------|
| projectId | ✅ | string | UUID project |
| name | ✅ | string | Tên task |
| state | ❌ | string | State UUID (mặc định: Backlog) |
| priority | ❌ | string | `urgent`, `high`, `medium`, `low`, `none` |
| description | ❌ | string | Mô tả task (sẽ được wrap trong `<p>`) |
| assignees | ❌ | array | Mảng user UUIDs |
| labels | ❌ | array | Mảng label UUIDs |

**Ví dụ:**
```bash
curl -X POST https://agent-docflow.ohha.com.vn/api/agent/tasks/create \
  -H "Content-Type: application/json" \
  -H "X-Agent-Key: YOUR_KEY" \
  -d '{
    "projectId": "project-uuid",
    "name": "Implement login API",
    "state": "todo-state-uuid",
    "priority": "high",
    "description": "Tạo API đăng nhập với JWT"
  }'
```

**Response:**
```json
{
  "success": true,
  "data": {"id": "new-task-uuid", "name": "Implement login API", "state": "...", ...}
}
```

---

## 6. Cập nhật Task

```
POST /api/agent/tasks/update
```

**Body:**

| Field | Bắt buộc | Type | Mô tả |
|-------|----------|------|--------|
| projectId | ✅ | string | UUID project |
| taskId | ✅ | string | UUID task |
| status | ❌ | string | State UUID mới |
| name | ❌ | string | Tên task mới |
| description | ❌ | string | Mô tả mới (sẽ được wrap trong `<p>`) |
| assignees | ❌ | array | Mảng user UUIDs (thay thế toàn bộ assignee hiện tại) |
| comment | ❌ | string | Thêm comment vào task |

> **Lưu ý:** Phải có ít nhất 1 trong 4 field: `status`, `name`, `description`, hoặc `assignees`. Nếu không có field nào sẽ trả về lỗi 400.

**Ví dụ:**
```bash
curl -X POST https://agent-docflow.ohha.com.vn/api/agent/tasks/update \
  -H "Content-Type: application/json" \
  -H "X-Agent-Key: YOUR_KEY" \
  -d '{
    "projectId": "project-uuid",
    "taskId": "task-uuid",
    "status": "done-state-uuid",
    "comment": "Đã hoàn thành: implement login API với JWT"
  }'
```

**Response:**
```json
{
  "success": true,
  "data": {"id": "task-uuid", "state": "done-state-uuid", ...}
}
```

---

## 7. Batch Update Tasks

```
POST /api/agent/tasks/batch-update
```

Cập nhật nhiều task cùng lúc (concurrency: 5 task song song).

**Body:**

| Field | Bắt buộc | Type | Mô tả |
|-------|----------|------|--------|
| projectId | ✅ | string | UUID project |
| updates | ✅ | array | Mảng `{taskId, status, comment}` |

**Ví dụ:**
```bash
curl -X POST https://agent-docflow.ohha.com.vn/api/agent/tasks/batch-update \
  -H "Content-Type: application/json" \
  -H "X-Agent-Key: YOUR_KEY" \
  -d '{
    "projectId": "project-uuid",
    "updates": [
      {"taskId": "task-1-uuid", "status": "done-uuid", "comment": "Done"},
      {"taskId": "task-2-uuid", "status": "in-progress-uuid"}
    ]
  }'
```

**Response:**
```json
{"success": true, "count": 2}
```

---

## 8. Tạo / Cập nhật Page (Tài liệu)

```
POST /api/agent/documents/update
```

**Body:**

| Field | Bắt buộc | Type | Mô tả |
|-------|----------|------|--------|
| projectId | ✅ | string | UUID project |
| title | ❌* | string | Tên page (*bắt buộc khi tạo mới) |
| pageId | ❌ | string | UUID page (nếu có → update, không có → tạo mới) |
| content | ❌ | string | Nội dung HTML |

**Tạo page mới:**
```bash
curl -X POST https://agent-docflow.ohha.com.vn/api/agent/documents/update \
  -H "Content-Type: application/json" \
  -H "X-Agent-Key: YOUR_KEY" \
  -d '{
    "projectId": "project-uuid",
    "title": "Tài liệu kỹ thuật - Auth Module",
    "content": "<h1>Auth Module</h1><p>Mô tả hệ thống xác thực</p>"
  }'
```

**Update page đã có:**
```bash
curl -X POST https://agent-docflow.ohha.com.vn/api/agent/documents/update \
  -H "Content-Type: application/json" \
  -H "X-Agent-Key: YOUR_KEY" \
  -d '{
    "projectId": "project-uuid",
    "pageId": "page-uuid",
    "content": "<h1>Auth Module v2</h1><p>Cập nhật: thêm OAuth2</p>"
  }'
```

**Response (tạo mới):**
```json
{
  "success": true,
  "data": {"id": "new-page-uuid", "name": "Tài liệu kỹ thuật - Auth Module", ...}
}
```

**Response (update):**
```json
{
  "success": true,
  "data": {"message": "Updated successfully"}
}
```

### HTML Tags hỗ trợ:

| Tag | Dùng cho |
|-----|----------|
| `<h1>`, `<h2>`, `<h3>` | Tiêu đề |
| `<p>` | Đoạn văn |
| `<ul>`, `<ol>`, `<li>` | Danh sách |
| `<strong>`, `<em>` | In đậm, in nghiêng |
| `<code>` | Code inline |
| `<pre><code>` | Code block |
| `<a href="...">` | Link |
| `<table>`, `<tr>`, `<td>` | Bảng |

---

## 9. Báo cáo ngày

```
POST /api/agent/reports/daily
```

**Body:**

| Field | Bắt buộc | Type | Mô tả |
|-------|----------|------|--------|
| projectId | ✅ | string | UUID project |

**Ví dụ:**
```bash
curl -X POST https://agent-docflow.ohha.com.vn/api/agent/reports/daily \
  -H "Content-Type: application/json" \
  -H "X-Agent-Key: YOUR_KEY" \
  -d '{"projectId": "project-uuid"}'
```

**Response:**
```json
{
  "success": true,
  "report": "# Báo cáo ngày 2026-05-16\n\n## ✅ Đã hoàn thành (2)\n...",
  "data": {
    "date": "2026-05-16",
    "done": [{"id": "uuid", "title": "...", "assignee": []}],
    "inProgress": [...],
    "blocked": [...],
    "todo": [...]
  }
}
```

---

## Error Responses

| HTTP Code | Ý nghĩa |
|-----------|----------|
| 400 | Thiếu field bắt buộc hoặc không có field nào để update |
| 401 | Sai hoặc thiếu X-Agent-Key |
| 429 | Vượt rate limit (60 req/phút) |
| 500 | Lỗi server hoặc Plane API |

**Format lỗi:**
```json
{"error": "Mô tả lỗi"}
```
