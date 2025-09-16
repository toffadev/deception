📖 NOUVELLE PUBLICATION DISPONIBLE - GEMEINSAMES LICHT

Bonjour {{ $user->pseudo ?? 'Membre de la communauté' }},

Une nouvelle publication vient d'être validée et publiée sur notre plateforme Gemeinsames Licht.

=== PUBLICATION ===
Type: {{ $publicationTypeLabel }}
Titre: {{ $publication->title }}
Auteur: {{ $publicationAuthor }}

Extrait:
{{ $publicationExcerpt }}

Lire la publication complète:
{{ $publicationUrl }}

=== NOTRE COMMUNAUTÉ ===
Publications: {{ \App\Models\Publication::where('status', 'published')->count() }}
Membres: {{ \App\Models\User::count() }}
Commentaires: {{ \App\Models\Comment::where('status', 'published')->count() }}

Chaque témoignage partagé contribue à notre mission d'aide aux personnes malvoyantes ou aveugles au Bénin. Votre participation à cette communauté fait la différence dans la vie de nombreuses personnes.

=== LIENS UTILES ===
Toutes les publications: {{ $platformUrl }}/publication
Nos projets solidaires: {{ $platformUrl }}/solidarity
Nous contacter: {{ $platformUrl }}/contact

---
Gemeinsames Licht
Transformer la douleur en espoir pour les malvoyants du Bénin
{{ $platformUrl }}

Vous recevez cet email car vous êtes membre de notre communauté.
Pour vous désabonner: {{ $unsubscribeUrl }}