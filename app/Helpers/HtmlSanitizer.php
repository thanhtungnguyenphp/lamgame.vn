<?php

namespace App\Helpers;

class HtmlSanitizer
{
    /**
     * Sanitize HTML to prevent XSS attacks
     * 
     * @param string $html
     * @return string
     */
    public static function sanitize($html)
    {
        if (empty($html)) {
            return '';
        }
        
        // Allowed tags for job descriptions
        $allowedTags = '<p><br><strong><em><u><ol><ul><li><h3><h4>';
        
        // Strip all tags except allowed
        $html = strip_tags($html, $allowedTags);
        
        // Remove javascript: and data: protocols
        $html = preg_replace('/javascript:/i', '', $html);
        $html = preg_replace('/data:/i', '', $html);
        
        // Remove event handlers (onclick, onload, etc)
        $html = preg_replace('/\s*on\w+\s*=\s*["\']?[^"\']*["\']?/i', '', $html);
        
        // Remove style attributes (can contain expressions)
        $html = preg_replace('/\s*style\s*=\s*["\']?[^"\']*["\']?/i', '', $html);
        
        return trim($html);
    }
    
    /**
     * Sanitize and limit length
     * 
     * @param string $html
     * @param int $maxLength
     * @return string
     */
    public static function sanitizeAndLimit($html, $maxLength = null)
    {
        $sanitized = self::sanitize($html);
        
        if ($maxLength && mb_strlen(strip_tags($sanitized)) > $maxLength) {
            $text = strip_tags($sanitized);
            $text = mb_substr($text, 0, $maxLength);
            return '<p>' . htmlspecialchars($text) . '...</p>';
        }
        
        return $sanitized;
    }
}
