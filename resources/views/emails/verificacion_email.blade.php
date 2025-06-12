<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verifica tu correo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0;padding:0;background:#f7fafc;font-family:'Montserrat',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f7fafc;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background:#fff;border-radius:12px;margin:32px 0;box-shadow:0 4px 18px #b3e7e2;">
                    <tr>
                        <td style="padding:32px 32px 16px 32px;text-align:center;">
                            <img src="{{ asset('images/logodef.png') }}" alt="Logo Autocaravanas Milan" style="height:64px;border-radius:8px;">
                            <h2 style="color:#0fab9f;font-weight:700;margin:24px 0 8px 0;font-size:2rem;">
                                ¡Verifica tu correo electrónico!
                            </h2>
                            <p style="color:#444436;font-size:17px;margin-bottom: 24px;">
                                ¡Hola{{ $user->name ? ', ' . $user->name : '' }}!<br>
                                Gracias por registrarte en <strong>Autocaravanas Milan</strong>.<br>
                                Por favor, haz clic en el botón para verificar tu dirección de correo electrónico y activar tu cuenta.
                            </p>
                            <a href="{{ $verificationUrl }}"
                               style="background:#0fab9f;color:#fff;text-decoration:none;padding:14px 34px;border-radius:6px;font-weight:600;display:inline-block;font-size:1.1rem;">
                                Verificar mi correo
                            </a>
                            <p style="color:#777;margin-top:36px;font-size:14px;">
                                Si no creaste una cuenta, puedes ignorar este mensaje.<br>
                                Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                                <span style="word-break:break-all;color:#0fab9f;">{{ $verificationUrl }}</span>
                            </p>
                            <hr style="margin:30px 0;border:0;border-top:1px solid #e0e0e0;">
                            <p style="color:#0fab9f;font-size:13px;margin-bottom:0;">
                                Autocaravanas Milan &copy; {{ date('Y') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>