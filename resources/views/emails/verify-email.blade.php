<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Verifikasi Email Anda</title>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; background-color: #030712; color: #f1f5f9; margin: 0; padding: 0; }
        .wrapper { background-color: #030712; padding: 40px 20px; width: 100%; text-align: center; box-sizing: border-box; }
        .container { background-color: #0f172a; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px; max-width: 500px; margin: 0 auto; padding: 40px; text-align: center; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5); }
        .logo { font-family: 'Orbitron', 'Inter', Arial, sans-serif; font-size: 28px; font-weight: 900; color: #ffffff; letter-spacing: 2px; text-decoration: none; display: inline-block; margin-bottom: 30px; }
        .logo span { color: #3b82f6; }
        .title { font-size: 22px; font-weight: 800; color: #ffffff; margin-bottom: 20px; letter-spacing: 0.5px; }
        .message { font-size: 15px; color: #94a3b8; line-height: 1.6; margin-bottom: 30px; }
        .button { display: inline-block; background-color: #2563eb; color: #ffffff !important; font-size: 15px; font-weight: 700; text-decoration: none; padding: 14px 32px; border-radius: 8px; margin-bottom: 30px; border: 1px solid rgba(139, 92, 246, 0.3); }
        .button-wrapper { margin-bottom: 30px; }
        .footer { font-size: 12px; color: #475569; margin-top: 30px; border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 20px; }
        .fallback { font-size: 11px; color: #64748b; margin-top: 20px; word-break: break-all; text-align: left; background-color: rgba(255, 255, 255, 0.02); padding: 15px; border-radius: 8px; }
        .highlight { color: #ffffff; font-weight: 600; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div style="text-align: center; margin-bottom: 30px;">
                <img src="{{ config('app.url') }}/asset/logo-square.png" alt="GameShop Logo" style="width: 80px; height: 80px; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);">
                <div><a href="{{ config('app.url') }}" class="logo" style="margin-bottom: 0;">GAME<span>SHOP</span></a></div>
            </div>
            
            <div class="title">Verifikasi Alamat Email Anda</div>
            
            <div class="message">
                Halo <span class="highlight">{{ $user->name }}</span>,<br><br>
                Terima kasih telah bergabung! Untuk menyelesaikan proses pendaftaran dan mulai menjelajahi katalog game premium kami, harap verifikasi alamat email Anda.
            </div>
            
            <div class="button-wrapper">
                <a href="{{ $url }}" class="button">Verifikasi Email Sekarang</a>
            </div>
            
            <div class="message" style="margin-bottom: 10px; font-size: 13px;">
                Jika Anda tidak merasa mendaftar di GameShop, Anda dapat mengabaikan email ini.
            </div>
            
            <div class="fallback">
                Jika Anda mengalami masalah saat mengklik tombol "Verifikasi Email Sekarang", salin dan tempel URL di bawah ini ke browser web Anda:<br>
                <a href="{{ $url }}" style="color: #3b82f6; display: block; margin-top: 5px;">{{ $url }}</a>
            </div>
            
            <div class="footer">
                &copy; {{ date('Y') }} GameShop Dev. Seluruh hak cipta dilindungi.
            </div>
        </div>
    </div>
</body>
</html>
