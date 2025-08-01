# Analyse CRUD pour le Dashboard Administrateur

## Opérations CRUD complètes nécessaires pour l'administrateur

1. **Tags** (`tags` table)

    - CRUD complet pour gérer les tags prédéfinis
    - Les administrateurs doivent pouvoir créer, mettre à jour et supprimer les tags utilisés sur la plateforme

2. **Partenaires** (`partners` table)

    - CRUD complet pour gérer les organisations partenaires
    - Les administrateurs doivent pouvoir ajouter de nouveaux partenaires, mettre à jour les informations, les logos et gérer le statut actif

3. **Projets de Solidarité** (`solidarity_projects` table)

    - CRUD complet pour créer et gérer les projets de solidarité
    - Lié à la table `project_media` pour gérer les images/vidéos du projet

4. **Médias des Projets** (`project_media` table)

    - Opérations CRUD dans le cadre de la gestion des projets de solidarité
    - Téléchargement et gestion des médias pour les projets

5. **Rapports Financiers** (`financial_reports` table)

    - CRUD complet pour créer et publier des rapports financiers
    - Les administrateurs doivent pouvoir télécharger des rapports et gérer la transparence financière

6. **Utilisateurs** (`users` table)

    - L'administrateur doit pouvoir visualiser, modifier et gérer les comptes utilisateurs
    - Possibilité de suspendre/bannir des utilisateurs et de changer les rôles

7. **Signalements** (`reports` table)
    - Système de révision administrateur pour le contenu signalé
    - Interface nécessaire pour visualiser les signalements et prendre des mesures (pas de création nécessaire)

## Gestion côté client (Affichage avec actions limitées)

1. **Commentaires** (`comments` table)

    - Les clients créent/modifient leurs propres commentaires
    - L'administrateur n'a besoin que de capacités de modération (approuver/masquer/supprimer)

2. **Réactions** (`reactions` table)

    - Réactions créées par les clients sur le contenu
    - L'administrateur n'a besoin que de visualiser les statistiques et éventuellement supprimer les réactions inappropriées

3. **Dons** (`donations` table)

    - Dons initiés par les clients
    - L'administrateur doit pouvoir visualiser, filtrer, générer des rapports, mais pas créer de nouveaux dons

4. **Tags de Publication** (`publication_tags` table)
    - Table de jonction gérée automatiquement lors de la gestion des publications et des tags
    - Pas de CRUD direct nécessaire, géré via la gestion des publications

## Déjà implémenté

-   **Publications** (`publications` table)
    -   Vous avez déjà implémenté le CRUD complet pour cette table

## Résumé

### CRUD complet nécessaire pour le Dashboard Admin:

-   Tags
-   Partenaires
-   Projets de Solidarité (avec Médias des Projets)
-   Rapports Financiers
-   Utilisateurs (avec capacités de modération)

### Dashboard Admin - Visualisation + Actions uniquement:

-   Commentaires (actions de modération)
-   Réactions (visualisation/suppression)
-   Dons (visualisation/rapports)
-   Signalements (révision/résolution)

### Tables de jonction (Gérées indirectement):

-   Tags de Publication
