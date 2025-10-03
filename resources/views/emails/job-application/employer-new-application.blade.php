<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ứng viên mới - {{ $applicantData['name'] }}</title>
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
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
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
        .priority-badge {
            display: inline-block;
            background: #fd79a8;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }
        .content {
            padding: 40px 30px;
        }
        .applicant-summary {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        .applicant-summary h2 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .applicant-summary .subtitle {
            opacity: 0.9;
            font-size: 16px;
        }
        .two-column {
            display: flex;
            gap: 20px;
            margin: 30px 0;
        }
        .column {
            flex: 1;
        }
        .info-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #6c5ce7;
        }
        .info-card h3 {
            margin: 0 0 15px 0;
            color: #2d3436;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        .info-card .icon {
            margin-right: 10px;
            font-size: 18px;
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
            color: #636e72;
            flex: 0 0 40%;
        }
        .detail-value {
            color: #2d3436;
            flex: 1;
        }
        .highlight {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 3px 8px;
            font-size: 12px;
            font-weight: 600;
            color: #856404;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin: 25px 0;
        }
        .stat-card {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            opacity: 0.9;
        }
        .cover-letter {
            background-color: #e8f4f8;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            font-style: italic;
            border-left: 4px solid #00cec9;
        }
        .cover-letter h4 {
            margin: 0 0 15px 0;
            color: #00b894;
            font-style: normal;
        }
        .actions {
            background: linear-gradient(135deg, #00b894, #00cec9);
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
        }
        .actions h3 {
            color: white;
            margin: 0 0 20px 0;
            font-size: 20px;
        }
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        .btn {
            display: inline-block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
            min-width: 120px;
        }
        .btn-primary {
            background: rgba(255, 255, 255, 0.2);
        }
        .btn-success {
            background: rgba(0, 184, 148, 0.8);
        }
        .btn-danger {
            background: rgba(231, 76, 60, 0.8);
        }
        .btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
            background: rgba(255, 255, 255, 0.3);
        }
        .footer {
            background-color: #2d3436;
            color: #b2bec3;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }
        .footer a {
            color: #00cec9;
            text-decoration: none;
        }
        .admin-panel-link {
            background-color: #e17055;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin: 20px 0;
        }
        .admin-panel-link:hover {
            background-color: #d63031;
            text-decoration: none;
            color: white;
        }
        @media only screen and (max-width: 650px) {
            .two-column {
                flex-direction: column;
            }
            .btn-group {
                flex-direction: column;
            }
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
            <div class="icon">🎯</div>
            <h1>Ứng viên mới!</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">{{ $jobData['title'] }}</p>
            @if($applicationStats['is_first_application'])
                <div class="priority-badge">ỨNG VIÊN ĐẦU TIÊN</div>
            @endif
        </div>

        <div class="content">
            <div class="applicant-summary">
                <h2>{{ $applicantData['name'] }}</h2>
                <div class="subtitle">{{ $applicantData['experience_level'] }} • {{ $applicationCode }}</div>
            </div>

            <div class="two-column">
                <div class="column">
                    <div class="info-card">
                        <h3><span class="icon">👤</span>Thông tin ứng viên</h3>
                        <div class="detail-row">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value">
                                <a href="mailto:{{ $applicantData['email'] }}">{{ $applicantData['email'] }}</a>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Điện thoại:</span>
                            <span class="detail-value">
                                <a href="tel:{{ $applicantData['phone'] }}">{{ $applicantData['phone'] }}</a>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Kinh nghiệm:</span>
                            <span class="detail-value">{{ $applicantData['experience_level'] }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">CV:</span>
                            <span class="detail-value">
                                @if($applicantData['has_cv'])
                                    <span class="highlight">{{ $applicantData['cv_filename'] ?? 'Đã upload' }}</span>
                                @else
                                    <span style="color: #e17055;">Chưa upload</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Ứng tuyển lúc:</span>
                            <span class="detail-value">{{ $appliedAt->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="column">
                    <div class="info-card">
                        <h3><span class="icon">💼</span>Thông tin công việc</h3>
                        <div class="detail-row">
                            <span class="detail-label">Vị trí:</span>
                            <span class="detail-value"><strong>{{ $jobData['title'] }}</strong></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Công ty:</span>
                            <span class="detail-value">{{ $jobData['company'] }}</span>
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
                            <span class="detail-label">Loại hình:</span>
                            <span class="detail-value">{{ $jobData['job_type'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $applicationStats['total'] }}</div>
                    <div class="stat-label">Tổng ứng viên</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $applicationStats['today'] }}</div>
                    <div class="stat-label">Hôm nay</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $applicationStats['pending'] }}</div>
                    <div class="stat-label">Chờ duyệt</div>
                </div>
            </div>

            @if($applicantData['cover_letter'])
            <div class="cover-letter">
                <h4>📝 Thư giới thiệu</h4>
                <p>{{ nl2br(e($applicantData['cover_letter'])) }}</p>
            </div>
            @endif

            <div class="actions">
                <h3>⚡ Hành động nhanh</h3>
                <div class="btn-group">
                    <a href="{{ $quickActions['view_application'] }}" class="btn btn-primary">
                        👁 Xem chi tiết
                    </a>
                    @if($applicantData['has_cv'])
                    <a href="{{ $quickActions['download_cv'] }}" class="btn btn-primary">
                        📄 Tải CV
                    </a>
                    @endif
                    <a href="{{ $quickActions['contact_applicant'] }}" class="btn btn-primary">
                        📧 Gửi email
                    </a>
                    <a href="{{ $quickActions['call_applicant'] }}" class="btn btn-primary">
                        📞 Gọi điện
                    </a>
                    <a href="{{ $quickActions['shortlist'] }}" class="btn btn-success">
                        ✅ Shortlist
                    </a>
                    <a href="{{ $quickActions['reject'] }}" class="btn btn-danger">
                        ❌ Từ chối
                    </a>
                </div>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $quickActions['view_application'] }}" class="admin-panel-link">
                    🏛 Quản lý ứng viên trong Admin Panel
                </a>
            </div>

            <div style="background-color: #f1f2f6; padding: 20px; border-radius: 8px; margin: 25px 0;">
                <h4 style="margin: 0 0 15px 0; color: #2d3436;">📊 Thống kê ứng tuyển</h4>
                <p style="margin: 0; color: #636e72;">
                    @if($applicationStats['is_first_application'])
                        🎉 Đây là ứng viên đầu tiên cho vị trí này! 
                    @else
                        📈 Có tổng cộng {{ $applicationStats['total'] }} ứng viên đã ứng tuyển vị trí này.
                    @endif
                    @if($applicationStats['today'] > 1)
                        Hôm nay có {{ $applicationStats['today'] }} ứng viên mới.
                    @endif
                </p>
            </div>

            <p style="margin-top: 30px; font-size: 14px; color: #6c757d;">
                <strong>Lưu ý:</strong> Bạn có thể trả lời trực tiếp email này để liên hệ với ứng viên.
                Email này được gửi tự động khi có ứng viên mới ứng tuyển.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 20px 0;">
                <strong>{{ config('mail.from.name', 'Làm Game') }} - Tuyển dụng</strong><br>
                Hệ thống quản lý ứng viên tự động
            </p>
            
            <p style="font-size: 12px; opacity: 0.8;">
                © {{ date('Y') }} {{ config('mail.from.name', 'Làm Game') }}. All rights reserved.<br>
                <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
            </p>
        </div>
    </div>
</body>
</html>