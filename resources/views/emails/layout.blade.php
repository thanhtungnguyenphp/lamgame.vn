<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="margin: 0; padding: 0; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f4f6f8;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f6f8; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%); padding: 30px 40px; text-align: center;">
                            <a href="{{ config('app.url') }}" style="text-decoration: none;">
                                <img src="{{ asset('assets/logos/png/logo-horizontal-200.png') }}" alt="LamGame.vn" style="height: 50px; width: auto;" />
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            {{ $slot }}
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px 40px; border-top: 1px solid #e9ecef;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="text-align: center;">
                                        <p style="margin: 0 0 10px; font-size: 14px; color: #6c757d;">
                                            Cần hỗ trợ? Liên hệ với chúng tôi
                                        </p>
                                        <p style="margin: 0 0 15px;">
                                            <a href="mailto:support@lamgame.vn" style="color: #2c5f41; text-decoration: none; font-weight: 600;">
                                                📧 support@lamgame.vn
                                            </a>
                                            <span style="color: #dee2e6; margin: 0 10px;">|</span>
                                            <a href="tel:0911118300" style="color: #2c5f41; text-decoration: none; font-weight: 600;">
                                                📞 09.1111.8300
                                            </a>
                                        </p>
                                        <p style="margin: 0; font-size: 12px; color: #adb5bd;">
                                            © {{ date('Y') }} LamGame.vn - Nền tảng mua bán source code game
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
