<!DOCTYPE html>
<html lang="id" class="notranslate">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="google" content="notranslate">
    <title>Reset Kata Sandi - {{ config('app.name') }}</title>
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
            color: #ffffff
        }

        .email-logo {
            max-width: 180px;
            height: auto;
            object-fit: contain;
        }

        .email-content {
            padding: 30px;
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

        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #1B232F, #2A3342);
            color: white !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 500;
            margin: 25px 0;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(27, 35, 47, 0.2);
        }

        .security-note {
            font-size: 14px;
            color: #64748b;
            background-color: #f1f5f9;
            border-radius: 6px;
            padding: 12px;
            margin: 25px 0;
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

            .greeting {
                font-size: 17px;
            }

            .message {
                font-size: 14px;
            }

            .action-button {
                padding: 10px 25px;
                font-size: 14px;
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
            <h2>Reset Kata Sandi</h2>
        </div>

        <!-- Content -->
        <div class="email-content">
            <div class="greeting">Halo, {{ $name ?? 'Pengguna' }} 👋</div>

            <div class="message">
                Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda di
                <strong>{{ config('app.name') }}</strong>.
                Jika Anda tidak meminta hal ini, abaikan email ini.
            </div>

            <div style="text-align: center;">
                <a href="{{ route('password.update', ['token' => $token]) }}" class="action-button" target="_blank">
                    Reset Kata Sandi
                </a>
                <p style="font-size: 13px; color: #64748b; margin-top: 10px;">
                    Tombol di atas akan kedaluwarsa dalam 30 menit.
                </p>
            </div>

            <div class="security-note">
                <strong>Catatan Keamanan:</strong>
                <ul style="margin-top: 10px; padding-left: 20px;">
                    <li>Jangan berikan tautan ini kepada siapapun.</li>
                    <li>Pastikan Anda mengakses tautan hanya dari perangkat pribadi.</li>
                    <li>Setelah mengatur ulang kata sandi, gunakan kombinasi yang kuat.</li>
                </ul>
            </div>

            <div style="margin-top: 25px; text-align: center;">
                <p style="font-size: 13px; color: #64748b;">
                    Jika tombol tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:
                </p>
                <p style="font-size: 13px; word-break: break-all; color: #334155; margin-top: 10px;">
                    {{ route('password.update', ['token' => $token]) }}
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Semua Hak Dilindungi.</p>
            <p style="margin-top: 10px; font-size: 12px;">
                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>

</html>
