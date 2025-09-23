# Bagisto Category Configuration

## Required Categories Setup

Before importing products, ensure these categories exist in your Bagisto installation:

### 1. Service Categories

#### Category: Dịch vụ trị liệu (Treatment Services)
- **Slug**: `dich-vu-tri-lieu`
- **Name**: Dịch vụ trị liệu
- **Description**: Các dịch vụ trị liệu chuyên nghiệp
- **Status**: Enabled
- **Position**: 1

#### Category: Cổ vai gáy (Neck Shoulder Treatment)
- **Slug**: `co-vai-gay`  
- **Name**: Cổ vai gáy
- **Description**: Điều trị các vấn đề về cổ vai gáy
- **Status**: Enabled
- **Position**: 2

#### Category: Khóa học (Courses)
- **Slug**: `khoa-hoc`
- **Name**: Khóa học
- **Description**: Các khóa học đào tạo chuyên nghiệp
- **Status**: Enabled
- **Position**: 3

## Creating Categories via Admin Panel

### Method 1: Admin Interface

1. Navigate to **Catalog > Categories**
2. Click **Create Category**
3. Fill in the required information:

**For Treatment Services Category:**
```
General Information:
- Name: Dịch vụ trị liệu
- Slug: dich-vu-tri-lieu
- Status: Enable
- Position: 1
- Description: Các dịch vụ trị liệu chuyên nghiệp bao gồm massage và các liệu pháp khác

SEO Information:
- Meta Title: Dịch vụ trị liệu - EMSaigon
- Meta Keywords: dịch vụ trị liệu, massage, therapy
- Meta Description: Khám phá các dịch vụ trị liệu chuyên nghiệp tại EMSaigon

Display Settings:
- Display Mode: Products and Description
- Filterable Attributes: Select relevant attributes
```

**For Neck Shoulder Category:**
```
General Information:
- Name: Cổ vai gáy
- Slug: co-vai-gay
- Status: Enable
- Position: 2
- Description: Điều trị chuyên sâu các vấn đề về cổ vai gáy

SEO Information:
- Meta Title: Điều trị cổ vai gáy - EMSaigon
- Meta Keywords: cổ vai gáy, neck shoulder, pain relief
- Meta Description: Giải pháp điều trị hiệu quả cho các vấn đề cổ vai gáy
```

**For Courses Category:**
```
General Information:
- Name: Khóa học
- Slug: khoa-hoc
- Status: Enable
- Position: 3
- Description: Các khóa học đào tạo chuyên nghiệp trong lĩnh vực trị liệu

SEO Information:
- Meta Title: Khóa học trị liệu - EMSaigon
- Meta Keywords: khóa học, training, course, therapy
- Meta Description: Tham gia các khóa học chuyên nghiệp về trị liệu
```

## Creating Categories via SQL

### Method 2: Direct Database Insert

```sql
-- Insert categories
INSERT INTO categories (position, image, status, created_at, updated_at) VALUES 
(1, NULL, 1, NOW(), NOW()),
(2, NULL, 1, NOW(), NOW()),
(3, NULL, 1, NOW(), NOW());

-- Get the category IDs (assuming they are 1, 2, 3)
SET @cat1_id = (SELECT id FROM categories WHERE position = 1 ORDER BY id DESC LIMIT 1);
SET @cat2_id = (SELECT id FROM categories WHERE position = 2 ORDER BY id DESC LIMIT 1);
SET @cat3_id = (SELECT id FROM categories WHERE position = 3 ORDER BY id DESC LIMIT 1);

-- Insert category translations for Vietnamese (locale_id = 1, assuming default)
INSERT INTO category_translations (category_id, name, slug, description, meta_title, meta_description, meta_keywords, locale_id) VALUES 
(@cat1_id, 'Dịch vụ trị liệu', 'dich-vu-tri-lieu', 'Các dịch vụ trị liệu chuyên nghiệp bao gồm massage và các liệu pháp khác', 'Dịch vụ trị liệu - EMSaigon', 'Khám phá các dịch vụ trị liệu chuyên nghiệp tại EMSaigon', 'dịch vụ trị liệu, massage, therapy', 1),
(@cat2_id, 'Cổ vai gáy', 'co-vai-gay', 'Điều trị chuyên sâu các vấn đề về cổ vai gáy', 'Điều trị cổ vai gáy - EMSaigon', 'Giải pháp điều trị hiệu quả cho các vấn đề cổ vai gáy', 'cổ vai gáy, neck shoulder, pain relief', 1),
(@cat3_id, 'Khóa học', 'khoa-hoc', 'Các khóa học đào tạo chuyên nghiệp trong lĩnh vực trị liệu', 'Khóa học trị liệu - EMSaigon', 'Tham gia các khóa học chuyên nghiệp về trị liệu', 'khóa học, training, course, therapy', 1);

-- Insert category translations for English (locale_id = 2, if applicable)
INSERT INTO category_translations (category_id, name, slug, description, meta_title, meta_description, meta_keywords, locale_id) VALUES 
(@cat1_id, 'Treatment Services', 'treatment-services', 'Professional treatment services including massage and other therapies', 'Treatment Services - EMSaigon', 'Discover professional treatment services at EMSaigon', 'treatment, services, massage, therapy', 2),
(@cat2_id, 'Neck Shoulder', 'neck-shoulder', 'Specialized treatment for neck and shoulder issues', 'Neck Shoulder Treatment - EMSaigon', 'Effective solutions for neck and shoulder problems', 'neck, shoulder, pain relief, treatment', 2),
(@cat3_id, 'Courses', 'courses', 'Professional training courses in therapy field', 'Therapy Courses - EMSaigon', 'Join professional courses in therapy', 'courses, training, therapy, education', 2);
```

## Category Hierarchy (Optional)

If you want to create a hierarchical structure:

```
Root
├── Dịch vụ trị liệu (dich-vu-tri-lieu)
│   └── Cổ vai gáy (co-vai-gay) [child of dich-vu-tri-lieu]
└── Khóa học (khoa-hoc)
```

To create this hierarchy, set the `parent_id` field when creating the "Cổ vai gáy" category to point to the "Dịch vụ trị liệu" category ID.

## Verification

After creating categories, verify they exist by running:

```sql
SELECT c.id, ct.name, ct.slug, c.status, c.position 
FROM categories c 
JOIN category_translations ct ON c.id = ct.category_id 
WHERE ct.locale_id = 1 
ORDER BY c.position;
```

## Important Notes

1. **Slug Uniqueness**: Category slugs must be unique across the system
2. **Status**: Ensure categories are enabled (status = 1)
3. **Locale**: Make sure translations exist for your default locale
4. **URL Structure**: Categories will be accessible at `/category/{slug}`
5. **Product Assignment**: Products reference categories by slug in the CSV import

## CSV Usage

In your product CSV, reference categories like this:
```csv
categories
"dich-vu-tri-lieu"
"co-vai-gay,khoa-hoc"
"dich-vu-tri-lieu"
```

Multiple categories can be assigned to a single product by separating slugs with commas.
