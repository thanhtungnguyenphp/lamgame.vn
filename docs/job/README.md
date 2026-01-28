# Job Functionality Documentation

This document outlines the functionality related to job postings on the lamgame.vn platform.

## 1. Overview

The job portal allows companies and users to post job openings in the game development industry. It provides features for listing, searching, viewing, and managing job applications.

## 2. Job Listings Page (`/viec-lam-game`)

This page displays a list of available job openings.

*   **Data Source:** Jobs are primarily fetched from the `products` table, filtered by `type = 'job'` and `sku LIKE 'JOB_%'`. Details like name, description, status, and URL key are retrieved from the localized `product_flat` table (using the `vi` locale).
*   **Company Information:** Joins with the `companies` table to display company name, logo, and other details.
*   **Filtering & Sorting:** Users can filter jobs by keyword, location, and sort them by date (newest first), salary (high to low), or company name. These parameters are taken from the request query string.
*   **Attributes:** Key job attributes like job type, experience level, salary range, location, required skills, and benefits are fetched from `product_attribute_values` and associated tables.
*   **Thumbnails:** Each job listing displays a thumbnail. This is either fetched from the job's associated images in `product_images` or falls back to a default recruitment image.
*   **URL Key:** Jobs are identified by a URL key (slug) in `product_flat` for friendly URLs, e.g., `/viec-lam-game/your-job-title-123`.

## 3. Job Detail Page (`/viec-lam/{slug}`)

This page displays comprehensive information about a specific job posting.

*   **Data Fetching:** Retrieves job data based on the `url_key` (slug) from `product_flat` and related tables, including detailed descriptions, company information, attributes, and images.
*   **Related Jobs:** Suggests similar job postings based on category.
*   **Company Details:** Shows detailed information about the hiring company, including description, website, contact info, and logo.
*   **Application Form:** Provides an interface for users to apply for the job, pre-filling details if the user is logged in.

## 4. Job Posting Functionality

Job postings can be created and managed through the Admin Panel or via API endpoints.

### 4.1. Admin Panel (`/admin/jobs`)

The admin interface provides a user-friendly way to manage job postings.

*   **Routes:** Handled by `Admin/JobController` with routes like `GET /admin/jobs` (index), `GET /admin/jobs/create`, `POST /admin/jobs`, `GET /admin/jobs/{id}/edit`, `PUT /admin/jobs/{id}`, `DELETE /admin/jobs/{id}`.
*   **Workflow (`store` & `update` methods):
    *   **Validation:** Input fields like title, description, contact email, and company name are validated.
    *   **Company Management:** Admins can create a new company or associate the job with an existing company (if the admin is linked to one). Company logo uploads are handled.
    *   **Product & Flat Creation:** Creates records in `products` (type: `job`, SKU: `JOB_%`) and `product_flat` tables.
    *   **Attribute Saving:** Job-specific attributes (job type, experience level, salary, skills, benefits, etc.) are saved into `product_attribute_values` and pivot tables (`job_skills`, `job_benefits`).
    *   **Ownership:** Jobs posted by admins are associated with the admin user via `created_by_admin_id`.

### 4.2. API Endpoints

APIs are available for programmatic job management.

*   **Public API (`/api/jobs`)
    *   `GET /api/jobs`:** Lists job postings with various filters (search, type, location, salary, etc.).
    *   `GET /api/jobs/{id}`:** Retrieves details for a specific job.
    *   `POST /api/jobs`:** Creates a new job posting. Requires authentication (likely `sanctum` token). If an admin user is authenticated, it handles company association.
    *   `PUT /api/jobs/{id}`:** Updates an existing job posting.
    *   `DELETE /api/jobs/{id}`:** Deletes a job posting.
    *   `POST /api/jobs/{id}/publish` & `POST /api/jobs/{id}/unpublish`:** Toggles the published status of a job.

*   **User-specific API (`/api/user/jobs`)
    *   This section is protected by `auth:sanctum` and is intended for authenticated users (presumably job posters or recruiters).
    *   `GET /api/user/jobs`:** Lists jobs created by the authenticated user.
    *   `POST /api/user/jobs`:** Creates a new job posting for the authenticated user. It ensures the job is linked to the correct category (`viec-lam`) and user ID.
    *   `PUT /api/user/jobs/{id}`:** Updates a job owned by the user.
    *   `DELETE /api/user/jobs/{id}`:** Deletes a job owned by the user.
    *   `PATCH /api/user/jobs/{id}/toggle-status`:** Activates/deactivates a job.
    *   `POST /api/user/jobs/{id}/duplicate`:** Duplicates an existing job.
    *   `POST /api/user/jobs/from-template/{templateId}`:** Creates a job from a saved template.

## 5. Database Structure Highlights

*   **`products` Table:** Core table for all product types, including jobs. Stores `id`, `sku`, `type` (e.g., 'job'), `created_by_admin_id`, `company_id`, timestamps.
*   **`product_flat` Table:** Stores localized product data. For jobs, this includes `name`, `description`, `short_description`, `status`, `visible_individually`, `url_key`, `meta_title`, `meta_description`, and `locale`.
*   **`categories` & `category_translations`:** Used to categorize products, with `viec-lam` being the primary category for jobs.
*   **`attributes` Table:** Defines available attributes for products (e.g., `job_type`, `experience_level`, `required_skills`).
*   **`product_attribute_values` Table:** Links products to attribute values. For jobs, stores `text_value` (for strings/IDs), `integer_value`, `date_value`, etc., associated with `attribute_id`.
*   **`job_skills` & `job_benefits` Tables:** Pivot tables for many-to-many relationships, linking jobs (`product_id`) to specific skill options (`skill_option_id`) or benefit options (`benefit_option_id`).
*   **`companies` Table:** Stores information about companies posting jobs.
*   **`job_applications` Table:** Stores applications submitted for job postings.

## 6. Key Controllers and Services

*   **`LamGamePageController`:** Handles frontend display of job listings and details.
*   **`Admin/JobController`:** Manages job postings via the admin panel.
*   **`Api/JobController`:** Provides API endpoints for public job data and general job creation/management.
*   **`Api/UserJobController`:** Provides API endpoints for authenticated users to manage their own job postings.
*   **`JobService`:** Contains core business logic for creating, updating, duplicating, and managing jobs.
*   **`JobFilterService`:** Handles fetching job categories and attributes for forms.
*   **`JobSearchService`:** Implements advanced search and filtering logic for job listings and user job management.
