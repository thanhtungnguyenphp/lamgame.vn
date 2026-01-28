# Custom Modules

This document provides an overview of the custom modules developed for this project.

## LamGame Banner Module

The `LamGame/Banner` module provides a flexible and powerful system for managing promotional banners.

### Features

- **Multiple Banner Types:** Supports image, HTML, and video banners.
- **Translatable Content:** Banner content (title, alt text, etc.) can be translated into multiple languages.
- **Scheduling:** Banners can be scheduled to appear for a specific date range.
- **Device Targeting:** Banners can be targeted to specific devices (desktop, tablet, mobile).
- **Positioning:** Banners can be placed in various predefined positions on the site (e.g., homepage hero, sidebar, product detail page).
- **Performance Tracking:** The module tracks banner impressions and clicks, and calculates the click-through rate (CTR).

### Database Schema

The `banners` table has the following columns:

- `name` (string): The internal name of the banner.
- `type` (string): The type of banner (image, html, video).
- `position` (string): The position of the banner on the site.
- `device_type` (string): The device type the banner is targeted to (all, desktop, tablet, mobile).
- `channel_id` (integer): The channel the banner belongs to.
- `locale` (string): The locale the banner is targeted to.
- `start_date` (datetime): The date the banner should start appearing.
- `end_date` (datetime): The date the banner should stop appearing.
- `sort_order` (integer): The sort order of the banner.
- `status` (boolean): The status of the banner (active/inactive).
- `title` (string): The title of the banner (translatable).
- `content` (text): The HTML content of the banner (for HTML banners).
- `image` (string): The path to the banner image.
- `image_alt` (string): The alt text for the banner image (translatable).
- `link` (string): The URL the banner links to.
- `target` (string): The target of the link (e.g., `_blank`).
- `css_classes` (array): An array of CSS classes to apply to the banner.
- `attributes` (array): An array of HTML attributes to apply to the banner.
- `settings` (array): An array of banner-specific settings (translatable).
- `clicks_count` (integer): The number of clicks the banner has received.
- `impressions_count` (integer): The number of impressions the banner has received.

### Scopes

The `Banner` model provides several convenient query scopes:

- `active()`: Returns only active banners.
- `position(string $position)`: Returns banners for a specific position.
- `device(string $deviceType)`: Returns banners for a specific device type.
- `channel(int $channelId)`: Returns banners for a specific channel.
- `locale(string $locale)`: Returns banners for a specific locale.
- `withinDateRange()`: Returns banners that are currently within their scheduled date range.
- `ordered()`: Orders the banners by their sort order.
- `forDisplay(array $filters)`: A convenient scope that combines several of the above scopes to get banners for frontend display.

### Usage

To display banners on the frontend, you can use the `forDisplay` scope. For example, to get all active banners for the homepage hero position, for the current channel and locale, you would do the following:

```php
use LamGame\Banner\Models\Banner;

$banners = Banner::forDisplay([
    'position' => 'homepage_hero',
    'channel_id' => core()->getCurrentChannel()->id,
    'locale' => app()->getLocale(),
])->get();
```

