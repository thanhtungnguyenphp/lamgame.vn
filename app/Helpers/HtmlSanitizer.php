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
        
        // Allowed tags for job descriptions (including editor formatting)
        $allowedTags = '<p><br><strong><b><em><i><u><s><ol><ul><li><h1><h2><h3><h4><h5><h6><blockquote><a>';
        
        // Strip all tags except allowed
        $html = strip_tags($html, $allowedTags);
        
        // Sanitize links
        $html = preg_replace_callback('/<a\s+([^>]*)>/i', function($matches) {
            $attrs = $matches[1];
            
            // Extract href
            if (preg_match('/href=["\']([^"\']*)["\']/', $attrs, $hrefMatch)) {
                $href = $hrefMatch[1];
                
                // Only allow http/https
                if (!preg_match('/^https?:\/\//i', $href)) {
                    return '<a>';
                }
                
                // Build safe link
                return '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">';
            }
            
            return '<a>';
        }, $html);
        
        // Remove javascript: and data: protocols
        $html = preg_replace('/javascript:/i', '', $html);
        $html = preg_replace('/data:/i', '', $html);
        
        // Remove event handlers (onclick, onload, etc)
        $html = preg_replace('/\s*on\w+\s*=\s*["\']?[^"\']*["\']?/i', '', $html);
        
        // Remove style attributes except for alignment
        $html = preg_replace_callback('/\s*style\s*=\s*["\']([^"\']*)["\']?/i', function($matches) {
            $style = $matches[1];
            
            // Only allow text-align
            if (preg_match('/text-align:\s*(left|center|right|justify)/i', $style, $alignMatch)) {
                return ' class="ql-align-' . strtolower($alignMatch[1]) . '"';
            }
            
            return '';
        }, $html);
        
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
