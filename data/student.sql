-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 27 août 2025 à 09:41
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
-- Base de données : `student`
--

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id` int(11) NOT NULL,
  `matricule` varchar(50) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id`, `matricule`, `nom`, `prenom`, `date_naissance`, `photo`) VALUES
(8, '124530', 'Waterson', 'Lya', '2000-08-01', 'uploads/689c7c83648c1_AI Yearbook.jpeg'),
(9, '345279', 'Walker', 'Elina', '2001-05-13', 'uploads/689c7cd9d483c_téléchargement (5).jpeg'),
(10, '309754', 'Carson', 'Luna', '1999-10-13', 'uploads/1755086384_90.jpeg'),
(11, '987442', 'Sanchez', 'Ethan', '1998-08-23', 'uploads/689c7d5a199ad_téléchargement (6).jpeg'),
(12, '435607', 'Mercier', 'Axel', '2000-09-05', 'uploads/689c7dd3a2167_téléchargement (7).jpeg'),
(13, '876457', 'Cameron', 'Luca', '1999-03-13', 'uploads/689c7e7518ca2_Download free image of Yearbook portrait necktie tuxedo blazer_ by Pinn about yearbook, texture, aesthetic, face, and light 12569737.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant_matieres`
--

CREATE TABLE `etudiant_matieres` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiant_matieres`
--

INSERT INTO `etudiant_matieres` (`id`, `etudiant_id`, `matiere_id`) VALUES
(20, 8, 8),
(21, 8, 9),
(22, 8, 10),
(23, 9, 1),
(24, 9, 6),
(25, 9, 9),
(32, 11, 1),
(33, 11, 2),
(34, 11, 3),
(35, 12, 5),
(36, 12, 7),
(37, 12, 8),
(38, 10, 4),
(39, 10, 8),
(40, 10, 9),
(41, 13, 1),
(42, 13, 3),
(43, 13, 4);

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

CREATE TABLE `matieres` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `coefficient` int(11) NOT NULL,
  `heure` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `matieres`
--

INSERT INTO `matieres` (`id`, `nom`, `coefficient`, `heure`) VALUES
(1, 'Mathématiques', 4, '08:00:00'),
(2, 'Physique', 3, '10:00:00'),
(3, 'Informatique', 5, '14:00:00'),
(4, 'Chimie', 2, '09:30:00'),
(5, 'Anglais', 1, '13:00:00'),
(6, 'Literature', 3, '14:00:00'),
(7, 'Science', 5, '10:00:00'),
(8, 'Biologie', 3, '13:00:00'),
(9, 'Music', 2, '16:00:00'),
(10, 'Art', 2, '09:00:00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricule` (`matricule`);

--
-- Index pour la table `etudiant_matieres`
--
ALTER TABLE `etudiant_matieres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`),
  ADD KEY `matiere_id` (`matiere_id`);

--
-- Index pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `etudiants`
--
ALTER TABLE `etudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `etudiant_matieres`
--
ALTER TABLE `etudiant_matieres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `matieres`
--
ALTER TABLE `matieres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `etudiant_matieres`
--
ALTER TABLE `etudiant_matieres`
  ADD CONSTRAINT `etudiant_matieres_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `etudiant_matieres_ibfk_2` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
