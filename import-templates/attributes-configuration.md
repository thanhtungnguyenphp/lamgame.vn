# Bagisto Configurable Product Attributes Setup

## Prerequisites: Creating Super Attributes

Before importing configurable products, you must create the attributes that will be used as super attributes. Here's how to set them up:

### 1. Service Duration Attribute

**Admin Panel Steps:**
1. Navigate to **Catalog > Attributes**
2. Click **Create Attribute**
3. Fill in the details:

```
General Information:
- Code: service_duration
- Type: Select
- Admin Name: Service Duration
- Default Label: Service Duration
- Input Validation: None
- Value Per Locale: No
- Value Per Channel: No
- Is Filterable: Yes
- Is Configurable: Yes (IMPORTANT!)
- Is Visible On Product View Page On Front End: Yes
- Is User Defined: Yes
- Use In Layered Navigation: Filterable (with results)
```

**Options to Add:**
```
Option 1: 
- Admin Label: 60 Minutes
- Default Label: 60
- Sort Order: 1

Option 2:
- Admin Label: 90 Minutes  
- Default Label: 90
- Sort Order: 2
```

### 2. Room Type Attribute

**Admin Panel Steps:**
1. Navigate to **Catalog > Attributes**
2. Click **Create Attribute**
3. Fill in the details:

```
General Information:
- Code: room_type
- Type: Select
- Admin Name: Room Type
- Default Label: Room Type
- Input Validation: None
- Value Per Locale: No
- Value Per Channel: No
- Is Filterable: Yes
- Is Configurable: Yes (IMPORTANT!)
- Is Visible On Product View Page On Front End: Yes
- Is User Defined: Yes
- Use In Layered Navigation: Filterable (with results)
```

**Options to Add:**
```
Option 1:
- Admin Label: Single Room
- Default Label: don
- Sort Order: 1

Option 2:
- Admin Label: Double Room
- Default Label: doi
- Sort Order: 2
```

### 3. Modality Attribute

**Admin Panel Steps:**
1. Navigate to **Catalog > Attributes**
2. Click **Create Attribute**
3. Fill in the details:

```
General Information:
- Code: modality
- Type: Select
- Admin Name: Modality
- Default Label: Modality
- Input Validation: None
- Value Per Locale: No
- Value Per Channel: No
- Is Filterable: Yes
- Is Configurable: Yes (IMPORTANT!)
- Is Visible On Product View Page On Front End: Yes
- Is User Defined: Yes
- Use In Layered Navigation: Filterable (with results)
```

**Options to Add:**
```
Option 1:
- Admin Label: Offline/In-Person
- Default Label: offline
- Sort Order: 1

Option 2:
- Admin Label: Online/Remote
- Default Label: online
- Sort Order: 2
```

## Adding Attributes to Attribute Families

After creating the attributes, you must add them to the appropriate attribute families:

### For Service Attribute Family:
1. Navigate to **Catalog > Attribute Families**
2. Click on **service** attribute family
3. In the **General** tab, drag and drop the following attributes:
   - `service_duration`
   - `room_type`
   - `modality`

### For Course Attribute Family:
1. Navigate to **Catalog > Attribute Families**
2. Click on **course** attribute family
3. In the **General** tab, drag and drop the following attributes:
   - `modality`

## Important Notes:

1. **Option Labels Must Match CSV Values**: The option labels you create (60, 90, don, doi, offline, online) must exactly match the values you use in your CSV file.

2. **Configurable Flag**: The "Is Configurable" option must be set to "Yes" for all super attributes.

3. **Attribute Family Assignment**: Attributes must be assigned to the same attribute family that your products use.

4. **Sort Order**: Set appropriate sort orders for options to control display order on the frontend.

## SQL Commands (Alternative Method)

If you prefer to create attributes via SQL:

```sql
-- Insert service_duration attribute
INSERT INTO attributes (code, admin_name, type, validation, value_per_locale, value_per_channel, is_filterable, is_configurable, is_user_defined, is_visible_on_front, is_required, position, created_at, updated_at) VALUES 
('service_duration', 'Service Duration', 'select', NULL, 0, 0, 1, 1, 1, 1, 0, 1, NOW(), NOW());

-- Insert room_type attribute  
INSERT INTO attributes (code, admin_name, type, validation, value_per_locale, value_per_channel, is_filterable, is_configurable, is_user_defined, is_visible_on_front, is_required, position, created_at, updated_at) VALUES 
('room_type', 'Room Type', 'select', NULL, 0, 0, 1, 1, 1, 1, 0, 2, NOW(), NOW());

-- Insert modality attribute
INSERT INTO attributes (code, admin_name, type, validation, value_per_locale, value_per_channel, is_filterable, is_configurable, is_user_defined, is_visible_on_front, is_required, position, created_at, updated_at) VALUES 
('modality', 'Modality', 'select', NULL, 0, 0, 1, 1, 1, 1, 0, 3, NOW(), NOW());
```

Note: You'll still need to create attribute options and assign to families through the admin panel or additional SQL commands.
