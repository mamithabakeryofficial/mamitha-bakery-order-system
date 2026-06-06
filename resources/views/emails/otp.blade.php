<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #8B4513; text-align: center; border-bottom: 2px solid #8B4513; padding-bottom: 10px;">Mamitha Bakery</h2>
        <p>Halo,</p>
        <p>Kami menerima permintaan untuk mereset password akun Anda di Mamitha Bakery.</p>
        <p>Berikut adalah kode OTP Anda. Kode ini berlaku selama <strong>1 Menit</strong>.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <div style="display: inline-block; background-color: #f8f9fa; border: 2px dashed #8B4513; padding: 15px 30px; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #8B4513; border-radius: 8px;">
                {{ $otp }}
            </div>
        </div>
        
        <p>Jika Anda tidak meminta reset password, Anda dapat mengabaikan email ini dengan aman.</p>
        <br>
        <p>Terima kasih,<br><strong>Tim Mamitha Bakery</strong></p>
    </div>
</body>
</html>
