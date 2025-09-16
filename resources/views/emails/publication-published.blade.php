<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle publication - Gemeinsames Licht</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #ef4444 0%, #ec4899 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.95;
        }
        
        .content {
            padding: 30px 20px;
        }
        
        .greeting {
            font-size: 18px;
            color: #1f2937;
            margin-bottom: 20px;
        }
        
        .publication-card {
            background: linear-gradient(135deg, #fef3f3 0%, #fdf2f8 100%);
            border-radius: 12px;
            padding: 25px;
            margin: 20px 0;
            border-left: 4px solid #ef4444;
        }
        
        .publication-type {
            display: inline-block;
            background: #ef4444;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        
        .publication-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        
        .publication-author {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 12px;
        }
        
        .publication-excerpt {
            color: #4b5563;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.3);
        }
        
        .cta-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 8px rgba(239, 68, 68, 0.4);
        }
        
        .stats-section {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        
        .stats-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 15px;
        }
        
        .stats-grid {
            display: flex;
            justify-content: space-around;
            gap: 15px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #ef4444;
            display: block;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .footer {
            background: #f3f4f6;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        
        .footer a {
            color: #ef4444;
            text-decoration: none;
            font-weight: 500;
        }
        
        .unsubscribe {
            margin-top: 15px;
            font-size: 12px;
        }
        
        .unsubscribe a {
            color: #9ca3af;
            text-decoration: underline;
        }
        
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            .publication-card {
                padding: 20px;
            }
            
            .stats-grid {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📖 Nouvelle publication disponible</h1>
            <p>Une nouvelle histoire vient d'être partagée dans notre communauté</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Bonjour {{ $user->pseudo ?? 'Membre de la communauté' }},
            </div>
            
            <p style="margin-bottom: 20px; color: #4b5563; font-size: 15px; line-height: 1.6;">
                Une nouvelle publication vient d'être validée et publiée sur Gemeinsames Licht. 
                Nous pensons qu'elle pourrait vous intéresser et vous apporter du réconfort ou de l'inspiration.
            </p>
            
            <!-- Publication Card -->
            <div class="publication-card">
                <span class="publication-type">{{ $publicationTypeLabel }}</span>
                <h2 class="publication-title">{{ $publication->title }}</h2>
                <p class="publication-author">Par {{ $publicationAuthor }}</p>
                <p class="publication-excerpt">{{ $publicationExcerpt }}</p>
                <a href="{{ $publicationUrl }}" class="cta-button">
                    ✨ Lire la publication
                </a>
            </div>
            
            <!-- Community Stats -->
            <div class="stats-section">
                <h3 class="stats-title">Notre communauté grandit chaque jour</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number">{{ \App\Models\Publication::where('status', 'published')->count() }}</span>
                        <span class="stat-label">Publications</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ \App\Models\User::count() }}</span>
                        <span class="stat-label">Membres</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ \App\Models\Comment::where('status', 'published')->count() }}</span>
                        <span class="stat-label">Commentaires</span>
                    </div>
                </div>
            </div>
            
            <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
                Chaque témoignage partagé contribue à notre mission d'aide aux personnes malvoyantes ou aveugles au Bénin. 
                Votre participation à cette communauté fait la différence dans la vie de nombreuses personnes.
            </p>
            
            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ $platformUrl }}/publication" style="color: #ef4444; text-decoration: none; font-weight: 500;">
                    🔍 Découvrir toutes les publications
                </a>
                |
                <a href="{{ $platformUrl }}/solidarity" style="color: #ef4444; text-decoration: none; font-weight: 500;">
                    💝 Nos projets solidaires
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>Gemeinsames Licht</strong><br>
                Transformer la douleur en espoir pour les malvoyants du Bénin
            </p>
            <p style="margin-top: 10px;">
                <a href="{{ $platformUrl }}">Visiter la plateforme</a> | 
                <a href="{{ $platformUrl }}/contact">Nous contacter</a>
            </p>
            
            <div class="unsubscribe">
                <p>Vous recevez cet email car vous êtes membre de notre communauté.</p>
                <p>
                    <a href="{{ $unsubscribeUrl }}">Se désabonner des notifications</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>