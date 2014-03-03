-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 26 Février 2014 à 09:40
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `my_events`
--
CREATE DATABASE IF NOT EXISTS `my_events` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `my_events`;

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

CREATE TABLE IF NOT EXISTS `amis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_emetteur` int(11) NOT NULL,
  `utilisateur_destinataire` int(11) NOT NULL,
  `valide` tinyint(1) NOT NULL,
  `date_reponse` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9FE2E7611B5ADE49` (`utilisateur_emetteur`),
  KEY `IDX_9FE2E761975BDB61` (`utilisateur_destinataire`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Contenu de la table `amis`
--

INSERT INTO `amis` (`id`, `utilisateur_emetteur`, `utilisateur_destinataire`, `valide`, `date_reponse`) VALUES
(1, 5, 7, 0, NULL),
(3, 4, 10, 1, '2014-02-24 14:28:30'),
(4, 8, 10, 1, '2014-02-25 13:43:05'),
(5, 10, 11, 1, '2014-02-25 10:52:14'),
(6, 12, 10, 1, '2014-02-25 13:40:55');

-- --------------------------------------------------------

--
-- Structure de la table `manifestation`
--

CREATE TABLE IF NOT EXISTS `manifestation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_createur` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6F2B3F7FE4A3231B` (`utilisateur_createur`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Contenu de la table `manifestation`
--

INSERT INTO `manifestation` (`id`, `utilisateur_createur`, `nom`, `date_debut`, `date_fin`, `adresse`) VALUES
(1, 3, 'concert de ouff', '2014-02-24 00:00:00', '2014-02-19 00:00:00', '45 rue du sdkusqsdfp rennes'),
(2, 4, 'concert moins ouff', '2014-02-24 00:00:00', '2014-02-25 00:00:00', '12 rue du sdkusqsdfp rennes'),
(3, 3, 'concert de ouff', '2014-02-24 00:00:00', '2014-02-19 00:00:00', '45 rue du sdkusqsdfp rennes'),
(4, 4, 'concert moins ouff', '2014-02-24 00:00:00', '2014-02-25 00:00:00', '12 rue du sdkusqsdfp rennes'),
(5, 3, 'drgydrghsdf', '2014-02-10 00:00:00', '2014-02-12 00:00:00', 'wdfhdfgwdfg dfwgh qdfghdshfhsf'),
(6, 7, 'srthg qsesh', '2014-02-27 00:00:00', '2014-02-27 00:00:00', 'dfhsdf sfthsf hsxfh sffxgh '),
(7, 3, 'drgydrghsdf', '2014-02-10 00:00:00', '2014-02-12 00:00:00', 'wdfhdfgwdfg dfwgh qdfghdshfhsf'),
(8, 7, 'srthg qsesh', '2014-02-27 00:00:00', '2014-02-27 00:00:00', 'dfhsdf sfthsf hsxfh sffxgh '),
(9, 10, 'azerty', '2014-12-12 00:00:00', '2014-12-12 00:00:00', 'dflgijqsdùgjsdlgk'),
(10, 10, 'azerty', '2014-12-12 00:00:00', '2014-12-12 00:00:00', 'qslghqsdlmfkhsd');

-- --------------------------------------------------------

--
-- Structure de la table `marqueur`
--

CREATE TABLE IF NOT EXISTS `marqueur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manifestation` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `date_debut` datetime NOT NULL,
  `dete_fin` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7FEB8C216F2B3F7F` (`manifestation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `organisation`
--

CREATE TABLE IF NOT EXISTS `organisation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur` int(11) NOT NULL,
  `manifestation` int(11) NOT NULL,
  `marqueur` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E6E132B41D1C63B3` (`utilisateur`),
  KEY `IDX_E6E132B46F2B3F7F` (`manifestation`),
  KEY `IDX_E6E132B47FEB8C21` (`marqueur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `participation`
--

CREATE TABLE IF NOT EXISTS `participation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur` int(11) NOT NULL,
  `manifestation` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AB55E24F1D1C63B3` (`utilisateur`),
  KEY `IDX_AB55E24F6F2B3F7F` (`manifestation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1D1C63B392FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_1D1C63B3A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `prenom`, `nom`, `adresse`) VALUES
(3, 'dfgkhsq', 'dfgkhsq', 'sdfhg@dfslhku.fr', 'sdfhg@dfslhku.fr', 1, 'jytplckxv00o4o08wcsksc0gs8ogss8', 'U3auu61N19+QhGS2baA0vu3Tu2oZL7bWNfnp7CYohBBGCoN9F77RSHguzJQ/FvPij/axDP/aB7h29xrcFE6o6A==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'Robert', 'Jean', '12 rue du chat qui dort, 35200 Rennes'),
(4, 'sdhdf', 'sdhdf', 'sdfh@gcfh.gf', 'sdfh@gcfh.gf', 1, 'cszs4jbnytc0s4coggo004s80c0sogo', 'SdITwcUdzoYz3xJ78BJJ0aQut8mBBbT8n3mbtG0iPNz3dlhZ1y8EV0HvN4e0m6hFr9ISq9aKguxZrOypghunMA==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'bernard', 'philou', '12 rue de je sais pas quoi, rennes'),
(5, 'dfhsdf', 'dfhsdf', 'xh@wdfhdf.fr', 'xh@wdfhdf.fr', 1, '6pyt7t4vx9k4c8sc0ccswkgsks0kocg', 'JHuv85OYjfCWzYXbtRJmgdnA6XTFAhfm6OOIVFgIrXKMW6UhkZxIq8r4lkkjQ5xW3MaOoRPLru+fCEnSwY3XMw==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'bernard', 'philou', '12 rue de je sais pas quoi, rennes'),
(6, 'dfgdfkh', 'dfgdfkh', 'dflgn@dfkhsdf', 'dflgn@dfkhsdf', 1, 'flhnwr2iejkg8swo8c8oo4wgsokcsws', 'UctqZhtvdeGl191MFvYaFPCNkMckiCQLAON5sXnk15mQg4dzu/tyazwgV7FO+yx83HKDm8mkssmob/BEeFo+fw==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'bernard', 'aasdfgj', '12 rue de je sais pas quoi, rennes'),
(7, 'fdgh', 'fdgh', 'dfgkl', '', 1, '86ue8ledbn4sg8sks8c00ck4og40880', 'dXtKw8KVedhxlyn4FkMrTIuiWIVRTLZXvi6XdqVpjEjDBYVXN0CUu8ZYezXt4WzdliLPMds9eMK65JDnZfr++g==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'bernard', 'aasdfgj', '12 rue de je sais pas quoi, rennes'),
(8, 'dsfg', 'dsfg', 'sdfhsdf', 'sdfhsdf', 1, '8vhji6kos7c4s0o40owowskscgk4s04', '9hjvqgQNYuvooYH9DOFaCaoT7l60cHh1rZ7QFBfdPXvPdNpx7xWSqhuKmyjjD1T9rVdgDg/0877/1weBhkcaKw==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'bernard', 'aasdfgj', '12 rue de je sais pas quoi, rennes'),
(9, 'jhfg', 'jhfg', 'dxfhdfh', 'dxfhdfh', 1, '2hd0kqc2fvk084wo408w8oc080sscsk', '0BK/xPI9ZYRiY8oV1f6Qrnk8lqSP8YESv3niNlUFDSv1RixWaKR+RrDtbz7zNieAJ9rbnj/q4NoddIhHa83DzA==', '2014-02-20 14:16:20', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'bernard', 'aasdfgj', '12 rue de je sais pas quoi, rennes'),
(10, 'antho', 'antho', 'sqgqsdf@qsdgkhsd.fr', 'sqgqsdf@qsdgkhsd.fr', 1, 'q98rb8xdk2okss00cks8wcw0cgg4sg4', '3iTQFVuC/QsWbgC3Ksj9l/k96oDrAYPtc6zuDd7Tia8tUx3WwpNjbOsNBzmKp4iY/uXeeu4BVa1hrYed11Al1A==', '2014-02-25 11:27:57', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'hippy', 'gloups', '45 rue des alinne Rennes'),
(11, 'aze', 'aze', 'aze@aze.aze', 'aze@aze.aze', 1, 's7bszc78g1cowwcg084c0gcccwgw80s', 'dEjg9Lo7yEGfURqCUEewIjDt4qizjlfo+0iEJJzjdCNteJ4EBBi2gA5puGoy9EpukHNIXu1vewqy9elTs3SgrA==', '2014-02-24 14:35:44', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'bipbip', 'tutu', 'plouf plouf rennes 35200'),
(12, 'test', 'test', 'test@test.test', 'test@test.test', 1, 'f3t3eh08qzw4o4wk8008gk0kcscw8oo', 'Lp4nyMQzhPogM6YhMDLwgt2Qkn3wX8btn2VsQFNQNfBmnbmo6qgzCJ3L/1ZXEKd9qD7LXkwp9fMDTgMU/qRBNw==', '2014-02-25 13:35:54', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'Test', 'TEST', 'test');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `amis`
--
ALTER TABLE `amis`
  ADD CONSTRAINT `FK_9FE2E7611B5ADE49` FOREIGN KEY (`utilisateur_emetteur`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_9FE2E761975BDB61` FOREIGN KEY (`utilisateur_destinataire`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `manifestation`
--
ALTER TABLE `manifestation`
  ADD CONSTRAINT `FK_6F2B3F7FE4A3231B` FOREIGN KEY (`utilisateur_createur`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `marqueur`
--
ALTER TABLE `marqueur`
  ADD CONSTRAINT `FK_7FEB8C216F2B3F7F` FOREIGN KEY (`manifestation`) REFERENCES `manifestation` (`id`);

--
-- Contraintes pour la table `organisation`
--
ALTER TABLE `organisation`
  ADD CONSTRAINT `FK_E6E132B41D1C63B3` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_E6E132B46F2B3F7F` FOREIGN KEY (`manifestation`) REFERENCES `manifestation` (`id`),
  ADD CONSTRAINT `FK_E6E132B47FEB8C21` FOREIGN KEY (`marqueur`) REFERENCES `marqueur` (`id`);

--
-- Contraintes pour la table `participation`
--
ALTER TABLE `participation`
  ADD CONSTRAINT `FK_AB55E24F1D1C63B3` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_AB55E24F6F2B3F7F` FOREIGN KEY (`manifestation`) REFERENCES `manifestation` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
