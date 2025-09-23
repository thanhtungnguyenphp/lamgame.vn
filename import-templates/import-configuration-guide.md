# Bagisto Configurable Product Import Guide

## Overview

This guide walks you through importing configurable products into Bagisto using the Data Transfer tool. Configurable products allow customers to select from different variations (like service duration, room type, or delivery mode).

## Prerequisites Checklist

Before starting the import, ensure you have completed:

- [ ] Created all super attributes (service_duration, room_type, modality)
- [ ] Added attributes to appropriate attribute families
- [ ] Created all required categories
- [ ] Prepared product images
- [ ] Prepared CSV file with correct format

## Image Setup

### 1. Image Directory Structure

Create the image directory in your Laravel storage:

```bash
# Navigate to your project root
cd /Users/Shared/jerry/ohha/emsaigon/src

# Create the image directory
mkdir -p storage/app/import/product-images

# Verify the directory exists
ls -la storage/app/import/
```

### 2. Image File Preparation

Place your product images in the created directory:

```bash
storage/app/import/product-images/
├── massage-main.jpg
├── massage-60min.jpg
├── massage-60min-double.jpg
├── massage-90min.jpg
├── massage-90min-double.jpg
├── course-shoulder.jpg
├── course-offline.jpg
├── course-online.jpg
├── therapy-main.jpg
├── therapy-60min-offline.jpg
├── therapy-60min-online.jpg
├── therapy-90min-offline.jpg
└── therapy-90min-online.jpg
```

**Image Requirements:**
- Supported formats: JPG, JPEG, PNG, GIF
- Recommended size: 800x600 pixels or higher
- File names should match exactly what you specify in the CSV
- No spaces in file names (use hyphens or underscores)

### 3. Image Permissions

Ensure proper permissions:

```bash
# Set proper permissions
chmod -R 755 storage/app/import/product-images/
chown -R www-data:www-data storage/app/import/product-images/
```

## CSV File Preparation

### 1. Column Structure

Your CSV must include these columns in order:

```csv
sku,type,attribute_family,name,price,status,visible_individually,visibility,categories,url_key,short_description,description,images,inventories,tax_category_id,super_attributes,parent_sku,service_duration,room_type,modality
```

### 2. Column Definitions

| Column | Description | Parent Row | Child Row |
|--------|-------------|------------|-----------|
| `sku` | Unique product identifier | Required | Required |
| `type` | Product type | `configurable` | `virtual` or `simple` |
| `attribute_family` | Must match existing family | Required | Required |
| `name` | Product name | Required | Required |
| `price` | Product price | `0` for parent | Actual price |
| `status` | Enable/disable (1/0) | `1` | `1` |
| `visible_individually` | Show in catalog | `1` for parent | `0` for child |
| `visibility` | Visibility level | `4` (catalog+search) | `1` (not visible) |
| `categories` | Category slugs | Required | Required |
| `url_key` | URL slug | Required | Required |
| `short_description` | Brief description | Required | Required |
| `description` | Full description | Required | Required |
| `images` | Image filenames | Required | Required |
| `inventories` | Stock quantity | Required | Required |
| `tax_category_id` | Tax category | `1` (default) | `1` (default) |
| `super_attributes` | Comma-separated attribute codes | Required | Leave empty |
| `parent_sku` | Parent product SKU | Leave empty | Required |
| `service_duration` | Duration option | Leave empty | Option value |
| `room_type` | Room type option | Leave empty | Option value |
| `modality` | Delivery mode option | Leave empty | Option value |

### 3. Important CSV Rules

1. **Parent Row**: One row with `type=configurable`, contains `super_attributes`
2. **Child Rows**: Multiple rows with `type=virtual`, contain `parent_sku` and attribute values
3. **Attribute Values**: Must exactly match option labels created in attributes
4. **Categories**: Use category slugs, separate multiple with commas
5. **Images**: Just filename, no path (e.g., `image.jpg` not `/path/to/image.jpg`)

## Step-by-Step Import Process

### Step 1: Access Data Transfer

1. Log in to Bagisto Admin Panel
2. Navigate to **Settings > Data Transfer > Imports**
3. Click **Create Import**

### Step 2: Configure Import Settings

Fill in the import form:

```
Import Settings:
- Import Type: Products
- File: Upload your CSV file
- Image Directory Path: product-images
- Allowed Errors: 10 (adjust as needed)
- Field Separator: , (comma)
- Field Enclosure: " (double quote)
```

**Critical**: Set "Image Directory Path" to `product-images` (without leading slash)

### Step 3: Validate Import

1. Click **Validate** button
2. Review validation results
3. Fix any errors in your CSV file
4. Re-upload if needed

### Step 4: Execute Import

1. After successful validation, click **Import**
2. Monitor the import progress
3. Check for any import errors
4. Review imported products

## Common Import Issues and Solutions

### Issue 1: Images Not Loading

**Problem**: Products imported but images are missing
**Solutions**:
- Check image directory path setting
- Verify file names match exactly
- Ensure proper file permissions
- Confirm images are in correct directory

### Issue 2: Attribute Values Not Matching

**Problem**: Child products not linking to parent
**Solutions**:
- Verify attribute option labels match CSV values exactly
- Check attribute is marked as "Configurable"
- Ensure attributes are assigned to attribute family

### Issue 3: Categories Not Found

**Problem**: Products not assigned to categories
**Solutions**:
- Verify category slugs exist
- Check category status is enabled
- Ensure proper spelling in CSV

### Issue 4: Parent-Child Relationship Broken

**Problem**: Child products not showing as variations
**Solutions**:
- Verify `parent_sku` matches parent product SKU exactly
- Check `super_attributes` column format (comma-separated)
- Ensure child product type is `virtual` or `simple`

## Post-Import Verification

After successful import:

### 1. Check Parent Products

Navigate to **Catalog > Products** and verify:
- [ ] Parent products exist with `type=configurable`
- [ ] Parent products show "0" price
- [ ] Parent products are visible in catalog

### 2. Check Child Products

Verify child products:
- [ ] Child products exist with correct prices
- [ ] Child products are not individually visible
- [ ] Child products have correct attribute values

### 3. Test Frontend Display

Visit your storefront and check:
- [ ] Configurable products display correctly
- [ ] Attribute options are available for selection
- [ ] Price updates when selecting different options
- [ ] Images change with option selection (if configured)

### 4. Verify Stock Levels

Check inventory:
- [ ] Parent products show "In Stock"
- [ ] Child products have correct inventory quantities
- [ ] Out of stock options are handled correctly

## Advanced Configuration

### Multiple Images per Product

To add multiple images, separate with commas in the CSV:

```csv
images
"main-image.jpg,gallery1.jpg,gallery2.jpg"
```

### Custom Attribute Integration

For additional custom attributes:
1. Add column to CSV with attribute code as header
2. Include attribute in appropriate attribute family
3. Set attribute values for each product row

### Localized Content

For multi-language support:
1. Create separate CSV files for each locale
2. Import each language version separately
3. Ensure SKUs match across all imports

## Troubleshooting Commands

### Clear Cache After Import

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Reindex Products (if using search)

```bash
php artisan scout:import "App\Models\Product"
```

### Check Import Logs

```bash
tail -f storage/logs/laravel.log
```

## Sample Import Workflow

Here's a complete workflow example:

```bash
# 1. Prepare directories
mkdir -p storage/app/import/product-images

# 2. Copy images
cp /path/to/your/images/* storage/app/import/product-images/

# 3. Set permissions
chmod -R 755 storage/app/import/product-images/

# 4. Validate CSV structure
head -1 your-products.csv

# 5. Clear caches before import
php artisan cache:clear

# 6. Monitor logs during import
tail -f storage/logs/laravel.log &

# 7. After import, clear caches again
php artisan cache:clear
```

This completes the import configuration. Your configurable products should now be ready for use in Bagisto!
