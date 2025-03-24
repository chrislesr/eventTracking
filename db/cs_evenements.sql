-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 10 mars 2025 à 15:37
-- Version du serveur : 10.4.25-MariaDB
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cs_evenements`
--

-- --------------------------------------------------------

--
-- Structure de la table `affectation_invit`
--

CREATE TABLE `affectation_invit` (
  `id_affect_inv` int(11) NOT NULL,
  `id_invitation_fk` int(11) NOT NULL,
  `id_inviter_fk` int(11) NOT NULL,
  `date_enreg_affect` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `affect_invitation_event`
--

CREATE TABLE `affect_invitation_event` (
  `id_aff` int(11) NOT NULL,
  `affect_invit_id` int(11) NOT NULL,
  `affect_event_id` int(11) NOT NULL,
  `affect_util_id` int(11) NOT NULL,
  `control_deleted` varchar(50) NOT NULL DEFAULT 'no_deleted',
  `is_actif` varchar(50) NOT NULL DEFAULT 'active',
  `date_enreg_affect` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `affect_invitation_event`
--

INSERT INTO `affect_invitation_event` (`id_aff`, `affect_invit_id`, `affect_event_id`, `affect_util_id`, `control_deleted`, `is_actif`, `date_enreg_affect`) VALUES
(3, 1, 3, 3, 'no_deleted', 'active', '2025-01-23 11:57:24'),
(5, 3, 1, 3, 'no_deleted', 'active', '2025-01-23 11:57:29'),
(7, 4, 1, 3, 'no_deleted', 'active', '2025-01-27 20:16:29'),
(17, 2, 1, 3, 'no_deleted', 'active', '2025-02-01 13:57:31');

-- --------------------------------------------------------

--
-- Structure de la table `categorie_inviter`
--

CREATE TABLE `categorie_inviter` (
  `id_cat_inv` int(11) NOT NULL,
  `nom_categorie` varchar(255) NOT NULL,
  `id_event_ctg` int(11) NOT NULL DEFAULT 0,
  `id_org_ctg_fk` int(11) DEFAULT 0,
  `sigle_cat_inviter` varchar(100) DEFAULT NULL,
  `controle_deleted_cat` varchar(100) NOT NULL DEFAULT 'no_deleted',
  `date_enreg_cat` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `categorie_inviter`
--

INSERT INTO `categorie_inviter` (`id_cat_inv`, `nom_categorie`, `id_event_ctg`, `id_org_ctg_fk`, `sigle_cat_inviter`, `controle_deleted_cat`, `date_enreg_cat`) VALUES
(1, 'special', 0, NULL, 'special', 'no_deleted', '2025-01-18'),
(2, 'normal', 0, NULL, 'normal', 'no_deleted', '2025-01-18'),
(3, 'famille de la fille', 0, NULL, 'fm_girls', 'no_deleted', '2025-03-08'),
(4, 'famille du garcon', 0, NULL, 'fm_man', 'no_deleted', '2025-03-08'),
(6, 'chine', 1, 3, NULL, 'no_deleted', '2025-03-08');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `phone`) VALUES
(1, 'christophe', 'christophe@gmail.com', ''),
(2, 'christophe1', 'christophe1@gmail.com', ''),
(3, 'christophe11', 'christophe11@gmail.com', ''),
(4, 'christophe122', 'christophe22@gmail.com', ''),
(5, 'christophe1224', 'christophe224@gmail.com', ''),
(6, 'christophe1224', 'christophe224@gmail.com', ''),
(7, 'christophe1224', 'christophe224@gmail.com', '9999999999244'),
(8, 'ff', 'ff@gmail.com', ''),
(9, 't', 'd@gmail.com', ''),
(10, 't', 'd@gmail.com', ''),
(11, 'gdgd', 'hdhdh@gmail.com', '11111'),
(12, 'goulan', 'goulan@fgmail.com', '12344'),
(13, 'gims', 'gims@gmail.com', '12344'),
(14, 'ggdgd', 'dddd@gmail.com', '112222'),
(15, 'ccdd', 'dd@gmail.com', '11223'),
(16, 'ccdd', 'dd@gmail.com', '11223'),
(17, 'ccdd', 'dd@gmail.com', '11223'),
(18, 'ccdd', 'dd@gmail.com', '11223'),
(19, 'ccdd4443', 'dd@gmail.com', '11223'),
(20, 'ccdd4443', 'dd@gmail.com', '11223'),
(21, 'ccdd4443', 'dd@gmail.com', '11223'),
(22, 'ccdd4443', 'dd@gmail.com', '11223'),
(23, 'christpppej', 'titit@gmail.com', '123456'),
(24, 'ff', 'ff@gmail.com', '12234');

-- --------------------------------------------------------

--
-- Structure de la table `comite_organisateur`
--

CREATE TABLE `comite_organisateur` (
  `id_inviter` int(11) NOT NULL,
  `id_utl_inviter_fk` int(11) NOT NULL,
  `id_niveau_inv_fk` int(11) DEFAULT NULL,
  `id_event_cm_fk` int(11) DEFAULT NULL,
  `nom_inviter` varchar(255) NOT NULL,
  `sexe_inviter` varchar(100) NOT NULL,
  `tel_inviter` varchar(100) NOT NULL,
  `poste_inviter` varchar(100) DEFAULT NULL,
  `image_inviter` varchar(255) NOT NULL,
  `email_inviter` varchar(100) DEFAULT NULL,
  `id_ops_update_cmt` int(11) DEFAULT NULL,
  `password_inviter` varchar(255) DEFAULT NULL,
  `control_deleted` varchar(100) NOT NULL DEFAULT 'no_deleted',
  `date_enreg_inviter` datetime NOT NULL DEFAULT current_timestamp(),
  `is_actif_inviter` varchar(50) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `comite_organisateur`
--

INSERT INTO `comite_organisateur` (`id_inviter`, `id_utl_inviter_fk`, `id_niveau_inv_fk`, `id_event_cm_fk`, `nom_inviter`, `sexe_inviter`, `tel_inviter`, `poste_inviter`, `image_inviter`, `email_inviter`, `id_ops_update_cmt`, `password_inviter`, `control_deleted`, `date_enreg_inviter`, `is_actif_inviter`) VALUES
(1, 3, 2, 1, 'judith', 'femme', '999999999', 'vente', '1737169948.png', 'judith@gmail.com', NULL, '$2y$10$1MqTAp24urS7v9gqsRhaY.7SEzIu9Qv.9F0/h5NSoaKJnzCXEyXn6', 'no_deleted', '2025-01-19 00:00:00', 'active'),
(2, 3, 2, 1, 'emilielesr', 'femme', '123456700', 'vente', 'PF67c0fb4a96a3b.png', 'emiComite@gmail.com', 3, '$2y$10$2Jq1WZDfz7.o.MJYpjyFNO39srJZSejgSVNyZAaPk7s1IWBrtvIZe', 'no_deleted', '2025-01-19 00:00:00', 'active'),
(3, 3, 2, 1, 'justin', 'homme', '999999', 'porte', '', 'just@gmail.com', NULL, '$2y$10$gSSAkM0PGGLbCmn.5prgrubFcKP1115aFe.jFZ1WLOG7Ic17Va9OK', 'no_deleted', '2025-01-19 00:00:00', 'active'),
(4, 3, 2, 1, 'gentil lwende', 'homme', '999444888', 'porte', '1737038445.jpg', 'gentil@gmail.com', NULL, '$2y$10$4tkeJCqCm6i/mzccvUro1e2Yjmp8V5wbNnorA1S1bFNGE3VnJPt0y', 'no_deleted', '2025-01-23 00:00:00', 'active'),
(12, 3, 2, 1, 'timo rusumba', 'homme', '123332222', 'porte', '', 'timorusumba@gmail.com', NULL, '$2y$10$GKmJNuZkg363NOoew73u9..mVX4PPOgoZUJ/2fn9OKN66n9QmRhie', 'no_deleted', '2025-01-27 00:00:00', 'active'),
(13, 3, 2, 1, 'jeanne', 'femme', '112221223', 'porte', 'PF1737996639.jpg', 'jeanne@gmail.com', NULL, '$2y$10$KDTs7hY8SGCJQTwwvbZC5ud1JB/pBJCOL/4la7uGnxpj/g5sbJqpu', 'no_deleted', '2025-01-27 00:00:00', 'active'),
(14, 3, 2, 1, 'fils', 'homme', '123332222', 'porte', 'PF1737996841.jpg', 'fils@gmail.com', NULL, '$2y$10$WFxfA3yGFMltFZp6gViKoutTdMUo1OB4eNZJ.bNjgFzDkOvUHOls2', 'no_deleted', '2025-01-27 00:00:00', 'active'),
(15, 3, 2, 1, 'gilbert', 'homme', '111111111', 'vente', 'PF1737997671.jpg', 'rusumba@gmail.com', NULL, '$2y$10$ysFuOA9SPkc4Ph0cqFOaN.Hhn4fRIVoCv1AS/Z1ueXy2rHUh7HwvK', 'no_deleted', '2025-01-27 00:00:00', 'active'),
(32, 3, 2, 1, 'fabrice', 'femme', '999888233', 'vente', '', 'fabrice@gmail.com', NULL, '$2y$10$9te2YdkarxLbfdafXedxwujOWDFoxRUKvXnKz65Qny/4sK0mRccIS', 'no_deleted', '2025-01-30 00:00:00', 'active'),
(33, 3, 2, 1, 'fabrice', 'femme', '999999999', 'porte', '', 'fabrice1@gmail.com', NULL, '$2y$10$hFy21aGtKTtL4iXHYExsoenPz7OdIY4Hwnx5l2yvZre7L5Tku1peu', 'no_deleted', '2025-01-30 00:00:00', 'active'),
(34, 3, 2, 1, 'bisimwa', 'homme', '999999999', 'porte', '', 'bisimwa@gmail.com', NULL, '$2y$10$AtkjMRvxM.q0ky.hhVQETuOvE6RbIVU.KpDLIVEL292LXz2LHTw46', 'no_deleted', '2025-01-30 00:00:00', 'active'),
(35, 3, 2, 1, 'vane', 'femme', '99929292', 'vente', '', 'vane@gmail.com', NULL, '$2y$10$7N.YzfD.6hzB4HzS.0q.A.eBrTInjDTOVzH54UouvqHtWxc8owxOa', 'no_deleted', '2025-01-30 00:00:00', 'active'),
(36, 3, 2, 1, 'barlathaz', 'femme', '999988776', 'porte', '', 'balthazard@gmail.com', NULL, '$2y$10$vRqWJQHj0sU/FpuIwcCMPeL1yQpWWAEzLoY4sQu6L9cc0TFBo2wbC', 'no_deleted', '2025-01-30 00:00:00', 'active'),
(37, 3, 2, 1, '22barlathaz', 'homme', '444444444', 'porte', '', '22balthazard@gmail.com', NULL, '$2y$10$cGaKnMph52pgfhmaHpzSe.2BeRBCJBD.f7fJgvSvCec/kqVVWAhjG', 'no_deleted', '2025-01-30 00:00:00', 'active'),
(38, 3, 2, 1, 'test1', 'femme', '099443422', 'vente', '', 'test1@gmail.com', NULL, '$2y$10$yifXGje94Xa5wSrtZa5LzOAZ1VEPhyiIwJDC1/Jyk8JW6cWRzGV2y', 'no_deleted', '2025-01-30 20:30:25', 'active'),
(39, 3, 2, 1, 'test2', 'homme', '344444444', 'porte', '', 'test2@gmail.com', NULL, '$2y$10$yyeF2KPw5l6dr6O5xOMgUu0xXrxLRrLzXyH15uoAVwN0W.1VnRLNi', 'no_deleted', '2025-01-30 20:35:46', 'active'),
(40, 3, 2, 1, 'test3', 'homme', '211111111', 'vente', '', 'test3@gmail.com', NULL, '$2y$10$7VclZldCsYrisvS4vLVFwuZk8kBFo7ytniymcjurILVJs.5NQYDwu', 'no_deleted', '2025-01-30 20:43:08', 'active'),
(41, 3, 2, 1, 'jean', 'homme', '853536620', 'porte', '', 'jena@gmail.com', NULL, '$2y$10$YwfZsM3BM3DsdFLzV2V8JO948KIdjbEe7yeQpIGPzDVsbVCVAQXOW', 'no_deleted', '2025-01-31 09:39:54', 'active'),
(42, 3, 2, 1, 'jeanne', 'femme', '876654325', 'porte', 'PF679c8fb64509e.jpg', 'jeanne12@gmail.com', NULL, '$2y$10$Y2NftXQ4ElAGYK1w1UO5EOF7bSS9yWaDFhCrxiMG2c/fqde0oqsuO', 'no_deleted', '2025-01-31 09:54:14', 'active'),
(43, 3, 2, 1, 'filipo', 'femme', '1122123', 'porte', 'PF67a3e9d883569.jpg', 'fils2@gmail.com', NULL, '$2y$10$IuVCx1eFdd0jCu3QfogALuHGzMsTVlvYkdAz.VzUINBLXGxjA/heG', 'no_deleted', '2025-02-05 23:44:40', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

CREATE TABLE `evenements` (
  `id_env` int(11) NOT NULL,
  `id_util_org` int(11) NOT NULL,
  `id_type_envent_fk` int(11) NOT NULL,
  `titre_evenement` varchar(255) NOT NULL,
  `lieu_evenement` varchar(255) NOT NULL,
  `date_evenement` date NOT NULL,
  `heure_evenement` varchar(100) NOT NULL,
  `affiche_evenemt` varchar(255) NOT NULL,
  `img_man_evenement` varchar(255) DEFAULT NULL,
  `img_girl_evenement` varchar(255) DEFAULT NULL,
  `background_evenement` varchar(255) DEFAULT NULL,
  `nombre_invitation` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `control_deleted` varchar(100) NOT NULL DEFAULT 'no_deleted',
  `is_actif` varchar(100) NOT NULL DEFAULT 'active',
  `date_enreg_evenement` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `evenements`
--

INSERT INTO `evenements` (`id_env`, `id_util_org`, `id_type_envent_fk`, `titre_evenement`, `lieu_evenement`, `date_evenement`, `heure_evenement`, `affiche_evenemt`, `img_man_evenement`, `img_girl_evenement`, `background_evenement`, `nombre_invitation`, `description`, `control_deleted`, `is_actif`, `date_enreg_evenement`) VALUES
(1, 3, 1, 'mariage herve &amp; kenedy', 'salle bodega', '2025-09-12', '18:30', 'AF23785.jpg', NULL, NULL, NULL, 10, 'mariage religieux à 13h', 'no_deleted', 'active', '2025-01-18 17:41:51'),
(2, 3, 1, 'mariage scott &amp; Y', 'goma', '2025-02-06', '20:00', 'AF43319.jpg', NULL, NULL, NULL, 10, 'mariage', 'no_deleted', 'desactive', '2025-01-18 17:46:26'),
(3, 4, 1, 'FESTIRAS', 'ATHENE D&#039;IBANDA', '2025-02-20', '13:30', ' ', NULL, NULL, NULL, 1000, 'GGGF', 'no_deleted', 'active', '2025-02-13 22:40:58'),
(4, 3, 2, 'tujenge kitumaini', 'goma', '2025-02-28', '12:12', 'AF67ae69c86cddd.jpg', NULL, NULL, NULL, 500, 'dfss', 'no_deleted', 'active', '2025-02-13 22:53:12');

-- --------------------------------------------------------

--
-- Structure de la table `invitations`
--

CREATE TABLE `invitations` (
  `id_inv` int(11) NOT NULL,
  `code_invitation` varchar(255) DEFAULT NULL,
  `code_qr_invitation` varchar(255) DEFAULT NULL,
  `id_typ_inv_fk` int(11) DEFAULT NULL,
  `id_utl_invit_fk` int(11) NOT NULL,
  `id_utl_affect` int(11) DEFAULT NULL,
  `control_deleted_invit` varchar(100) NOT NULL DEFAULT 'no_deleted',
  `date_enreg_invit` date NOT NULL DEFAULT current_timestamp(),
  `is_actif` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `invitations`
--

INSERT INTO `invitations` (`id_inv`, `code_invitation`, `code_qr_invitation`, `id_typ_inv_fk`, `id_utl_invit_fk`, `id_utl_affect`, `control_deleted_invit`, `date_enreg_invit`, `is_actif`) VALUES
(1, NULL, 'MT51665402521', 1, 2, NULL, 'no_deleted', '2025-01-21', 'active'),
(2, '3997B1C1', 'MT5986234582548', 1, 2, 2, 'no_deleted', '2025-01-21', 'active'),
(3, '1425263763774', 'MT31699450772', 1, 2, 3, 'no_deleted', '2025-01-22', 'active'),
(4, '1425263763', NULL, 1, 2, 3, 'no_deleted', '2025-01-22', 'active'),
(5, NULL, 'SUcqv8BHz8_001_v', 2, 1, NULL, 'no_deleted', '2025-02-16', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `niveaux`
--

CREATE TABLE `niveaux` (
  `id_niv` int(11) NOT NULL,
  `nom_niv` varchar(100) NOT NULL,
  `sigle_niv` varchar(150) DEFAULT NULL,
  `is_actif_niv` varchar(50) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `niveaux`
--

INSERT INTO `niveaux` (`id_niv`, `nom_niv`, `sigle_niv`, `is_actif_niv`) VALUES
(1, 'full_control', 'LEVEL_0', 'active'),
(2, 'comite', 'LEVEL_NIV1', 'active'),
(3, 'full_control1', 'LEVEL_1', 'active'),
(4, 'organisateur', 'LEVEL_NIV4', 'active'),
(5, 'inviter', 'LEVEL_NIV5', 'active'),
(6, 'finance', 'LEVEL_NIV6', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `participants`
--

CREATE TABLE `participants` (
  `id_part` int(11) NOT NULL,
  `id_invitation_part_fk` int(11) NOT NULL,
  `id_event_part_fk` int(11) NOT NULL,
  `id_cat_inviter_fk` int(11) NOT NULL,
  `nom_part` varchar(250) NOT NULL,
  `prenom_part` varchar(150) DEFAULT NULL,
  `email_part` varchar(255) DEFAULT NULL,
  `sexe_part` varchar(100) DEFAULT NULL,
  `tel_part` varchar(100) NOT NULL,
  `id_util_ops_part_fk` int(11) DEFAULT NULL,
  `id_cmt_osp_part_fk` int(11) DEFAULT NULL,
  `id_ops_update_part` int(11) DEFAULT NULL,
  `image_part` varchar(255) DEFAULT NULL,
  `etat_participant` varchar(100) NOT NULL DEFAULT 'attente',
  `control_deleted` varchar(50) NOT NULL DEFAULT 'no_deleted',
  `date_enreg_part` datetime NOT NULL DEFAULT current_timestamp(),
  `is_actif_part` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `participants`
--

INSERT INTO `participants` (`id_part`, `id_invitation_part_fk`, `id_event_part_fk`, `id_cat_inviter_fk`, `nom_part`, `prenom_part`, `email_part`, `sexe_part`, `tel_part`, `id_util_ops_part_fk`, `id_cmt_osp_part_fk`, `id_ops_update_part`, `image_part`, `etat_participant`, `control_deleted`, `date_enreg_part`, `is_actif_part`) VALUES
(1, 17, 1, 3, 'christophe rusumba12', 'chris', 'christophe1@gmail.com', 'homme', '9992929233', 3, NULL, 3, '67c0a7df9435f.JPG', 'confirme', 'no_deleted', '2025-01-23 20:10:07', 'active'),
(7, 5, 1, 1, 'ccc', NULL, NULL, 'femme', '11122', NULL, 1, NULL, NULL, 'attente', 'no_deleted', '2025-01-23 20:57:33', 'active'),
(11, 7, 1, 1, 'badera', NULL, NULL, 'femme', '0987654323', NULL, 1, NULL, '67a3ed6cdd636.jpg', 'attente', 'no_deleted', '2025-02-05 23:59:56', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `types_invitation`
--

CREATE TABLE `types_invitation` (
  `id_typ_inv` int(11) NOT NULL,
  `nom_typ_inv` varchar(255) NOT NULL,
  `id_utl_TI_fk` int(11) NOT NULL,
  `controle_deleted_typ_inv` varchar(100) NOT NULL DEFAULT 'no_deleted',
  `date_enreg_typ_inv` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `types_invitation`
--

INSERT INTO `types_invitation` (`id_typ_inv`, `nom_typ_inv`, `id_utl_TI_fk`, `controle_deleted_typ_inv`, `date_enreg_typ_inv`) VALUES
(1, 'invitation', 1, 'no_deleted', '2025-02-16 03:27:05'),
(2, 'bracelet', 1, 'no_deleted', '2025-02-16 03:27:05'),
(3, 'chapeau', 1, 'no_deleted', '2025-02-16 03:39:32'),
(4, 'teste', 1, 'no_deleted', '2025-02-16 03:41:47');

-- --------------------------------------------------------

--
-- Structure de la table `type_evenement`
--

CREATE TABLE `type_evenement` (
  `id_typ_even` int(11) NOT NULL,
  `nom_evenement` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `type_evenement`
--

INSERT INTO `type_evenement` (`id_typ_even`, `nom_evenement`) VALUES
(1, 'fete'),
(2, 'concert'),
(3, 'sortie');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utl` int(11) NOT NULL,
  `id_niveau_fk` int(11) NOT NULL,
  `nom_utl` varchar(255) DEFAULT NULL,
  `postnom_utl` varchar(255) NOT NULL,
  `prenom_utl` varchar(255) NOT NULL,
  `sexe_utl` varchar(100) NOT NULL,
  `email_utl` varchar(255) DEFAULT NULL,
  `tel_utl` varchar(20) DEFAULT NULL,
  `image_utl` varchar(255) DEFAULT NULL,
  `nationalite_utl` varchar(255) DEFAULT NULL,
  `id_util_enreg` int(11) DEFAULT NULL,
  `control_deleted` varchar(20) NOT NULL DEFAULT 'no_deleted',
  `password_utl` varchar(255) NOT NULL,
  `is_actif` varchar(100) NOT NULL DEFAULT 'active',
  `date_enreg_utl` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utl`, `id_niveau_fk`, `nom_utl`, `postnom_utl`, `prenom_utl`, `sexe_utl`, `email_utl`, `tel_utl`, `image_utl`, `nationalite_utl`, `id_util_enreg`, `control_deleted`, `password_utl`, `is_actif`, `date_enreg_utl`) VALUES
(1, 1, 'christophe', 'rusumba', 'christophe', 'Homme', 'christophe@gmail.com', '991480479', '1737038445.jpg', 'congolaise', NULL, 'no_deleted', '$2y$10$Uj6EBr5tt8hW/z7Fy1B56OlotyiQFWH98tZsNZya893ttBmPdDpRi', 'active', '2025-01-16'),
(2, 3, 'titi', 'rusumba', 'lesr', 'Homme', 'titi.rusumba@gmail.com', '991480479', '1737169948.png', 'congolaise', NULL, 'no_deleted', '$2y$10$q1YUYRRbLUv.7d4GF2BkQuqQeXJMVgXTxJmwwANIXjOizcAYAZUDu', 'active', '2025-01-18'),
(3, 4, 'danny', 'rusumba', 'danos', 'Homme', 'dannyOrg@gmail.com', '111111111', '', 'congolaise', NULL, 'no_deleted', '$2y$10$YNL.UC6O9/PE.2mMxRkoiOdGU5bafcEJF8/qTVhbZlvyyl1BKOURS', 'active', '2025-01-18'),
(4, 2, 'julien', 'rusumba', 'junior', 'Homme', 'julien@gmail.com', '111111111', '', 'congolaise', 1, 'no_deleted', '$2y$10$ZIchKJPzjk7KiBllt.q5GeUl2ZSil35nclCFVAOB36EEhWfrFcDhS', 'active', '2025-01-18');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `affectation_invit`
--
ALTER TABLE `affectation_invit`
  ADD PRIMARY KEY (`id_affect_inv`),
  ADD KEY `affect_inviation_fk` (`id_invitation_fk`),
  ADD KEY `affect_inviater_fk` (`id_inviter_fk`);

--
-- Index pour la table `affect_invitation_event`
--
ALTER TABLE `affect_invitation_event`
  ADD PRIMARY KEY (`id_aff`),
  ADD KEY `evenement_affect_fk` (`affect_event_id`),
  ADD KEY `invitation_affect_fk` (`affect_invit_id`);

--
-- Index pour la table `categorie_inviter`
--
ALTER TABLE `categorie_inviter`
  ADD PRIMARY KEY (`id_cat_inv`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comite_organisateur`
--
ALTER TABLE `comite_organisateur`
  ADD PRIMARY KEY (`id_inviter`),
  ADD KEY `util_inviter_fk` (`id_utl_inviter_fk`),
  ADD KEY `inviter_niveau_fk` (`id_niveau_inv_fk`),
  ADD KEY `evenement_com_fk` (`id_event_cm_fk`);

--
-- Index pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD PRIMARY KEY (`id_env`),
  ADD KEY `type_evenement_fk` (`id_type_envent_fk`),
  ADD KEY `util_event_fk` (`id_util_org`);

--
-- Index pour la table `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`id_inv`),
  ADD KEY `invit_typ_inv_fk` (`id_typ_inv_fk`),
  ADD KEY `util_invit_fk` (`id_utl_invit_fk`);

--
-- Index pour la table `niveaux`
--
ALTER TABLE `niveaux`
  ADD PRIMARY KEY (`id_niv`);

--
-- Index pour la table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id_part`),
  ADD KEY `event_part_fk` (`id_event_part_fk`),
  ADD KEY `utilisateur_ops_fk` (`id_util_ops_part_fk`),
  ADD KEY `comite_ops_fk` (`id_cmt_osp_part_fk`),
  ADD KEY `id_invitation_aff_part_fk` (`id_invitation_part_fk`);

--
-- Index pour la table `types_invitation`
--
ALTER TABLE `types_invitation`
  ADD PRIMARY KEY (`id_typ_inv`);

--
-- Index pour la table `type_evenement`
--
ALTER TABLE `type_evenement`
  ADD PRIMARY KEY (`id_typ_even`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utl`),
  ADD KEY `util_niveau_fk` (`id_niveau_fk`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `affectation_invit`
--
ALTER TABLE `affectation_invit`
  MODIFY `id_affect_inv` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `affect_invitation_event`
--
ALTER TABLE `affect_invitation_event`
  MODIFY `id_aff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `categorie_inviter`
--
ALTER TABLE `categorie_inviter`
  MODIFY `id_cat_inv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `comite_organisateur`
--
ALTER TABLE `comite_organisateur`
  MODIFY `id_inviter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `evenements`
--
ALTER TABLE `evenements`
  MODIFY `id_env` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `invitations`
--
ALTER TABLE `invitations`
  MODIFY `id_inv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `niveaux`
--
ALTER TABLE `niveaux`
  MODIFY `id_niv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `participants`
--
ALTER TABLE `participants`
  MODIFY `id_part` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `types_invitation`
--
ALTER TABLE `types_invitation`
  MODIFY `id_typ_inv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `type_evenement`
--
ALTER TABLE `type_evenement`
  MODIFY `id_typ_even` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `affectation_invit`
--
ALTER TABLE `affectation_invit`
  ADD CONSTRAINT `affect_inviater_fk` FOREIGN KEY (`id_inviter_fk`) REFERENCES `comite_organisateur` (`id_inviter`) ON UPDATE CASCADE,
  ADD CONSTRAINT `affect_inviation_fk` FOREIGN KEY (`id_invitation_fk`) REFERENCES `invitations` (`id_inv`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `affect_invitation_event`
--
ALTER TABLE `affect_invitation_event`
  ADD CONSTRAINT `evenement_affect_fk` FOREIGN KEY (`affect_event_id`) REFERENCES `evenements` (`id_env`) ON DELETE CASCADE,
  ADD CONSTRAINT `invitation_affect_fk` FOREIGN KEY (`affect_invit_id`) REFERENCES `invitations` (`id_inv`) ON DELETE CASCADE;

--
-- Contraintes pour la table `comite_organisateur`
--
ALTER TABLE `comite_organisateur`
  ADD CONSTRAINT `evenement_com_fk` FOREIGN KEY (`id_event_cm_fk`) REFERENCES `evenements` (`id_env`),
  ADD CONSTRAINT `inviter_niveau_fk` FOREIGN KEY (`id_niveau_inv_fk`) REFERENCES `niveaux` (`id_niv`),
  ADD CONSTRAINT `util_inviter_fk` FOREIGN KEY (`id_utl_inviter_fk`) REFERENCES `utilisateurs` (`id_utl`);

--
-- Contraintes pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD CONSTRAINT `type_evenement_fk` FOREIGN KEY (`id_type_envent_fk`) REFERENCES `type_evenement` (`id_typ_even`),
  ADD CONSTRAINT `util_event_fk` FOREIGN KEY (`id_util_org`) REFERENCES `utilisateurs` (`id_utl`);

--
-- Contraintes pour la table `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `invit_typ_inv_fk` FOREIGN KEY (`id_typ_inv_fk`) REFERENCES `types_invitation` (`id_typ_inv`) ON UPDATE CASCADE,
  ADD CONSTRAINT `util_invit_fk` FOREIGN KEY (`id_utl_invit_fk`) REFERENCES `utilisateurs` (`id_utl`);

--
-- Contraintes pour la table `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `comite_ops_fk` FOREIGN KEY (`id_cmt_osp_part_fk`) REFERENCES `comite_organisateur` (`id_inviter`),
  ADD CONSTRAINT `event_part_fk` FOREIGN KEY (`id_event_part_fk`) REFERENCES `evenements` (`id_env`),
  ADD CONSTRAINT `id_invitation_aff_part_fk` FOREIGN KEY (`id_invitation_part_fk`) REFERENCES `affect_invitation_event` (`id_aff`) ON DELETE CASCADE,
  ADD CONSTRAINT `utilisateur_ops_fk` FOREIGN KEY (`id_util_ops_part_fk`) REFERENCES `utilisateurs` (`id_utl`);

--
-- Contraintes pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `util_niveau_fk` FOREIGN KEY (`id_niveau_fk`) REFERENCES `niveaux` (`id_niv`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
