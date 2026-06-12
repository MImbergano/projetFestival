-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 11 juin 2026 à 17:23
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `festival_vitrine`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `cid` int(11) NOT NULL,
  `intitule` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`cid`, `intitule`) VALUES
(1, 'Cuisine'),
(2, 'Artisanat'),
(3, 'Conte'),
(4, 'Dégustation');

-- --------------------------------------------------------

--
-- Structure de la table `prestation`
--

CREATE TABLE `prestation` (
  `pid` int(11) NOT NULL,
  `intitule` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `categories_id` int(11) DEFAULT NULL,
  `artiste_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `prestation`
--

INSERT INTO `prestation` (`pid`, `intitule`, `description`, `image`, `categories_id`, `artiste_id`) VALUES
(1, 'Sculpture de sabot', 'Démonstration de fabrication de sabots traditionnels.', NULL, 2, 1),
(2, 'Le bois flotté', 'Atelier de création de petits objets en bois de récupération.', 'travail_bois.jpeg', 2, 1),
(3, 'La soupe au chaudron', 'Préparation collective d une soupe paysanne sur feu de bois.', NULL, 1, 2),
(4, 'Secrets du pain', 'Façonnage de miches de pain à l ancienne.', NULL, 1, 2),
(5, 'Légendes du village', 'Récits des histoires oubliées de nos campagnes.', 'compte_region.jpg', 3, 3),
(6, 'Les esprits de la forêt', 'Contes merveilleux pour petits et grands.', NULL, 3, 3),
(7, 'Peinture à l œuf', 'Technique ancienne de peinture utilisant des pigments naturels.', NULL, 2, 4),
(8, 'Modelage d argile', 'Initiation au tour de potier manuel.', 'poterie.jpg', 2, 4),
(9, 'Le fer rouge', 'Démonstration de forge d outils agricoles.', NULL, 2, 5),
(10, 'Création de fer à cheval', 'Explication de l importance du maréchal-ferrant.', NULL, 2, 5),
(11, 'Confitures à la bassine', 'Cuisson lente de fruits oubliés.', 'confiture.jpg', 1, 6),
(12, 'Goûter d antan', 'Dégustation de galettes sèches et miel local.', NULL, 4, 6),
(13, 'Le chant des pierres', 'Mythes sur la création des montagnes.', 'compte_region.jpg', 3, 7),
(14, 'Veillée au coin du feu', 'Histoires de loups et de bergers.', 'compte_region.jpg', 3, 7),
(15, 'Fresque historique', 'Réalisation en direct d une scène de vie rurale.', NULL, 2, 8),
(16, 'Atelier pigments', 'Apprendre à créer ses couleurs avec des plantes.', NULL, 2, 8),
(17, 'Vins de jadis', 'Dégustation commentée de cépages anciens.', 'degusterVin.jpg', 4, 9),
(18, 'Pressage de raisin', 'Démonstration avec un pressoir à main.', NULL, 4, 9),
(19, 'Le jardin enchanté', 'Récits de plantes magiques et d animaux fantastiques.', NULL, 3, 10),
(20, 'Peinture sur faïence', 'Atelier pratique de peinture', 'peintureFaience.jpg', 2, 10),
(21, 'Tissage au métier à main', 'Démonstration de tissage', 'metierTisser.jpg', 2, 10);

-- --------------------------------------------------------

--
-- Structure de la table `programmation`
--

CREATE TABLE `programmation` (
  `prog_id` int(11) NOT NULL,
  `heure_debut` time NOT NULL,
  `prestation_id` int(11) DEFAULT NULL,
  `scene_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `programmation`
--

INSERT INTO `programmation` (`prog_id`, `heure_debut`, `prestation_id`, `scene_id`) VALUES
(1, '13:00:00', 1, 1),
(2, '13:00:00', 3, 2),
(3, '14:00:00', 5, 3),
(4, '14:00:00', 9, 1),
(7, '16:00:00', 13, 3),
(8, '16:00:00', 4, 2),
(9, '17:00:00', 6, 3),
(10, '17:00:00', 11, 2),
(11, '18:00:00', 15, 1),
(12, '15:00:00', 20, 1),
(13, '18:00:00', 17, 2);

-- --------------------------------------------------------

--
-- Structure de la table `scene`
--

CREATE TABLE `scene` (
  `sid` int(11) NOT NULL,
  `nom_scene` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `scene`
--

INSERT INTO `scene` (`sid`, `nom_scene`) VALUES
(1, 'Atelier des Mains'),
(2, 'Place des Saveurs'),
(3, 'Espace Mémoire');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `uid` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom_artiste` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `mot_passe_hashe` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `est_organisateur` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`uid`, `nom`, `prenom`, `nom_artiste`, `email`, `mot_passe_hashe`, `photo`, `description`, `est_organisateur`) VALUES
(1, 'Dupont', 'Jean', 'L’Âme du Chêne', 'jean@bois.fr', 'hash123', 'jeanDupont.jpg', 'Ébéniste de père en fils, Jean redonne vie aux essences de bois locales. À travers ses sculptures et ses objets du quotidien, il transmet la patience et la précision du travail manuel tel qu’il se pratiquait dans les ateliers d’autrefois.', 0),
(2, 'Martin', 'Claire', 'La Cuillère en Bois', 'claire@cuisine.fr', 'hash123', 'claireMartin.jpg', 'Gardienne des secrets culinaires de nos grands-mères, Claire sublime les produits du terroir. Spécialisée dans la cuisson au chaudron et les mijotés d’antan, elle fait revivre les saveurs oubliées qui rassemblaient jadis les familles autour de l’âtre.', 0),
(3, 'Petit', 'Léo', 'Le Conteur des Brumes', 'leo@contes.fr', 'hash123', 'leoPetit.jpg', 'Léo parcourt les sentiers pour recueillir les murmures du passé. Entre mythes sylvestres et récits de villageois, ses contes transportent son auditoire dans un univers où le merveilleux côtoie la réalité historique de nos campagnes.', 0),
(4, 'Moreau', 'Julie', 'Terre & Couleurs', 'julie@potier.fr', 'hash123', 'julieMoreau.jpg', 'Inspirée par les vestiges archéologiques de la région, Julie travaille l’argile selon des techniques ancestrales. Chaque pièce, façonnée à la main et cuite au feu de bois, est une ode à la terre nourricière et au geste premier de l’artisan.', 0),
(5, 'Lefebvre', 'Thomas', 'Le Forgeron d’Antan', 'thomas@fer.fr', 'hash123', 'thomasLefebvre.jpg', 'Au rythme de l’enclume et du marteau, Thomas dompte le fer chauffé au rouge. Il se consacre à la restauration d’outils anciens et à la création de ferronneries d’art, témoignant de l’importance vitale de la forge dans la vie rurale de jadis.', 0),
(6, 'Roux', 'Sophie', 'Douceurs d’Autrefois', 'sophie@sucre.fr', 'hash123', 'sophieRoux.jpg', 'Confiseuse passionnée, Sophie transforme les fruits du verger en douceurs sucrées. En utilisant des bassines en cuivre et des méthodes de conservation naturelles, elle préserve le goût authentique des goûters d’enfance et des fêtes de village.', 0),
(7, 'Bernard', 'Marc', 'La Voix des Forêts', 'marc@legendes.fr', 'hash123', 'marcBernard.jpg', 'Marc est un passeur d’histoires qui puise son inspiration dans les bruits de la nuit et les légendes paysannes. Ses récits, souvent accompagnés d’instruments traditionnels, célèbrent le lien sacré entre l’homme et son environnement sauvage.', 0),
(8, 'Dubois', 'Emma', 'Le Pinceau Rustique', 'emma@art.fr', 'hash123', 'emmaDubois.jpg', 'Artiste peintre amoureuse des paysages ruraux, Emma utilise des pigments naturels qu’elle fabrique elle-même. Ses toiles capturent la lumière des saisons et les scènes de la vie quotidienne, immortalisant la beauté simple des traditions.', 0),
(9, 'Garnier', 'Pierre', 'Le Vigneron du Terroir', 'pierre@vin.fr', 'hash123', 'pierreGarnier.jpg', 'Héritier d’un vignoble centenaire, Pierre cultive des cépages anciens souvent délaissés. Sa philosophie repose sur le respect du cycle de la vigne et une vinification naturelle, offrant des vins de caractère qui racontent l’histoire de leur terre.', 0),
(10, 'Dumas', 'Claire', 'Claire Dumas', 'claire.d@example.com', 'hash123', 'claireDumas.jpeg', 'Spécialiste de la cuisine sauvage et médicinale, Claire apprend à redécouvrir les herbes folles de nos chemins. Elle marie habilement botanique et gastronomie pour créer des plats sains, directement inspirés du savoir des anciens herboristes.', 0),
(11, 'Admin', 'Fest', 'Organisateur', 'contact@festival.fr', 'adminhash', NULL, 'Équipe dédiée à la préservation du patrimoine immatériel et artisanal. Notre mission est de créer un pont entre les générations en célébrant les savoir-faire qui ont façonné notre identité et notre culture régionale.', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cid`);

--
-- Index pour la table `prestation`
--
ALTER TABLE `prestation`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `fk_prestation_categories` (`categories_id`),
  ADD KEY `fk_prestation_artiste` (`artiste_id`);

--
-- Index pour la table `programmation`
--
ALTER TABLE `programmation`
  ADD PRIMARY KEY (`prog_id`),
  ADD KEY `fk_prog_prestation` (`prestation_id`),
  ADD KEY `fk_prog_scene` (`scene_id`);

--
-- Index pour la table `scene`
--
ALTER TABLE `scene`
  ADD PRIMARY KEY (`sid`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `prestation`
--
ALTER TABLE `prestation`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `programmation`
--
ALTER TABLE `programmation`
  MODIFY `prog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `scene`
--
ALTER TABLE `scene`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `prestation`
--
ALTER TABLE `prestation`
  ADD CONSTRAINT `fk_prestation_artiste` FOREIGN KEY (`artiste_id`) REFERENCES `utilisateur` (`uid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_prestation_categories` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`cid`) ON DELETE CASCADE;

--
-- Contraintes pour la table `programmation`
--
ALTER TABLE `programmation`
  ADD CONSTRAINT `fk_prog_prestation` FOREIGN KEY (`prestation_id`) REFERENCES `prestation` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_prog_scene` FOREIGN KEY (`scene_id`) REFERENCES `scene` (`sid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
