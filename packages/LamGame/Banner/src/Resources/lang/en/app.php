<?php

return [
    'admin' => [
        'banners' => [
            // Index page
            'index' => [
                'title' => 'Banner Management',
                'add-btn' => 'Create Banner',
                'no-banners' => 'No Banners Found',
                'no-banners-description' => 'Create your first banner to get started with banner management.',
                'create-first-banner' => 'Create First Banner',
            ],

            // Create page
            'create' => [
                'title' => 'Create New Banner',
                'back-btn' => 'Back to Banners',
                'save-btn' => 'Save Banner',
                'general' => 'General Information',
                'content' => 'Banner Content',
                'settings' => 'Display Settings',
                'media' => 'Media & Images',
                
                // Form fields
                'name' => 'Banner Name',
                'type' => 'Banner Type',
                'type-image' => 'Image Banner',
                'type-html' => 'HTML Banner',
                'type-video' => 'Video Banner',
                'position' => 'Display Position',
                'device-type' => 'Device Type',
                'status' => 'Active',
                'banner-title' => 'Title',
                'banner-content' => 'Description',
                'link' => 'Link URL',
                'link-placeholder' => 'https://example.com',
                'target' => 'Link Target',
                'target-self' => 'Same Window',
                'target-blank' => 'New Window',
                'sort-order' => 'Sort Order',
                'start-date' => 'Start Date',
                'end-date' => 'End Date',
                'image' => 'Banner Image',
                'image-size-hint' => 'Recommended size: 1200x400px for optimal display across devices',
                'image-alt' => 'Image Alt Text',
                'image-alt-placeholder' => 'Describe the image for accessibility',
            ],

            // Edit page
            'edit' => [
                'title' => 'Edit Banner',
                'back-btn' => 'Back to Banners',
                'save-btn' => 'Update Banner',
                'general' => 'General Information',
                'content' => 'Banner Content',
                'settings' => 'Display Settings',
                'media' => 'Media & Images',
                
                // Form fields (same as create)
                'name' => 'Banner Name',
                'type' => 'Banner Type',
                'type-image' => 'Image Banner',
                'type-html' => 'HTML Banner',
                'type-video' => 'Video Banner',
                'position' => 'Display Position',
                'device-type' => 'Device Type',
                'status' => 'Active',
                'banner-title' => 'Title',
                'banner-content' => 'Description',
                'link' => 'Link URL',
                'link-placeholder' => 'https://example.com',
                'target' => 'Link Target',
                'target-self' => 'Same Window',
                'target-blank' => 'New Window',
                'sort-order' => 'Sort Order',
                'start-date' => 'Start Date',
                'end-date' => 'End Date',
                'image' => 'Banner Image',
                'image-size-hint' => 'Recommended size: 1200x400px for optimal display across devices',
                'image-alt' => 'Image Alt Text',
                'image-alt-placeholder' => 'Describe the image for accessibility',
            ],

            // Show page
            'show' => [
                'title' => 'Banner Details',
                'back-btn' => 'Back to Banners',
                'edit-btn' => 'Edit Banner',
                'preview' => 'Banner Preview',
                'details' => 'Banner Information',
                'analytics' => 'Performance Analytics',
                'mobile-performance' => 'Mobile Performance',
                'actions' => 'Quick Actions',
                
                'no-image' => 'No image uploaded',
                'view-link' => 'View Target Link',
                
                // Details labels
                'name' => 'Name',
                'type' => 'Type',
                'position' => 'Position',
                'device-type' => 'Device Type',
                'status' => 'Status',
                'sort-order' => 'Sort Order',
                'start-date' => 'Start Date',
                'end-date' => 'End Date',
                'created-at' => 'Created',
                
                // Analytics
                'impressions' => 'Impressions',
                'clicks' => 'Clicks',
                'ctr' => 'Click Rate',
                'recent-activity' => 'Recent Activity',
                
                // Actions
                'edit-banner' => 'Edit Banner',
                'visit-link' => 'Visit Link',
                'delete-banner' => 'Delete Banner',
                'delete-confirmation' => 'Are you sure you want to delete this banner? This action cannot be undone.',
            ],

            // Messages
            'create-success' => 'Banner created successfully!',
            'create-error' => 'Error creating banner. Please try again.',
            'update-success' => 'Banner updated successfully!',
            'update-error' => 'Error updating banner. Please try again.',
            'delete-success' => 'Banner deleted successfully!',
            'delete-error' => 'Error deleting banner. Please try again.',
            'mass-delete-success' => 'Selected banners deleted successfully!',
            'mass-delete-error' => 'Error deleting banners. Please try again.',
            'mass-enable-success' => 'Selected banners enabled successfully!',
            'mass-disable-success' => 'Selected banners disabled successfully!',
            'mass-update-error' => 'Error updating banners. Please try again.',
            'cache-clear-success' => 'Banner cache cleared successfully!',
            'cache-clear-error' => 'Error clearing banner cache. Please try again.',
        ],
        
        'analytics' => [
            'title' => 'Banner Analytics',
            'dashboard' => 'Analytics Dashboard',
            'performance' => 'Performance Metrics',
            'reports' => 'Detailed Reports',
        ],
    ],
];
