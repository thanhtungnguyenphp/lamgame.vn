<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ContactController extends Controller
{
    /**
     * Handle contact form submission
     */
    public function submit(Request $request)
    {
        // Rate limiting: 5 requests per minute per IP
        $key = 'contact-form:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã gửi quá nhiều tin nhắn. Vui lòng thử lại sau vài phút.'
            ], 429);
        }
        RateLimiter::hit($key, 60);

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'subject.required' => 'Vui lòng chọn chủ đề.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'message.max' => 'Nội dung không được quá 2000 ký tự.',
        ]);

        // Map subject values to readable labels
        $subjectLabels = [
            'source-game' => 'Hỏi về Source Game',
            'ai-tools' => 'Hỏi về AI Tools',
            'hop-tac' => 'Hợp tác kinh doanh',
            'ho-tro' => 'Hỗ trợ kỹ thuật',
            'khac' => 'Khác',
        ];
        $subjectLabel = $subjectLabels[$validated['subject']] ?? $validated['subject'];

        try {
            // Send email to admin
            Mail::send([], [], function ($mail) use ($validated, $subjectLabel) {
                $mail->to('thanhtungnguyenphp@gmail.com')
                    ->subject('[LamGame Contact] ' . $subjectLabel)
                    ->html($this->buildEmailHtml($validated, $subjectLabel));
            });

            // Log the contact submission
            Log::info('Contact form submitted', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'ip' => request()->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tin nhắn của bạn đã được gửi thành công!'
            ]);

        } catch (\Exception $e) {
            Log::error('Contact form email failed', [
                'error' => $e->getMessage(),
                'email' => $validated['email'] ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi email. Vui lòng thử lại sau.'
            ], 500);
        }
    }

    /**
     * Build HTML email content
     */
    private function buildEmailHtml(array $data, string $subjectLabel): string
    {
        $phone = $data['phone'] ?? 'Không cung cấp';
        $message = nl2br(htmlspecialchars($data['message']));
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #8B5CF6, #6366F1); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; font-size: 20px; }
        .content { background: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px; }
        .field { margin-bottom: 15px; }
        .field-label { font-weight: 600; color: #6B7280; font-size: 12px; text-transform: uppercase; margin-bottom: 4px; }
        .field-value { color: #111827; }
        .message-box { background: white; padding: 15px; border-radius: 6px; border: 1px solid #e5e7eb; margin-top: 15px; }
        .footer { margin-top: 20px; font-size: 12px; color: #9CA3AF; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📬 Tin nhắn mới từ LamGame.vn</h1>
        </div>
        <div class="content">
            <div class="field">
                <div class="field-label">Chủ đề</div>
                <div class="field-value"><strong>{$subjectLabel}</strong></div>
            </div>
            <div class="field">
                <div class="field-label">Họ tên</div>
                <div class="field-value">{$data['name']}</div>
            </div>
            <div class="field">
                <div class="field-label">Email</div>
                <div class="field-value"><a href="mailto:{$data['email']}">{$data['email']}</a></div>
            </div>
            <div class="field">
                <div class="field-label">Số điện thoại</div>
                <div class="field-value">{$phone}</div>
            </div>
            <div class="message-box">
                <div class="field-label">Nội dung tin nhắn</div>
                <div class="field-value">{$message}</div>
            </div>
        </div>
        <div class="footer">
            Email này được gửi tự động từ form liên hệ tại lamgame.vn
        </div>
    </div>
</body>
</html>
HTML;
    }
}
