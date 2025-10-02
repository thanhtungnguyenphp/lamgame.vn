<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xác nhận nhận hồ sơ ứng tuyển</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333333;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 25px;
            color: #2c3e50;
        }
        .job-details {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        .job-details h3 {
            margin: 0 0 15px 0;
            color: #28a745;
            font-size: 20px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
            border-bottom: 1px dotted #dee2e6;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }
        .detail-value {
            color: #495057;
        }
        .application-code {
            background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 25px 0;
            border: 2px solid #e17055;
        }
        .application-code strong {
            font-size: 20px;
            color: #2d3436;
        }
        .next-steps {
            background-color: #e3f2fd;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
        }
        .next-steps h3 {
            color: #1565c0;
            margin: 0 0 20px 0;
            font-size: 18px;
        }
        .steps-list {
            margin: 15px 0;
            padding-left: 0;
            list-style: none;
        }
        .steps-list li {
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }
        .steps-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
            font-size: 16px;
        }
        .tips {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .tips h4 {
            color: #856404;
            margin: 0 0 15px 0;
            font-size: 16px;
        }
        .tips ul {
            margin: 0;
            padding-left: 20px;
        }
        .tips li {
            margin-bottom: 8px;
            color: #856404;
        }
        .contact-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            text-align: center;
        }
        .contact-info h4 {
            color: #495057;
            margin: 0 0 15px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 10px 5px;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }
        .footer {
            background-color: #343a40;
            color: #adb5bd;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }
        .footer a {
            color: #28a745;
            text-decoration: none;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #6c757d;
            font-size: 18px;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 20px 15px;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="icon">✅</div>
            <h1>Hồ sơ đã được nhận!</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Cảm ơn bạn đã ứng tuyển tại Làm Game</p>
        </div>

        <div class="content">
            <div class="greeting">
                <strong>Xin chào {{ $applicantName }},</strong>
            </div>

            <p>Chúng tôi đã nhận được hồ sơ ứng tuyển của bạn và rất vui mừng về sự quan tâm của bạn đến vị trí việc làm tại {{ $companyName }}.</p>

            <div class="job-details">
                <h3>📋 Thông tin ứng tuyển</h3>
                <div class="detail-row">
                    <span class="detail-label">Vị trí:</span>
                    <span class="detail-value"><strong>{{ $jobTitle }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Công ty:</span>
                    <span class="detail-value">{{ $companyName }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Mức lương:</span>
                    <span class="detail-value">{{ $jobData['salary'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Địa điểm:</span>
                    <span class="detail-value">{{ $jobData['location'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Thời gian ứng tuyển:</span>
                    <span class="detail-value">{{ $appliedAt->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            @if($applicationCode)
            <div class="application-code">
                <p style="margin: 0 0 10px 0; font-size: 14px;">Mã đơn ứng tuyển của bạn:</p>
                <strong>{{ $applicationCode }}</strong>
                <p style="margin: 10px 0 0 0; font-size: 12px; opacity: 0.8;">
                    Vui lòng lưu mã này để tra cứu trạng thái đơn ứng tuyển
                </p>
            </div>
            @endif

            <div class="next-steps">
                <h3>🚀 Các bước tiếp theo</h3>
                <p><strong>Thời gian xem xét:</strong> {{ $nextSteps['review_time'] }}</p>
                
                <ul class="steps-list">
                    @foreach($nextSteps['what_happens_next'] as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ul>
                
                <p style="margin-top: 20px;">
                    <strong>Cách liên hệ:</strong> Chúng tôi sẽ liên hệ với bạn qua {{ $nextSteps['contact_method'] }}
                </p>
            </div>

            <div class="tips">
                <h4>💡 Lời khuyên trong thời gian chờ</h4>
                <ul>
                    @foreach($nextSteps['tips'] as $tip)
                        <li>{{ $tip }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="contact-info">
                <h4>Cần hỗ trợ?</h4>
                <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi:</p>
                
                <div style="margin-top: 15px;">
                    <a href="mailto:{{ config('mail.contact.address', config('mail.from.address')) }}" class="btn">
                        📧 Gửi email hỗ trợ
                    </a>
                </div>
            </div>

            <p style="margin-top: 30px; font-size: 14px; color: #6c757d;">
                Email này được gửi tự động từ hệ thống. Vui lòng không phản hồi trực tiếp email này.
            </p>
        </div>

        <div class="footer">
            <div class="social-links">
                <a href="#">🌐 Website</a>
                <a href="#">💼 LinkedIn</a>
                <a href="#">📱 Facebook</a>
            </div>
            
            <p style="margin: 20px 0;">
                <strong>{{ config('mail.from.name', 'Làm Game') }}</strong><br>
                Nền tảng việc làm cho ngành Game Development tại Việt Nam
            </p>
            
            <p style="font-size: 12px; opacity: 0.8;">
                © {{ date('Y') }} {{ config('mail.from.name', 'Làm Game') }}. All rights reserved.<br>
                <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
            </p>
        </div>
    </div>
</body>
</html>