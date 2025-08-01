# Configuration de l'environnement - Solidarité Cœur Brisé

## Variables d'environnement nécessaires

Ajoutez ces variables à votre fichier `.env` :

### Configuration Google OAuth (optionnel)

```bash
# Configuration Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

### Configuration Email (pour reset password)

```bash
# Configuration Email - Exemple avec Mailtrap pour les tests
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@solidaritecoeurbrise.org"
MAIL_FROM_NAME="Solidarité Cœur Brisé"
```

### Configuration Base de données

```bash
# Assurez-vous que votre base de données est configurée
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=deception
DB_USERNAME=root
DB_PASSWORD=
```

## Configuration Google OAuth (optionnel)

1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Créez ou sélectionnez un projet
3. Activez l'API Google+ ou Google Identity
4. Créez des identifiants OAuth 2.0
5. Ajoutez ces URLs de redirection autorisées :
    - `http://localhost:8000/auth/google/callback` (développement)
    - `https://votredomaine.com/auth/google/callback` (production)

## Commandes à exécuter

```bash
# Exécuter les migrations
php artisan migrate

# Exécuter les seeders (créer les utilisateurs par défaut)
php artisan db:seed

# Ou exécuter uniquement le seeder admin
php artisan db:seed --class=AdminUserSeeder

# Générer les clés d'application si pas déjà fait
php artisan key:generate

# Lancer le serveur de développement
php artisan serve

# Dans un autre terminal, compiler les assets
npm run dev
```

## Comptes par défaut créés

Après avoir exécuté les seeders, vous aurez :

### Administrateurs

-   **Email:** admin@solidaritecoeurbrise.org
-   **Mot de passe:** AdminSCB2024!

-   **Email:** superadmin@solidaritecoeurbrise.org
-   **Mot de passe:** SuperAdminSCB2024!

### Client de test

-   **Email:** client.test@exemple.com
-   **Mot de passe:** ClientTest2024!

## URLs d'accès

### Client (Frontend public)

-   Accueil : `http://localhost:8000/`
-   Inscription : `http://localhost:8000/auth/register`
-   Connexion : `http://localhost:8000/auth/login`
-   Mot de passe oublié : `http://localhost:8000/auth/forgot-password`

### Admin

-   Connexion admin : `http://localhost:8000/admin/auth/login`
-   Tableau de bord : `http://localhost:8000/admin/` (après connexion)
-   Mot de passe oublié admin : `http://localhost:8000/admin/auth/forgot-password`

## Sécurité

-   Les mots de passe sont hachés avec bcrypt
-   Protection CSRF activée sur tous les formulaires
-   Validation des rôles par middleware
-   Sessions sécurisées
-   Protection contre les attaques de réinitialisation de mot de passe

## Notes importantes

1. **Changez les mots de passe par défaut** en production
2. **Configurez un vrai service d'email** pour la production
3. **Activez HTTPS** en production
4. **Configurez les variables d'environnement Google** seulement si vous voulez l'auth Google
5. **Testez toutes les fonctionnalités** avant la mise en production
