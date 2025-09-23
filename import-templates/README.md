# Bagisto Configurable Product Import Templates

This directory contains comprehensive templates and guides for importing configurable products into Bagisto for the EMSaigon project.

## 📁 File Structure

```
import-templates/
├── README.md                          # This file
├── configurable-products-template.csv # Main comprehensive template
├── sample-simple-service.csv         # Simple single-attribute example
├── sample-complex-service.csv        # Complex multi-attribute example
├── sample-course.csv                 # Course products example
├── attributes-configuration.md       # Attribute setup guide
├── categories-configuration.md       # Category setup guide
└── import-configuration-guide.md     # Complete import guide
```

## 🚀 Quick Start

1. **Set up prerequisites** (see detailed guides):
   - Create attributes: `service_duration`, `room_type`, `modality`
   - Create categories: `dich-vu-tri-lieu`, `co-vai-gay`, `khoa-hoc`
   - Create attribute families: `service`, `course`

2. **Choose a template**:
   - **Beginner**: Use `sample-simple-service.csv`
   - **Advanced**: Use `sample-complex-service.csv` or `configurable-products-template.csv`
   - **Courses**: Use `sample-course.csv`

3. **Prepare images**:
   ```bash
   mkdir -p storage/app/import/product-images
   # Copy your images to this directory
   ```

4. **Import products**:
   - Admin Panel → Settings → Data Transfer → Imports
   - Set "Image Directory Path" to `product-images`

## 📋 Template Descriptions

### 1. configurable-products-template.csv
**Use case**: Comprehensive template with multiple product examples
- 3 different configurable products
- Mix of attribute combinations
- Realistic pricing and descriptions
- Vietnamese product names and descriptions

### 2. sample-simple-service.csv
**Use case**: Learning/testing with minimal complexity
- 1 configurable product (Acupuncture)
- Single super attribute (`service_duration`)
- 2 child variations (60min, 90min)
- Perfect for first-time imports

### 3. sample-complex-service.csv
**Use case**: Complex multi-attribute scenarios
- 1 configurable product (Wellness Package)
- 3 super attributes (`service_duration`, `room_type`, `modality`)
- 8 child variations (all combinations)
- Demonstrates full attribute matrix

### 4. sample-course.csv
**Use case**: Course/training products
- 2 configurable course products
- Single super attribute (`modality`)
- Online/Offline delivery options
- Different pricing structure

## 🔧 Attribute Configurations

### Required Attributes

| Attribute | Type | Options | Used In |
|-----------|------|---------|---------|
| `service_duration` | Select | 60, 90 | Services |
| `room_type` | Select | don, doi | Services |
| `modality` | Select | offline, online | Services, Courses |

### Attribute Families

- **service**: Contains `service_duration`, `room_type`, `modality`
- **course**: Contains `modality`

## 📂 Category Structure

| Slug | Name | Usage |
|------|------|--------|
| `dich-vu-tri-lieu` | Dịch vụ trị liệu | Treatment services |
| `co-vai-gay` | Cổ vai gáy | Neck/shoulder treatments |
| `khoa-hoc` | Khóa học | Training courses |

## 💡 CSV Structure Explained

### Parent Row (Configurable Product)
```csv
sku,type,super_attributes,price,visible_individually
SERVICE_001,configurable,"service_duration,room_type",0,1
```

### Child Rows (Product Variations)
```csv
sku,type,parent_sku,service_duration,room_type,price,visible_individually
SERVICE_001_60_DON,virtual,SERVICE_001,60,don,800000,0
```

### Key Rules
1. **Parent**: `type=configurable`, `price=0`, `visible_individually=1`
2. **Children**: `type=virtual`, `parent_sku=PARENT_SKU`, `visible_individually=0`
3. **Attributes**: Child rows specify option values, parent specifies `super_attributes`

## 🔍 Common Issues & Solutions

### Import Failures
- **Attributes not found**: Create attributes first, mark as "Configurable"
- **Categories missing**: Create categories with exact slugs
- **Images not loading**: Check file path and permissions
- **Child products not linking**: Verify `parent_sku` matches exactly

### Data Validation
- Option values must match attribute labels exactly
- SKUs must be unique across all products
- Category slugs must exist and be enabled
- Image files must be in correct directory

## 📖 Detailed Documentation

For comprehensive setup instructions, see:

1. **[Attributes Configuration](attributes-configuration.md)** - Step-by-step attribute setup
2. **[Categories Configuration](categories-configuration.md)** - Category creation guide
3. **[Import Configuration Guide](import-configuration-guide.md)** - Complete import process

## 🧪 Testing Your Setup

### Validation Checklist
- [ ] All attributes created with correct options
- [ ] Attributes assigned to attribute families
- [ ] All categories created with correct slugs
- [ ] Images uploaded to correct directory
- [ ] CSV file validates without errors
- [ ] Products import successfully
- [ ] Parent-child relationships work on frontend

### Test Import Process
1. Start with `sample-simple-service.csv`
2. Create test images (can be placeholders)
3. Run import validation
4. Fix any errors and re-validate
5. Execute import and verify results

## 🎯 Production Usage

When ready for production:

1. **Backup your database**
2. **Test import on staging environment first**
3. **Use proper product images (800x600px minimum)**
4. **Set up proper inventory levels**
5. **Configure tax categories as needed**
6. **Test frontend functionality thoroughly**

## 🤝 Support

If you encounter issues:

1. Check the detailed guides in this directory
2. Verify your Bagisto version compatibility
3. Enable debug mode to see detailed error messages
4. Check Laravel logs: `storage/logs/laravel.log`

## 📝 Notes

- Templates use Vietnamese content for EMSaigon project
- Prices are in Vietnamese Dong (VND)
- Product types use `virtual` for booking compatibility
- All examples follow Bagisto best practices
- Images references are examples - replace with actual files
