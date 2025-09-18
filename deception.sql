-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 16 sep. 2025 à 17:30
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `deception`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-home_page_data', 'a:7:{s:20:\"featuredPublications\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:3:{i:0;a:16:{s:2:\"id\";i:7;s:5:\"title\";s:18:\"Pour toi, toujours\";s:4:\"slug\";s:17:\"pour-toi-toujours\";s:4:\"type\";s:9:\"testimony\";s:7:\"excerpt\";s:153:\"Je n’ai pas cherché l’amour, c’est lui qui t’a choisi.Depuis, mon cœur ne respire plus sans lui.Ta voix est un murmure qui m’apaise.Ton re...\";s:11:\"author_name\";s:4:\"Sovi\";s:10:\"created_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2025-08-05 04:01:58.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:11:\"views_count\";i:0;s:14:\"comments_count\";i:0;s:16:\"donations_amount\";s:4:\"0.00\";s:15:\"reactions_count\";i:0;s:4:\"tags\";a:0:{}s:14:\"gradient_class\";s:41:\"bg-gradient-to-r from-pink-100 to-red-100\";s:10:\"icon_color\";s:12:\"text-red-300\";s:10:\"type_color\";s:12:\"text-red-500\";s:10:\"type_label\";s:11:\"Témoignage\";}i:1;a:16:{s:2:\"id\";i:6;s:5:\"title\";s:21:\"Chaque seconde de toi\";s:4:\"slug\";s:21:\"chaque-seconde-de-toi\";s:4:\"type\";s:10:\"reflection\";s:7:\"excerpt\";s:153:\"J’écris ton prénom sur chaque battement de mon cœur.Chaque seconde loin de toi me semble une erreur.Ton absence résonne comme un vide infini.Mai...\";s:11:\"author_name\";s:7:\"Anonyme\";s:10:\"created_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2025-08-05 03:48:44.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:11:\"views_count\";i:0;s:14:\"comments_count\";i:0;s:16:\"donations_amount\";s:4:\"0.00\";s:15:\"reactions_count\";i:0;s:4:\"tags\";a:0:{}s:14:\"gradient_class\";s:46:\"bg-gradient-to-r from-yellow-100 to-orange-100\";s:10:\"icon_color\";s:15:\"text-orange-300\";s:10:\"type_color\";s:15:\"text-orange-500\";s:10:\"type_label\";s:10:\"Réflexion\";}i:2;a:16:{s:2:\"id\";i:5;s:5:\"title\";s:19:\"À travers tes yeux\";s:4:\"slug\";s:18:\"a-travers-tes-yeux\";s:4:\"type\";s:6:\"poetry\";s:7:\"excerpt\";s:153:\"Quand je plonge dans ton regard, le monde cesse de tourner.Tes silences me parlent mieux que mille mots.Chaque battement de mon cœur danse à ton nom...\";s:11:\"author_name\";s:4:\"auri\";s:10:\"created_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2025-08-05 03:01:41.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:11:\"views_count\";i:0;s:14:\"comments_count\";i:0;s:16:\"donations_amount\";s:4:\"0.00\";s:15:\"reactions_count\";i:0;s:4:\"tags\";a:0:{}s:14:\"gradient_class\";s:44:\"bg-gradient-to-r from-purple-100 to-blue-100\";s:10:\"icon_color\";s:13:\"text-blue-300\";s:10:\"type_color\";s:13:\"text-blue-500\";s:10:\"type_label\";s:6:\"Poème\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:18:\"solidarityProjects\";a:15:{s:2:\"id\";i:3;s:5:\"title\";s:29:\"Activité sportive malvoyants\";s:11:\"description\";s:19:\"Distinctio Ut conse\";s:13:\"target_amount\";s:5:\"77.00\";s:14:\"current_amount\";s:5:\"69.00\";s:8:\"currency\";s:3:\"EUR\";s:19:\"progress_percentage\";d:89.6103896103896;s:16:\"remaining_amount\";d:8;s:12:\"is_completed\";b:0;s:18:\"featured_image_url\";s:80:\"/storage/solidarity-projects/images/qXNrcExKrdWkHgyS76Gp5BFqxlj8C8e1iEE9z7L4.jpg\";s:18:\"beneficiaries_info\";N;s:18:\"impact_description\";s:19:\"Iusto in architecto\";s:10:\"start_date\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2025-03-03 00:00:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:8:\"end_date\";N;s:5:\"media\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:8:\"partners\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:3:{i:0;a:7:{s:2:\"id\";i:4;s:4:\"name\";s:18:\"Enock le binguiste\";s:11:\"description\";s:8:\"Bingisto\";s:8:\"logo_url\";s:68:\"/storage/partners/logos/kXZZt02x5NJsmyzNuLbBr272jAccIFKAh2vdys2B.jpg\";s:11:\"website_url\";s:19:\"https://cursor.com/\";s:8:\"category\";s:11:\"association\";s:14:\"category_label\";s:11:\"Association\";}i:1;a:7:{s:2:\"id\";i:2;s:4:\"name\";s:9:\"Devo devo\";s:11:\"description\";s:10:\"descripion\";s:8:\"logo_url\";s:68:\"/storage/partners/logos/nfkucpz26bPcghvZfjDa2pJRmXc2cblUbIlqlYFB.jpg\";s:11:\"website_url\";s:19:\"https://cursor.com/\";s:8:\"category\";s:6:\"expert\";s:14:\"category_label\";s:6:\"Expert\";}i:2;a:7:{s:2:\"id\";i:3;s:4:\"name\";s:12:\"Romeo Gangba\";s:11:\"description\";s:11:\"Description\";s:8:\"logo_url\";s:68:\"/storage/partners/logos/mAhl5zkTjex5IbgdZEC3AziZTxc3kSzcVQmSdPQS.png\";s:11:\"website_url\";s:19:\"https://cursor.com/\";s:8:\"category\";s:7:\"mecenas\";s:14:\"category_label\";s:9:\"Mécénat\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:11:\"globalStats\";a:13:{s:18:\"total_publications\";i:7;s:11:\"total_views\";s:2:\"70\";s:14:\"total_comments\";s:1:\"5\";s:11:\"total_users\";i:7;s:15:\"total_donations\";s:6:\"249.00\";s:15:\"active_projects\";i:3;s:18:\"completed_projects\";i:3;s:13:\"people_helped\";i:0;s:11:\"annual_goal\";i:75000;s:22:\"current_year_donations\";s:6:\"249.00\";s:13:\"goal_progress\";d:0.332;s:15:\"efficiency_rate\";i:85;s:16:\"countries_helped\";i:5;}s:12:\"projectMedia\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:4:{i:0;a:7:{s:2:\"id\";i:2;s:4:\"type\";s:5:\"image\";s:8:\"file_url\";s:52:\"/storage/project-media/images/1754148294_media4.jpeg\";s:5:\"title\";s:20:\"Porro at non non eos\";s:11:\"description\";s:17:\"Recusandae Veniam\";s:8:\"alt_text\";s:17:\"Est eaque elit in\";s:13:\"project_title\";s:13:\"Joie de vivre\";}i:1;a:7:{s:2:\"id\";i:1;s:4:\"type\";s:5:\"image\";s:8:\"file_url\";s:52:\"/storage/project-media/images/1754148260_media2.jpeg\";s:5:\"title\";s:19:\"Fuga Quis nostrum u\";s:11:\"description\";s:19:\"Sed fuga Qui incidi\";s:8:\"alt_text\";s:20:\"Voluptate nihil ea e\";s:13:\"project_title\";s:13:\"Joie de vivre\";}i:2;a:7:{s:2:\"id\";i:4;s:4:\"type\";s:5:\"image\";s:8:\"file_url\";s:52:\"/storage/project-media/images/1754148386_media3.jpeg\";s:5:\"title\";s:20:\"Officiis quas a pari\";s:11:\"description\";s:20:\"Et quia eaque ullamc\";s:8:\"alt_text\";s:20:\"Nulla veritatis veni\";s:13:\"project_title\";s:13:\"Joie de vivre\";}i:3;a:7:{s:2:\"id\";i:3;s:4:\"type\";s:5:\"image\";s:8:\"file_url\";s:62:\"/storage/project-media/images/1754148324_téléchargement.jpeg\";s:5:\"title\";s:19:\"Eaque facilis velit\";s:11:\"description\";s:19:\"Amet natus eaque un\";s:8:\"alt_text\";s:19:\"Consequatur volupta\";s:13:\"project_title\";s:18:\"Education scolaire\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:15:\"recentDonations\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;a:8:{s:2:\"id\";i:9;s:6:\"amount\";s:5:\"10.00\";s:8:\"currency\";s:3:\"EUR\";s:10:\"donor_name\";s:8:\"ToffaDev\";s:4:\"type\";s:8:\"platform\";s:12:\"processed_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2025-08-05 03:19:10.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:17:\"publication_title\";N;s:7:\"message\";N;}i:1;a:8:{s:2:\"id\";i:8;s:6:\"amount\";s:4:\"3.00\";s:8:\"currency\";s:3:\"EUR\";s:10:\"donor_name\";s:8:\"ToffaDev\";s:4:\"type\";s:13:\"blind_support\";s:12:\"processed_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2025-08-03 08:17:57.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:17:\"publication_title\";s:28:\"Ce que la douleur m\'a appris\";s:7:\"message\";N;}i:2;a:8:{s:2:\"id\";i:7;s:6:\"amount\";s:6:\"200.00\";s:8:\"currency\";s:3:\"EUR\";s:10:\"donor_name\";s:8:\"ToffaDev\";s:4:\"type\";s:13:\"blind_support\";s:12:\"processed_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2025-08-03 04:14:40.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:17:\"publication_title\";s:20:\"Les mots qui restent\";s:7:\"message\";N;}i:3;a:8:{s:2:\"id\";i:6;s:6:\"amount\";s:4:\"3.00\";s:8:\"currency\";s:3:\"EUR\";s:10:\"donor_name\";s:8:\"ToffaDev\";s:4:\"type\";s:13:\"blind_support\";s:12:\"processed_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2025-08-02 09:53:36.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:17:\"publication_title\";s:28:\"Ce que la douleur m\'a appris\";s:7:\"message\";N;}i:4;a:8:{s:2:\"id\";i:5;s:6:\"amount\";s:5:\"10.00\";s:8:\"currency\";s:3:\"EUR\";s:10:\"donor_name\";s:8:\"ToffaDev\";s:4:\"type\";s:8:\"platform\";s:12:\"processed_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2025-08-02 02:20:03.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:17:\"publication_title\";N;s:7:\"message\";N;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:21:\"visuallyImpairedStats\";a:5:{s:15:\"total_personnes\";i:1;s:6:\"hommes\";i:0;s:6:\"femmes\";i:1;s:13:\"en_traitement\";i:0;s:20:\"derniere_mise_a_jour\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2025-08-05 01:45:31.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}}', 1754505767);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `publication_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `content` text NOT NULL,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('published','moderated','hidden') NOT NULL DEFAULT 'published',
  `moderation_reason` text DEFAULT NULL,
  `moderated_at` timestamp NULL DEFAULT NULL,
  `moderated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `publication_id`, `parent_id`, `content`, `is_anonymous`, `status`, `moderation_reason`, `moderated_at`, `moderated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 3, NULL, 'C\'est bien ton histoire', 0, 'published', NULL, NULL, NULL, '2025-08-01 21:06:45', '2025-08-01 21:06:45', NULL),
(2, 5, 3, 1, 'tu pense que c\'est le cas', 1, 'published', NULL, NULL, NULL, '2025-08-01 21:09:17', '2025-08-01 21:09:17', NULL),
(3, 5, 3, 1, 'Je vois très bien le problème', 1, 'published', NULL, NULL, NULL, '2025-08-01 21:10:32', '2025-08-01 21:10:32', NULL),
(4, 5, 3, 1, 'très bien bien bien', 0, 'published', NULL, NULL, NULL, '2025-08-02 07:52:16', '2025-08-02 07:52:16', NULL),
(5, 5, 3, NULL, 'How are you ? Fine', 0, 'published', NULL, NULL, NULL, '2025-08-02 07:52:50', '2025-08-02 07:52:50', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `donations`
--

CREATE TABLE `donations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('platform','blind_support') NOT NULL,
  `publication_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'EUR',
  `frequency` enum('one_time','monthly') NOT NULL DEFAULT 'one_time',
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','completed','failed','cancelled') NOT NULL DEFAULT 'pending',
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `anonymous_donor_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`anonymous_donor_info`)),
  `tax_receipt_requested` tinyint(1) NOT NULL DEFAULT 0,
  `tax_receipt_path` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `next_payment_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `donations`
--

INSERT INTO `donations` (`id`, `user_id`, `type`, `publication_id`, `amount`, `currency`, `frequency`, `stripe_payment_intent_id`, `stripe_subscription_id`, `status`, `is_anonymous`, `anonymous_donor_info`, `tax_receipt_requested`, `tax_receipt_path`, `message`, `processed_at`, `next_payment_at`, `created_at`, `updated_at`) VALUES
(1, 5, 'blind_support', 3, 3.00, 'EUR', 'one_time', 'pi_3RrS33JdpxiVKv8F1ugzXfsf', NULL, 'pending', 0, NULL, 0, NULL, 'bien', NULL, NULL, '2025-08-01 21:07:31', '2025-08-01 21:07:40'),
(2, 5, 'blind_support', 3, 3.00, 'EUR', 'one_time', 'cs_test_a1fRWVbSdkkOwIWDVkrgjz3wRYxA4pgL1qEapCu80cfKkV45FWfChRPLav', NULL, 'completed', 0, NULL, 0, NULL, 'MERCI', '2025-08-01 22:18:58', NULL, '2025-08-01 21:53:19', '2025-08-01 22:18:58'),
(3, 5, 'platform', NULL, 10.00, 'EUR', 'one_time', 'cs_test_a1km3FO37Rnhcy9iDAEyvBbBht786orzNZxhyCejyLcDkHiQFVErz33y8x', NULL, 'completed', 0, NULL, 0, NULL, NULL, '2025-08-02 00:09:18', NULL, '2025-08-02 00:04:21', '2025-08-02 00:09:18'),
(4, 5, 'platform', NULL, 10.00, 'EUR', 'one_time', 'cs_test_a1PsZzgpgNjoDyx9lcTnhz97wX2sURfuyw1U9HRPOfpvFkGhHHRVRsb1tz', NULL, 'completed', 0, NULL, 0, NULL, NULL, '2025-08-02 00:13:52', NULL, '2025-08-02 00:10:50', '2025-08-02 00:13:52'),
(5, 5, 'platform', NULL, 10.00, 'EUR', 'one_time', 'cs_test_a1ozsI4mR0QGgZNJF7jcqCSe7xGpmAMiYizIFJnyz2gwivYqNA3WhJzQkg', NULL, 'completed', 0, NULL, 0, NULL, NULL, '2025-08-02 00:20:03', NULL, '2025-08-02 00:15:04', '2025-08-02 00:20:03'),
(6, 5, 'blind_support', 3, 3.00, 'EUR', 'one_time', 'cs_test_a1N59mXxaJftvAxlGLK4CqLaGTJH87a4fEQCSvLPXjiQpaSidtTdepEMEZ', NULL, 'completed', 0, NULL, 0, NULL, NULL, '2025-08-02 07:53:36', NULL, '2025-08-02 07:53:18', '2025-08-02 07:53:36'),
(7, 5, 'blind_support', 2, 200.00, 'EUR', 'one_time', 'cs_test_a1ILHeKkY3gR5EBTwG8IAGLxxzf0reSVEnJhLwR05spXnXV426YDe40u6i', NULL, 'completed', 0, NULL, 0, NULL, NULL, '2025-08-03 02:14:40', NULL, '2025-08-03 02:13:36', '2025-08-03 02:14:40'),
(8, 5, 'blind_support', 3, 3.00, 'EUR', 'one_time', 'cs_test_a1MP1Gq3nuWLivXNTkQIaBi19tqcHljMO1w8qgMhBZ7H7AJ6RnVbYZfG64', NULL, 'completed', 0, NULL, 0, NULL, NULL, '2025-08-03 06:17:57', NULL, '2025-08-03 06:17:26', '2025-08-03 06:17:57'),
(9, 5, 'platform', NULL, 10.00, 'EUR', 'one_time', 'cs_test_a1NsHHE22KFrR09QzSbqu7fY92MJudteuoUf5IrR7PAFeQyiLeLgu0WAKI', NULL, 'completed', 0, NULL, 0, NULL, NULL, '2025-08-05 01:19:10', NULL, '2025-08-05 01:17:37', '2025-08-05 01:19:10');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `financial_reports`
--

CREATE TABLE `financial_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `period_type` enum('monthly','quarterly','yearly') NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `total_donations` decimal(12,2) NOT NULL,
  `total_expenses` decimal(12,2) NOT NULL,
  `administrative_costs` decimal(12,2) NOT NULL,
  `breakdown` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`breakdown`)),
  `report_file_path` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_24_214725_create_publications_table', 1),
(5, '2025_07_24_214845_create_tags_table', 1),
(6, '2025_07_24_214926_create_publication_tags_table', 1),
(7, '2025_07_24_215003_create_comments_table', 1),
(8, '2025_07_24_215041_create_reactions_table', 1),
(9, '2025_07_24_215151_create_donations_table', 1),
(10, '2025_07_24_215233_create_partners_table', 1),
(11, '2025_07_24_215321_create_reports_table', 1),
(12, '2025_07_24_215404_create_solidarity_projects_table', 1),
(13, '2025_07_24_215446_create_project_media_table', 1),
(14, '2025_07_24_215808_create_financial_reports_table', 1),
(15, '2025_07_25_100214_create_personal_access_tokens_table', 1),
(16, '2025_08_01_152507_add_slug_to_publications_table', 2),
(17, '2025_08_05_005951_create_visually_impaired_people_table', 3),
(18, '2025_08_05_022211_add_notification_fields_to_users_table', 4);

-- --------------------------------------------------------

--
-- Structure de la table `partners`
--

CREATE TABLE `partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `category` enum('mecenas','association','expert') NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `partners`
--

INSERT INTO `partners` (`id`, `name`, `description`, `logo_path`, `website_url`, `category`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Devo devo', 'descripion', 'partners/logos/nfkucpz26bPcghvZfjDa2pJRmXc2cblUbIlqlYFB.jpg', 'https://cursor.com/', 'expert', 2, 1, '2025-08-02 07:46:48', '2025-08-02 07:46:48'),
(3, 'Romeo Gangba', 'Description', 'partners/logos/mAhl5zkTjex5IbgdZEC3AziZTxc3kSzcVQmSdPQS.png', 'https://cursor.com/', 'mecenas', 3, 1, '2025-08-02 07:47:49', '2025-08-02 07:47:49'),
(4, 'Enock le binguiste', 'Bingisto', 'partners/logos/kXZZt02x5NJsmyzNuLbBr272jAccIFKAh2vdys2B.jpg', 'https://cursor.com/', 'association', 0, 1, '2025-08-02 07:48:45', '2025-08-02 07:48:45');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('devincetoffa99@gmail.com', '$2y$12$pT1WlrEDtrrTvwehVWbfs.ddlKW/qvT5Wih4Zd.CA3DUWR3gm.YUK', '2025-07-31 11:18:43');

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `project_media`
--

CREATE TABLE `project_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `solidarity_project_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('image','video') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `project_media`
--

INSERT INTO `project_media` (`id`, `solidarity_project_id`, `type`, `file_path`, `title`, `description`, `alt_text`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'image', 'project-media/images/1754148260_media2.jpeg', 'Fuga Quis nostrum u', 'Sed fuga Qui incidi', 'Voluptate nihil ea e', 1, '2025-08-02 13:24:20', '2025-08-02 14:08:45'),
(2, 1, 'image', 'project-media/images/1754148294_media4.jpeg', 'Porro at non non eos', 'Recusandae Veniam', 'Est eaque elit in', 0, '2025-08-02 13:24:54', '2025-08-03 06:38:03'),
(3, 2, 'image', 'project-media/images/1754148324_téléchargement.jpeg', 'Eaque facilis velit', 'Amet natus eaque un', 'Consequatur volupta', 5, '2025-08-02 13:25:24', '2025-08-02 14:09:12'),
(4, 1, 'image', 'project-media/images/1754148386_media3.jpeg', 'Officiis quas a pari', 'Et quia eaque ullamc', 'Nulla veritatis veni', 4, '2025-08-02 13:26:26', '2025-08-02 13:26:26');

-- --------------------------------------------------------

--
-- Structure de la table `publications`
--

CREATE TABLE `publications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `custom_author_name` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` enum('testimony','reflection','poetry') NOT NULL,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('draft','published','moderated','hidden') NOT NULL DEFAULT 'published',
  `views_count` int(11) NOT NULL DEFAULT 0,
  `comments_count` int(11) NOT NULL DEFAULT 0,
  `reactions_count` int(11) NOT NULL DEFAULT 0,
  `donations_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `auto_tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`auto_tags`)),
  `moderation_reason` text DEFAULT NULL,
  `moderated_at` timestamp NULL DEFAULT NULL,
  `moderated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `publications`
--

INSERT INTO `publications` (`id`, `user_id`, `custom_author_name`, `title`, `slug`, `content`, `type`, `is_anonymous`, `status`, `views_count`, `comments_count`, `reactions_count`, `donations_amount`, `auto_tags`, `moderation_reason`, `moderated_at`, `moderated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, NULL, 'Le jour où tout s\'est effondré', 'le-jour-ou-tout-sest-effondre', 'Aprs cinq ans de relation, il est parti sans explication. Les premiers jours ont t les plus durs, je ne mangeais plus, je ne dormais plus. Puis j&#39;ai dcouvert cette communaut et j&#39;ai ralis que je n&#39;tais pas seule. Vos messages m&#39;ont aide  tenir,  comprendre que cette douleur tait temporaire. Aujourd&#39;hui, je commence  voir la lumire au bout du tunnel', 'testimony', 0, 'published', 0, 0, 0, 0.00, '[\"t\\u00e9moignage\",\"douleur\",\"espoir\",\"solitude\"]', NULL, NULL, NULL, '2025-08-01 12:47:11', '2025-08-01 14:24:59', NULL),
(2, 5, NULL, 'Les mots qui restent', 'les-mots-qui-restent', 'Tes yeux &eacute;taient mon ciel &eacute;toil&eacute;,Ta voix, ma m&eacute;lodie pr&eacute;f&eacute;r&eacute;e.Aujourd&#39;hui le silence a tout engloutiTes yeux &eacute;taient mon ciel &eacute;toil&eacute;,Ta voix, ma m&eacute;lodie pr&eacute;f&eacute;r&eacute;e.Aujourd&#39;hui le silence a tout englouti', 'poetry', 0, 'published', 12, 0, 0, 200.00, '[\"po\\u00e9sie\"]', NULL, NULL, NULL, '2025-08-01 15:15:53', '2025-08-03 02:14:40', NULL),
(3, 1, NULL, 'Ce que la douleur m\'a appris', 'ce-que-la-douleur-ma-appris', 'Depuis que je t&rsquo;ai rencontr&eacute;, mon monde a chang&eacute;. Ton regard apaise mes col&egrave;res, ta voix rassure mes silences. Je ne croyais plus en l&rsquo;amour avant toi, mais chaque jour &agrave; tes c&ocirc;t&eacute;s me prouve que le bonheur existe, tout simplement dans la pr&eacute;sence de l&rsquo;autre.', 'testimony', 0, 'published', 34, 5, 1, 21.00, '[\"t\\u00e9moignage\"]', NULL, NULL, NULL, '2025-08-01 15:38:49', '2025-08-03 06:31:58', NULL),
(4, 5, NULL, 'J\'aime bien les femmes', 'jaime-bien-les-femmes', 'Dans la premi&egrave;re section de la page, le titre &#39;Plus de 3 t&eacute;moignages partag&eacute;s&#39; est trop coll&eacute; au haut de la section en mode responsive, ce qui nuit &agrave; la lisibilit&eacute;. De plus, le bouton &#39;Contribuer au projet&#39; est &eacute;galement trop proche du bas de la section, et une partie de celui-ci peut m&ecirc;me &ecirc;tre masqu&eacute;e', 'testimony', 0, 'published', 24, 0, 0, 0.00, '[\"t\\u00e9moignage\"]', NULL, NULL, NULL, '2025-08-03 06:48:25', '2025-08-04 23:46:01', NULL),
(5, 7, NULL, 'À travers tes yeux', 'a-travers-tes-yeux', 'Quand je plonge dans ton regard, le monde cesse de tourner.Tes silences me parlent mieux que mille mots.Chaque battement de mon c&oelig;ur danse &agrave; ton nom.Tu es ma douceur dans les jours de temp&ecirc;te.Aimer, avec toi, n&rsquo;a jamais &eacute;t&eacute; une question.C&rsquo;est une &eacute;vidence grav&eacute;e au fond de mon &acirc;me.', 'poetry', 0, 'published', 0, 0, 0, 0.00, '[\"po\\u00e9sie\"]', NULL, NULL, NULL, '2025-08-05 01:01:41', '2025-08-05 01:01:41', NULL),
(6, 3, NULL, 'Chaque seconde de toi', 'chaque-seconde-de-toi', 'J&rsquo;&eacute;cris ton pr&eacute;nom sur chaque battement de mon c&oelig;ur.Chaque seconde loin de toi me semble une erreur.Ton absence r&eacute;sonne comme un vide infini.Mais ton amour remplit tout ce qui m&rsquo;ennuie.Tu es mon souffle, mon rep&egrave;re, mon abri.Ma plus belle histoire, c&rsquo;est celle que je vis ici.', 'reflection', 1, 'published', 0, 0, 0, 0.00, '[\"r\\u00e9flexion\"]', NULL, NULL, NULL, '2025-08-05 01:48:44', '2025-08-05 01:48:44', NULL),
(7, NULL, 'Sovi', 'Pour toi, toujours', 'pour-toi-toujours', 'Je n&rsquo;ai pas cherch&eacute; l&rsquo;amour, c&rsquo;est lui qui t&rsquo;a choisi.Depuis, mon c&oelig;ur ne respire plus sans lui.Ta voix est un murmure qui m&rsquo;apaise.Ton regard, un lieu o&ugrave; rien ne me blesse.Je t&rsquo;aime sans raison, sans mesure, sans d&eacute;tour.Et je t&rsquo;aimerai encore, pour toujours.', 'testimony', 0, 'published', 0, 0, 0, 0.00, '[\"t\\u00e9moignage\"]', NULL, NULL, NULL, '2025-08-05 02:01:58', '2025-08-05 02:01:58', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `publication_tags`
--

CREATE TABLE `publication_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `publication_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `publication_tags`
--

INSERT INTO `publication_tags` (`id`, `publication_id`, `tag_id`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '2025-08-01 12:47:11', '2025-08-01 12:47:11'),
(2, 1, 3, '2025-08-01 12:47:11', '2025-08-01 12:47:11'),
(3, 1, 8, '2025-08-01 12:47:11', '2025-08-01 12:47:11'),
(4, 2, 4, '2025-08-01 15:15:53', '2025-08-01 15:15:53'),
(5, 2, 3, '2025-08-01 15:15:53', '2025-08-01 15:15:53'),
(6, 2, 11, '2025-08-01 15:15:53', '2025-08-01 15:15:53');

-- --------------------------------------------------------

--
-- Structure de la table `reactions`
--

CREATE TABLE `reactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reactable_type` varchar(255) NOT NULL,
  `reactable_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('heart','cry','pray','thank_you','understand','courage') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reactions`
--

INSERT INTO `reactions` (`id`, `user_id`, `reactable_type`, `reactable_id`, `type`, `created_at`, `updated_at`) VALUES
(1, 5, 'App\\Models\\Comment', 1, 'cry', '2025-08-01 21:08:35', '2025-08-01 21:08:35'),
(2, 5, 'App\\Models\\Comment', 1, 'pray', '2025-08-02 07:51:47', '2025-08-02 07:51:47'),
(3, 5, 'App\\Models\\Comment', 1, 'heart', '2025-08-02 07:51:51', '2025-08-02 07:51:51'),
(5, 5, 'App\\Models\\Publication', 3, 'cry', '2025-08-02 13:01:13', '2025-08-02 13:01:13');

-- --------------------------------------------------------

--
-- Structure de la table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reporter_id` bigint(20) UNSIGNED NOT NULL,
  `reportable_type` varchar(255) NOT NULL,
  `reportable_id` bigint(20) UNSIGNED NOT NULL,
  `reason` enum('inappropriate_content','harassment','spam','violence','hate_speech','misinformation','other') NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','reviewed','resolved','dismissed') NOT NULL DEFAULT 'pending',
  `moderator_notes` text DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9cK6c0exYe2bYGYQp8PEiHDjRHEEK3kxU8gqcbIQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiRG5mcTI4WWV6U0NBTHBpWlBFTDJmVks1TlhGVW8yWEdlV0xlMzdwdCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1754422886),
('asHjbEKKAPj0PSL77oiLdPEWTAKWx0YElQpyWM4q', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiMTJURnRXaUxCdThWbHlBVDBNMG5SczRFMzliQzFiWTIyQnB4dmE4eSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1754440761),
('J7lSjysh60YfcB9Yg6ifYBsiWnfsHdZbGz6TEx2A', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoieVc2S3Y3T0pXejI1UkZ6MnhNeElvMmo2YnY0d2hXTzdnRGxrSm1LYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1754422766),
('KClAjLpxVUiuiUzT8JvP5igUuCNmA0TfXUrpFphQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWJkSmNEZ3dQYlhqdnBlalRESGFOa2I3WDFlaWFCZEpGOGE3dDgweCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1754505267),
('soVdpCLmI2nyOklAeAM7GkB5ciSy9h29YFCbkVsq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVmlSeUhiZFZyamNpVkFhM3pmZlZjTXk4dDNmQlpwTE9yWEFqcHlpaiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Zpc3VhbGx5LWltcGFpcmVkIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hdXRoL2xvZ2luIjt9fQ==', 1754440441),
('W0eXsBhK3ndQLDO3X5aWgwsuIL0zrvSbt9FZ3BvN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVDBteXBqejR3bzJIY2ZuSDBGMDRKTExsdVYwMThNMk5qUzFsT2NnZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1754404773),
('zBOm9tH3apuat29KCFUWSEWdc1nh0xLgbXKySQWA', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ0Q1T25iWjNZT1FsSWZzY2JPRzRQMUZsdkFaTnFNUGxsSHV1NlY2eCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Zpc3VhbGx5LWltcGFpcmVkIjt9fQ==', 1754404892);

-- --------------------------------------------------------

--
-- Structure de la table `solidarity_projects`
--

CREATE TABLE `solidarity_projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `target_amount` decimal(12,2) NOT NULL,
  `current_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'EUR',
  `status` enum('planned','active','completed','paused') NOT NULL DEFAULT 'planned',
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `featured_image_path` varchar(255) DEFAULT NULL,
  `beneficiaries_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`beneficiaries_info`)),
  `impact_description` text DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `solidarity_projects`
--

INSERT INTO `solidarity_projects` (`id`, `title`, `description`, `target_amount`, `current_amount`, `currency`, `status`, `start_date`, `end_date`, `featured_image_path`, `beneficiaries_info`, `impact_description`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 'Joie de vivre', 'Fuga Facere alias d', 35.00, 95.00, 'USD', 'active', '2025-08-03', NULL, 'solidarity-projects/images/70JxGROsmw4fQqM8LUzw6BAlbrbVrxz6SUmSLfYm.jpg', NULL, 'Iste nobis nisi dolo', 1, '2025-08-02 08:57:45', '2025-08-03 06:35:18'),
(2, 'Education scolaire', 'Itaque quos deleniti', 6.00, 72.00, 'XOF', 'active', '2025-08-02', NULL, 'solidarity-projects/images/7xShNgBGmpUmupF4fNiznvkeH544aOc2jb6XEcgB.jpg', NULL, 'Accusantium laborum', 1, '2025-08-02 13:14:24', '2025-08-02 14:10:34'),
(3, 'Activité sportive malvoyants', 'Distinctio Ut conse', 77.00, 69.00, 'EUR', 'active', '2025-03-03', NULL, 'solidarity-projects/images/qXNrcExKrdWkHgyS76Gp5BFqxlj8C8e1iEE9z7L4.jpg', NULL, 'Iusto in architecto', 1, '2025-08-02 13:15:19', '2025-08-03 06:40:23'),
(4, 'Et nisi enim in dese', 'Deserunt inventore d', 70.00, 74.00, 'XOF', 'planned', '2025-08-25', NULL, NULL, NULL, 'Aspernatur et sequi', 1, '2025-08-02 13:16:17', '2025-08-02 13:16:32');

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#3B82F6',
  `usage_count` int(11) NOT NULL DEFAULT 0,
  `is_suggested` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `color`, `usage_count`, `is_suggested`, `created_at`, `updated_at`) VALUES
(1, 'rupture', 'rupture', '#EF4444', 10, 1, '2025-08-01 12:41:38', '2025-08-01 12:41:38'),
(2, 'douleur', 'douleur', '#7C3AED', 8, 1, '2025-08-01 12:41:38', '2025-08-01 12:41:38'),
(3, 'espoir', 'espoir', '#10B981', 14, 1, '2025-08-01 12:41:38', '2025-08-01 15:15:53'),
(4, 'amour', 'amour', '#F59E0B', 17, 1, '2025-08-01 12:41:38', '2025-08-01 15:15:53'),
(5, 'solitude', 'solitude', '#6B7280', 9, 1, '2025-08-01 12:41:38', '2025-08-01 12:41:38'),
(6, 'reconstruction', 'reconstruction', '#3B82F6', 7, 1, '2025-08-01 12:41:38', '2025-08-01 12:41:38'),
(7, 'trahison', 'trahison', '#DC2626', 6, 1, '2025-08-01 12:41:38', '2025-08-01 12:41:38'),
(8, 'résilience', 'resilience', '#059669', 12, 1, '2025-08-01 12:41:38', '2025-08-01 12:47:11'),
(9, 'témoignage', 'temoignage', '#EC4899', 20, 1, '2025-08-01 12:41:38', '2025-08-01 12:41:38'),
(10, 'réflexion', 'reflexion', '#8B5CF6', 13, 1, '2025-08-01 12:41:38', '2025-08-01 12:41:38'),
(11, 'poésie', 'poesie', '#06B6D4', 9, 1, '2025-08-01 12:41:38', '2025-08-01 15:15:53'),
(12, 'guérison', 'guerison', '#16A34A', 9, 1, '2025-08-01 12:41:38', '2025-08-01 12:41:38'),
(13, 'nostalgie', 'nostalgie', '#9333EA', 5, 1, '2025-08-01 12:41:38', '2025-08-01 12:41:38'),
(14, 'pardon', 'pardon', '#0EA5E9', 6, 1, '2025-08-01 12:41:38', '2025-08-01 12:41:38'),
(15, 'force', 'force', '#DC2626', 7, 1, '2025-08-01 12:41:38', '2025-08-01 12:41:38');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('client','admin') NOT NULL DEFAULT 'client',
  `pseudo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `birth_date` date NOT NULL,
  `status` enum('active','suspended','banned') NOT NULL DEFAULT 'active',
  `charter_accepted_at` timestamp NULL DEFAULT NULL,
  `anonymous_by_default` tinyint(1) NOT NULL DEFAULT 0,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `auth_provider` enum('local','google') NOT NULL DEFAULT 'local',
  `notification_preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notification_preferences`)),
  `email_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `unsubscribe_token` varchar(255) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `role`, `pseudo`, `email`, `email_verified_at`, `password`, `birth_date`, `status`, `charter_accepted_at`, `anonymous_by_default`, `google_id`, `avatar`, `auth_provider`, `notification_preferences`, `email_notifications`, `unsubscribe_token`, `last_login_at`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'Admin Principal', 'admin@solidaritecoeurbrise.org', '2025-07-31 07:18:06', '$2y$12$MWpsB3vPEkKrChtTYCyQNeTos5CijOJ1CZ/pPWpKqhX5DxicLg8Vq', '1995-07-31', 'active', '2025-07-31 07:18:06', 0, NULL, NULL, 'local', NULL, 1, '3yRdVo8OdqsySdkTsPFhSOSOU1Y7igVU67oNU0FBFE6dQTVFeX8LYdEAOzcA', NULL, NULL, '2025-07-31 07:18:06', '2025-08-05 00:30:17', NULL),
(2, 'admin', 'Super Admin', 'superadmin@solidaritecoeurbrise.org', '2025-07-31 07:18:07', '$2y$12$3Co.LyOG3fztGP/pPFfKg.2Rzw3lmwcm0mHkGRaW1zyhy3W8Tl.Oy', '1990-07-31', 'active', '2025-07-31 07:18:07', 0, NULL, NULL, 'local', NULL, 1, '4M9lL6BBpg8Hc0gcWBXTJIeRizamcn4xxb0iUsChMA8b36FLhpQ4RMgYdwYS', NULL, NULL, '2025-07-31 07:18:07', '2025-08-05 00:30:17', NULL),
(3, 'admin', 'Admin Toffadev', 'admin@gmail.com', '2025-07-31 07:18:08', '$2y$12$29qChvEaFa4Fa92geq.4w.7IWrfZ4.LLGQVK2JxK/3U1ULHJh48sC', '1997-07-31', 'active', '2025-07-31 07:18:08', 0, NULL, NULL, 'local', NULL, 1, 'oOYC0k2Smjy2i8pTXckYx71rG87H1mQZnKEbwmtqnpDXrH4dHolpAlq0Z6Zf', '2025-08-05 08:01:33', 'gmbS8Dt3SqLzmVnIwf9tK4xUoCcLospONjcrIgxFqprUXih5gt80fluWkXEg', '2025-07-31 07:18:08', '2025-08-05 08:01:33', NULL),
(4, 'client', 'Client Test', 'client.test@exemple.com', '2025-07-31 07:18:08', '$2y$12$sVrp7r3t4q7e.BYtp8xXFuWxvevdGR6qOesmTnL9IsBjDQLSNoUr6', '2000-07-31', 'active', '2025-07-31 07:18:08', 0, NULL, NULL, 'local', NULL, 1, '4TBjAiAj9BZKVxkoAFc25NFvC1wnyBjx6hI1xmCRKDdkr4Gbvt70s3SSdtfl', NULL, NULL, '2025-07-31 07:18:08', '2025-08-05 00:30:17', NULL),
(5, 'client', 'ToffaDev', 'devincetoffa99@gmail.com', '2025-07-31 07:18:09', '$2y$12$tnd0Q6V7aV5yBZ424alTB.C7aR6FAfCtOIwQ25TdIKMnKzfajCnC.', '2000-07-31', 'active', '2025-07-31 07:18:09', 0, NULL, NULL, 'local', NULL, 1, 'SpjFzQbvDfBkahebZpRw3AtKVxD7Ek7WUvxdxeFNBFZfXvnOEJKW7CBV7uXI', '2025-08-05 00:34:51', 'rVmT2J6MviEC5LhJVPsLtyZiAIVTxlQNqggL6eWgiClm0GbEhHCoyx3KrYs5', '2025-07-31 07:18:09', '2025-08-05 00:34:51', NULL),
(6, 'client', 'Auriol Assogba', 'auriolassogba0@gmail.com', NULL, NULL, '2007-07-31', 'active', '2025-07-31 07:21:12', 0, '103946412160022236076', 'https://lh3.googleusercontent.com/a/ACg8ocLXuXYKAfb8LFGZZbgiVfUafSElNFcD7bYx4o1W5zS54GVzZw=s96-c', 'google', NULL, 1, 'vyqPDws3cOgrCQ6ohGd78IarLdHssSrvoDIq2eJrpjQAgdQUgN9ZvL7isVjo', NULL, NULL, '2025-07-31 07:21:12', '2025-08-05 00:30:17', NULL),
(7, 'client', 'auri', 'auriolauriol229@gmail.com', NULL, '$2y$12$qNAN0jVWSvIO5Q9146ZOI.1nzRkrdTAAVih3cWWglQjUbiHWP.4Li', '1997-12-07', 'active', '2025-07-31 08:06:03', 0, NULL, NULL, 'local', NULL, 1, 'BWHW7CQ0iO0RBwFwVB3k5wRL7O1jjEa93v2PqtycdrNfdqTZiqeXkvHU7R8r', NULL, NULL, '2025-07-31 08:06:03', '2025-08-05 00:30:18', NULL),
(8, 'client', 'TestUser', 'test@test.com', NULL, '$2y$12$tFric0sCZm6EcFaZh5YlO.PfXKM1/1NMMIiFsF4VM3esTr5vx5KAm', '1990-01-01', 'active', '2025-07-31 08:20:03', 0, NULL, NULL, 'local', NULL, 1, 'GWwBrGox4WvWihpBMkaoTOoWkR4L8ds3J5YSmbU50LUiFPSckwCDc2c0Iawa', NULL, NULL, '2025-07-31 08:20:04', '2025-08-05 00:30:18', NULL),
(9, 'client', 'enock', 'enocktoffa229@gmail.com', NULL, '$2y$12$CChnBNFX2AEK4xuTXmEFxOHTg089Rtn2PSREquDnd/hO8xxKv4N/6', '1997-12-07', 'active', '2025-07-31 08:27:31', 0, NULL, NULL, 'local', NULL, 1, 'inhg4rMvL7ghDKmPxJlyBDVrtm94ELZvul9YhVjME4WY23HxPeVTgjY0hF5V', NULL, NULL, '2025-07-31 08:27:31', '2025-08-05 00:30:18', NULL),
(10, 'client', 'UserTest', 'user@test.com', NULL, '$2y$12$NvdrwQ3wK7ClxRcrJf9rOexmEVhw4QE.lLqriBAJzgiHJCncgo0Ii', '1990-01-01', 'active', '2025-07-31 10:55:06', 0, NULL, NULL, 'local', NULL, 1, 'rrEEoVg2XvCsVz46FquUUR4N0OpOHNtLLH66pDbEkjHE0J2Z1EBspakzviuE', NULL, NULL, '2025-07-31 10:55:06', '2025-08-05 00:30:18', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `visually_impaired_people`
--

CREATE TABLE `visually_impaired_people` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `sexe` enum('M','F') NOT NULL,
  `age` int(11) DEFAULT NULL,
  `lieu_residence` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) NOT NULL,
  `type_voyance` varchar(255) DEFAULT NULL,
  `traitement_en_cours` tinyint(1) NOT NULL DEFAULT 0,
  `photo_path` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `visually_impaired_people`
--

INSERT INTO `visually_impaired_people` (`id`, `nom`, `prenom`, `sexe`, `age`, `lieu_residence`, `telephone`, `type_voyance`, `traitement_en_cours`, `photo_path`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Soton', 'Ornelia', 'F', 19, 'Godomey', '62765025', 'cécité_totale', 0, NULL, 1, 1, '2025-08-04 23:45:31', '2025-08-04 23:45:31');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_moderated_by_foreign` (`moderated_by`),
  ADD KEY `comments_publication_id_status_index` (`publication_id`,`status`),
  ADD KEY `comments_parent_id_index` (`parent_id`);

--
-- Index pour la table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donations_type_status_index` (`type`,`status`),
  ADD KEY `donations_publication_id_index` (`publication_id`),
  ADD KEY `donations_user_id_index` (`user_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `financial_reports`
--
ALTER TABLE `financial_reports`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Index pour la table `project_media`
--
ALTER TABLE `project_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_media_solidarity_project_id_foreign` (`solidarity_project_id`);

--
-- Index pour la table `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `publications_slug_unique` (`slug`),
  ADD KEY `publications_moderated_by_foreign` (`moderated_by`),
  ADD KEY `publications_type_status_index` (`type`,`status`),
  ADD KEY `publications_user_id_status_index` (`user_id`,`status`),
  ADD KEY `publications_slug_index` (`slug`);
ALTER TABLE `publications` ADD FULLTEXT KEY `publications_title_content_fulltext` (`title`,`content`);

--
-- Index pour la table `publication_tags`
--
ALTER TABLE `publication_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `publication_tags_publication_id_tag_id_unique` (`publication_id`,`tag_id`),
  ADD KEY `publication_tags_tag_id_foreign` (`tag_id`);

--
-- Index pour la table `reactions`
--
ALTER TABLE `reactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reactions_user_id_reactable_id_reactable_type_type_unique` (`user_id`,`reactable_id`,`reactable_type`,`type`),
  ADD KEY `reactions_reactable_type_reactable_id_index` (`reactable_type`,`reactable_id`);

--
-- Index pour la table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_reporter_id_foreign` (`reporter_id`),
  ADD KEY `reports_reportable_type_reportable_id_index` (`reportable_type`,`reportable_id`),
  ADD KEY `reports_reviewed_by_foreign` (`reviewed_by`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `solidarity_projects`
--
ALTER TABLE `solidarity_projects`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_name_unique` (`name`),
  ADD UNIQUE KEY `tags_slug_unique` (`slug`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_pseudo_unique` (`pseudo`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`),
  ADD UNIQUE KEY `users_unsubscribe_token_unique` (`unsubscribe_token`);

--
-- Index pour la table `visually_impaired_people`
--
ALTER TABLE `visually_impaired_people`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visually_impaired_people_nom_prenom_index` (`nom`,`prenom`),
  ADD KEY `visually_impaired_people_sexe_index` (`sexe`),
  ADD KEY `visually_impaired_people_is_active_index` (`is_active`),
  ADD KEY `visually_impaired_people_sort_order_index` (`sort_order`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `financial_reports`
--
ALTER TABLE `financial_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `project_media`
--
ALTER TABLE `project_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `publications`
--
ALTER TABLE `publications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `publication_tags`
--
ALTER TABLE `publication_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `reactions`
--
ALTER TABLE `reactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `solidarity_projects`
--
ALTER TABLE `solidarity_projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `visually_impaired_people`
--
ALTER TABLE `visually_impaired_people`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_moderated_by_foreign` FOREIGN KEY (`moderated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_publication_id_foreign` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_publication_id_foreign` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `donations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `project_media`
--
ALTER TABLE `project_media`
  ADD CONSTRAINT `project_media_solidarity_project_id_foreign` FOREIGN KEY (`solidarity_project_id`) REFERENCES `solidarity_projects` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `publications`
--
ALTER TABLE `publications`
  ADD CONSTRAINT `publications_moderated_by_foreign` FOREIGN KEY (`moderated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `publications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `publication_tags`
--
ALTER TABLE `publication_tags`
  ADD CONSTRAINT `publication_tags_publication_id_foreign` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `publication_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reactions`
--
ALTER TABLE `reactions`
  ADD CONSTRAINT `reactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
