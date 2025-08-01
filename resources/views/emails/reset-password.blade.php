<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2d3748;
        }
        .message {
            margin-bottom: 30px;
            line-height: 1.8;
            color: #4a5568;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .reset-button:hover {
            transform: translateY(-1px);
        }
        .expiry-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        .security-note {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            color: #1565c0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .manual-link {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            word-break: break-all;
            font-size: 14px;
            color: #495057;
        }
        .app-name {
            font-weight: 700;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Réinitialisation de mot de passe</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Bonjour <strong>{{ $user->pseudo }}</strong> !
            </div>

            <div class="message">
                Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte sur <span class="app-name">{{ config('app.name', 'Laravel') }}</span>.
            </div>

            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    Réinitialiser mon mot de passe
                </a>
            </div>

            <div class="expiry-notice">
                ⏰ <strong>Attention :</strong> Ce lien de réinitialisation expirera dans <strong>60 minutes</strong>.
            </div>

            <div class="security-note">
                🛡️ <strong>Note de sécurité :</strong> Si vous n'avez pas demandé cette réinitialisation, aucune action n'est requise de votre part. Votre compte reste sécurisé.
            </div>

            <div class="message">
                Si vous rencontrez des difficultés avec le bouton ci-dessus, vous pouvez copier et coller le lien suivant dans votre navigateur :
            </div>

            <div class="manual-link">
                {{ $resetUrl }}
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                Cet email a été envoyé par <span class="app-name">{{ config('app.name', 'Laravel') }}</span><br>
                Si vous avez des questions, n'hésitez pas à nous contacter.
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #adb5bd;">
                © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>