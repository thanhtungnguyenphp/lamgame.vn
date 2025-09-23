<?php

/**
 * AI Image Upload Limits Configuration
 * This file increases PHP limits specifically for AI image uploads
 */

// Set upload limits for AI images
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '100M'); 
ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('max_input_time', '300');

// Log the changes for debugging
if (function_exists('error_log')) {
    error_log("AI Upload Limits Set: upload_max_filesize=" . ini_get('upload_max_filesize') . 
             ", post_max_size=" . ini_get('post_max_size') . 
             ", memory_limit=" . ini_get('memory_limit'));
}