<!DOCTYPE html>
<html lang="id" class="notranslate">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>{{ config('app.name') }} - Kode OTP Verifikasi</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #4a4a4a;
            line-height: 1.6;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .email-header {
            background: #1B232F;
            text-align: center;
            border-bottom: 4px solid #2A3342;
            padding: 40px 0;
            color: #ffffff;
        }

        .email-logo {
            max-width: 180px;
            height: auto;
            object-fit: contain;
        }

        .email-content {
            padding: 30px;
            text-align: center;
        }

        .greeting {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 20px;
            color: #1e293b;
        }

        .message {
            font-size: 15px;
            margin-bottom: 25px;
            color: #475569;
        }

        /* -- OTP Box: kembali ke versi lama (dashed + tebal) -- */
        .otp-box {
            font-size: 28px;
            font-weight: 700;
            color: #1B232F;
            letter-spacing: 8px;
            background: #f8fafc;
            border-radius: 8px;
            display: inline-block;
            padding: 15px 30px;
            margin: 25px 0;
            border: 2px dashed #1B232F;
        }

        /* -- Note: kembali ke versi lama, tapi dengan konsistensi padding dan font -- */
        .security-note {
            background: #f1f5f9;
            border-left: 4px solid #1B232F;
            padding: 15px;
            margin-top: 25px;
            border-radius: 4px;
            font-size: 14px;
            color: #64748b;
            text-align: left;
        }

        .email-footer {
            background-color: #f1f5f9;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }

        @media only screen and (max-width: 480px) {
            .email-content {
                padding: 20px !important;
            }

            .otp-box {
                font-size: 22px;
                letter-spacing: 5px;
                padding: 12px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="https://raw.githubusercontent.com/muhamdaily/assets/refs/heads/main/kembangin.png"
                alt="{{ config('app.name') }} Logo" class="email-logo">
            <h2>Kode Verifikasi OTP</h2>
        </div>

        <!-- Content -->
        <div class="email-content">
            <div class="greeting">Halo, {{ $nama ?? 'Pengguna' }} 👋</div>

            <div class="message">
                Gunakan kode OTP berikut untuk verifikasi akun Anda di <strong>{{ config('app.name') }}</strong>.
            </div>

            <div class="otp-box">{{ $otpCode }}</div>

            <div class="message">
                Kode ini hanya berlaku selama <strong>5 menit</strong>.<br>
                Jangan berikan kode ini kepada siapapun, termasuk pihak yang mengaku dari {{ config('app.name') }}.
            </div>

            <div class="security-note">
                <strong>Catatan Keamanan:</strong>
                Jika Anda tidak meminta kode OTP ini, abaikan pesan ini.
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Semua Hak Dilindungi.</p>
            <p style="margin-top: 10px; font-size: 12px;">
                Email ini dikirim otomatis. Mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>

</html>
