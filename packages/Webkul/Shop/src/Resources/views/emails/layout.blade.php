<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    </head>

    <body style="font-family: 'Inter', sans-serif; margin: 0; padding: 0; background-color: #070B14;">
        <div style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 32px;">
                <a href="https://lamgame.vn" style="text-decoration: none;">
                    <span style="font-size: 24px; font-weight: 800; color: #F5F7FA; font-family: 'Inter', sans-serif;">LAMGAME<span style="color: #7C5CFF;">.VN</span></span>
                </a>
            </div>

            <!-- Content Card -->
            <div style="background-color: #111827; border: 1px solid rgba(124,92,255,0.12); border-radius: 12px; padding: 32px;">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div style="text-align: center; margin-top: 24px; padding: 16px 0;">
                <p style="font-size: 12px; color: #7A8599; margin: 0;">
                    © {{ date('Y') }} LAMGAME.VN — Cộng đồng Game Developer Việt Nam
                </p>
                <p style="font-size: 11px; color: #7A8599; margin: 4px 0 0;">
                    <a href="https://lamgame.vn" style="color: #7C5CFF; text-decoration: none;">lamgame.vn</a>
                </p>
            </div>
        </div>
    </body>
</html>
