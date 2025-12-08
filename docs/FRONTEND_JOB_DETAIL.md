# Tài Liệu Front-End Job Detail Page

## Tổng Quan

Trang chi tiết việc làm (Job Detail) hiển thị thông tin đầy đủ về một công việc và cho phép người dùng ứng tuyển.

**URL Pattern**: `/viec-lam/{slug}`

**Example**: `https://lamgame.localhost/viec-lam/job-b-36`

---

## 1. Routing & Controller

### Route Definition

**File**: `routes/web.php`

```php
Route::get('viec-lam/{slug}', [LamGamePageController::class, 'jobDetail'])
    ->name('lamgame.job.detail');
```

### Controller Method

**File**: `app/Http/Controllers/LamGamePageController.php`

**Method**: `jobDetail($slug)`

#### Flow Logic

```
1. Query Job Data
   ├── Join products, product_flat, categories, companies
   ├── Join product_attribute_values (deadline, email)
   ├── Join product_images (thumbnail)
   └── Filter by slug, type='job', status=1

2. Check Job Exists
   └── If not found → abort(404)

3. Get Job Attributes
   └── Call getJobAttributes($job->id)

4. Process Data
   ├── Get thumbnail URL
   ├── Process description (HTML safe)
   ├── Parse company name & job title
   ├── Format salary
   ├── Calculate posted time ago
   └── Encode company logo to base64

5. Get Similar Jobs
   └── Query 3 latest jobs (exclude current)

6. Get Customer Data (if logged in)
   └── For auto-fill form

7. Return View
   └── Pass all data to blade template
```

---

## 2. Database Query Structure

### Main Job Query

```php
DB::table('products as p')
    ->leftJoin('product_flat as pf', ...)
    ->leftJoin('product_categories as pc', ...)
    ->leftJoin('category_translations as ct', ...)
    ->leftJoin('companies as c', ...)
    ->leftJoin('product_attribute_values as pav_deadline', ...)
    ->leftJoin('product_attribute_values as pav_email', ...)
    ->leftJoin('product_images as pi', ...)
    ->where('pf.url_key', $slug)
    ->where('p.type', 'job')
    ->where('p.sku', 'LIKE', 'JOB_%')
    ->where('pf.status', 1)
    ->where('pf.visible_individually', 1)
    ->select([...])
    ->first();
```

### Selected Fields

| Field | Source | Description |
|-------|--------|-------------|
| id | products | Job ID |
| sku | products | Job SKU (JOB_xxxxx) |
| name | product_flat | Job title + Company name |
| short_description | product_flat | Job requirements |
| description | product_flat | Job description |
| price | product_flat | Salary (numeric) |
| url_key | product_flat | URL slug |
| category_name | category_translations | Category name |
| created_at | products | Created timestamp |
| updated_at | products | Updated timestamp |
| application_deadline | product_attribute_values | Deadline date |
| contact_email | product_attribute_values | Contact email |
| thumbnail | product_images | Thumbnail path |
| company_name | companies | Company name |
| company_description | companies | Company description |
| company_logo | companies | Company logo path |
| company_website | companies | Company website |
| company_email | companies | Company email |
| company_phone | companies | Company phone |
| employee_count | companies | Number of employees |
| founded_year | companies | Founded year |
| industry | companies | Industry |

---

## 3. Get Job Attributes Method

### Method: `getJobAttributes($productId)`

**Purpose**: Lấy các thuộc tính của job từ bảng `product_attribute_values`

#### Query Logic

```php
DB::table('product_attribute_values as pav')
    ->join('attributes as a', ...)
    ->leftJoin('attribute_options as ao', ...)
    ->leftJoin('attribute_option_translations as aot', ...)
    ->where('pav.product_id', $productId)
    ->whereIn('a.code', [
        'job_type',
        'experience_level',
        'salary_range',
        'job_location',
        'required_skills',
        'job_benefits'
    ])
    ->select([...])
    ->get();
```

#### Attributes Mapping

| Attribute Code | Type | Description | Example |
|----------------|------|-------------|---------|
| job_type | select | Loại công việc | Full-time, Part-time |
| experience_level | select | Cấp độ kinh nghiệm | Junior, Senior |
| salary_range | select | Mức lương | 10-15 triệu |
| job_location | text | Địa điểm | TP.HCM, Hà Nội |
| required_skills | multiselect | Kỹ năng yêu cầu | PHP, Laravel, MySQL |
| job_benefits | multiselect | Phúc lợi | Bảo hiểm, Thưởng |

#### Processing Logic

```php
// For multiselect attributes (skills, benefits)
if (in_array($attr->code, ['job_benefits', 'required_skills'])) {
    // Split comma-separated IDs
    $valueIds = explode(',', $attr->text_value);
    
    // Get labels for each ID
    foreach ($valueIds as $valueId) {
        $valueLabel = DB::table('attribute_options')
            ->join('attribute_option_translations', ...)
            ->where('id', $valueId)
            ->value('label');
        
        $valueLabels[] = $valueLabel;
    }
    
    // Join labels with comma
    $jobAttributes[$attr->code] = implode(',', $valueLabels);
}
// For single value attributes
else {
    $value = $attr->option_label ?: $attr->text_value ?: $attr->integer_value;
    $jobAttributes[$attr->code] = $value;
}
```

#### Return Format

```php
[
    'job_type' => 'Full-time',
    'experience_level' => 'Senior',
    'salary_range' => '20-30 triệu',
    'job_location' => 'TP.HCM',
    'required_skills' => 'PHP,Laravel,MySQL',
    'job_benefits' => 'Bảo hiểm sức khỏe,Thưởng tháng 13'
]
```

---

## 4. Data Processing

### Company Logo Encoding

```php
if ($job->company_logo) {
    $path = 'company-logos/' . basename($job->company_logo);
    
    if (Storage::disk('public')->exists($path)) {
        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        $logoUrl = 'data:' . $mimeType . ';base64,' . base64_encode($file);
    }
}
```

**Purpose**: Convert logo file to base64 data URL for inline display

### Job Title & Company Name Parsing

```php
// Parse from product_flat.name format: "Job Title - Company Name"
$companyName = $job->company_name ?: 
    trim(str_replace(' - ', ' ', explode(' - ', $job->name)[1] ?? $job->name));

$jobTitle = explode(' - ', $job->name)[0] ?? $job->name;
```

### Salary Formatting

```php
$salaryFormatted = $job->attributes['salary_range'] ?? 'Thỏa thuận';
```

### Posted Time

```php
$postedAgo = Carbon::parse($job->created_at)->diffForHumans();
// Output: "2 ngày trước", "1 tuần trước"
```

---

## 5. View Data Structure

### Data Passed to View

```php
return view('lamgame.pages.job-detail', [
    'job' => $job,                      // Job object with all fields
    'jobTitle' => $jobTitle,            // Parsed job title
    'companyName' => $companyName,      // Parsed company name
    'companyInfo' => $companyInfo,      // Company details array
    'salaryFormatted' => $salaryFormatted, // Formatted salary
    'postedAgo' => $postedAgo,          // Human readable time
    'similarJobs' => $similarJobs,      // Collection of 3 similar jobs
    'customer' => $customerData,        // Customer data (if logged in)
    'isLoggedIn' => !is_null($customer), // Boolean
    'page_title' => $jobTitle . ' - ' . $companyName . ' - Làm Game',
    'page_description' => Str::limit($job->short_description, 160),
]);
```

### Company Info Array

```php
$companyInfo = [
    'name' => $companyName,
    'description' => $job->company_description ?: 'Default description',
    'logo' => $job->company_logo,
    'logo_url' => $logoUrl,             // Base64 encoded
    'website' => $job->company_website,
    'email' => $job->company_email,
    'phone' => $job->company_phone,
    'employee_count' => $job->employee_count ?: 50,
    'founded_year' => $job->founded_year ?: 2020,
    'industry' => $job->industry ?: 'Game Development'
];
```

### Customer Data (if logged in)

```php
$customerData = [
    'id' => $customer->id,
    'full_name' => trim($customer->first_name . ' ' . $customer->last_name),
    'first_name' => $customer->first_name,
    'last_name' => $customer->last_name,
    'email' => $customer->email,
    'phone' => $customer->phone ?? '',
    'is_verified' => $customer->is_verified ?? false,
    'status' => $customer->status ?? 1
];
```

---

## 6. Similar Jobs Query

```php
DB::table('products as p')
    ->leftJoin('product_flat as pf', ...)
    ->where('p.type', 'job')
    ->where('p.sku', 'LIKE', 'JOB_%')
    ->where('pf.url_key', '!=', $slug)  // Exclude current job
    ->where('pf.status', 1)
    ->where('pf.visible_individually', 1)
    ->select('p.id', 'p.sku', 'pf.name', 'pf.price', 'pf.url_key', 'p.created_at')
    ->orderBy('p.created_at', 'desc')
    ->limit(3)
    ->get();
```

**Purpose**: Show 3 most recent jobs (excluding current job)

---

**Continued in Part 2...**
