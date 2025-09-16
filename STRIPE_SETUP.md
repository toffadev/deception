# Configuration Stripe

## Ajout des variables d'environnement

Ajoutez ces variables à votre fichier `.env` :

```env
# Configuration Stripe
STRIPE_KEY=pk_test_votre_cle_publique_test
STRIPE_SECRET=sk_test_votre_cle_secrete_test
STRIPE_WEBHOOK_SECRET=whsec_votre_secret_webhook
```

## Obtenir vos clés Stripe

1. Connectez-vous à votre [Dashboard Stripe](https://dashboard.stripe.com/)
2. Allez dans **Développeurs > Clés API**
3. Copiez votre **Clé publique** dans `STRIPE_KEY`
4. Copiez votre **Clé secrète** dans `STRIPE_SECRET`

## Configuration des webhooks (optionnel)

Pour recevoir les notifications de paiement :

1. Allez dans **Développeurs > Webhooks**
2. Créez un nouveau webhook pointant vers : `https://votre-domaine.com/stripe/webhook`
3. Copiez le **Secret de signature** dans `STRIPE_WEBHOOK_SECRET`

## Commandes à exécuter

Après avoir mis à jour votre `.env` :

```bash
# Vider le cache des configurations
php artisan config:clear

# Redémarrer le serveur si nécessaire
php artisan serve
```

## Test en mode développement

Utilisez les clés de test (commencent par `pk_test_` et `sk_test_`) pour le développement.

En production, remplacez par les clés live (`pk_live_` et `sk_live_`).
