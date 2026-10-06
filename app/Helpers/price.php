<?php

if (! function_exists('format_usd')) {
    /**
     * Format một số tiền thành chuỗi USD hiển thị, ví dụ: 19.99 -> "$19.99".
     * Dùng xuyên suốt frontend để hiển thị giá đồng nhất bằng USD.
     */
    function format_usd($amount): string
    {
        return '$' . number_format((float) $amount, 2, '.', ',');
    }
}
