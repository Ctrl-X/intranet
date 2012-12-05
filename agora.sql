-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 05 Décembre 2012 à 08:36
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `agora`
--

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

CREATE TABLE IF NOT EXISTS `classe` (
  `id_classe` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(120) NOT NULL,
  `code` varchar(10) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `selectable` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_classe`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `classe`
--

INSERT INTO `classe` (`id_classe`, `nom`, `code`, `active`, `selectable`) VALUES
(1, 'préparatoire', 'prepa', 1, 1),
(2, 'design graphique 1', 'DG1', 1, 1),
(3, 'design graphique 2', 'DG2', 1, 1),
(4, 'design graphique 3', 'DG3', 1, 1),
(5, 'design graphique 4', 'DG4', 1, 1),
(6, 'WebDesigner', 'WD', 1, 1),
(7, 'WebDesigner Alternance', 'WD Alt', 1, 1),
(8, 'Infographiste', 'Infog', 1, 1),
(9, 'Infographiste Alternance', 'Infog Alt', 1, 1),
(10, 'cadre', 'cadre', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `id_module` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `css` varchar(120) NOT NULL,
  `script` varchar(150) NOT NULL,
  `order` int(11) NOT NULL,
  `installDate` int(11) NOT NULL,
  `cronTask` int(11) NOT NULL DEFAULT '0',
  `topMenu` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_module`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Structure de la table `signaled`
--

CREATE TABLE IF NOT EXISTS `signaled` (
  `id_signaled` int(11) NOT NULL AUTO_INCREMENT,
  `table` varchar(50) NOT NULL,
  `idrow` int(11) NOT NULL,
  PRIMARY KEY (`id_signaled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(40) NOT NULL,
  `nom` varchar(40) NOT NULL,
  `email` varchar(120) NOT NULL,
  `portrait` varchar(250) NOT NULL,
  `pass` varchar(250) NOT NULL,
  `statut` enum('étudiant','intervenant','administration') NOT NULL,
  `id_classe` int(11) NOT NULL,
  `rang` enum('inactif','normal','modérateur','admin','désactivé') NOT NULL DEFAULT 'inactif',
  `date_inscription` int(11) NOT NULL,
  `derniere_connexion` int(11) NOT NULL,
  `hash` varchar(250) NOT NULL,
  PRIMARY KEY (`id_utilisateur`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `prenom`, `nom`, `email`, `portrait`, `pass`, `statut`, `id_classe`, `rang`, `date_inscription`, `derniere_connexion`, `hash`) VALUES
(1, 'Gabin', 'Aureche', 'gabin.aureche@live.fr', '', 'caf973c16410b87b3a996405f421ec14', 'étudiant', 4, 'admin', 1353243137, 1354615727, '50a8da01ae4876.21831358'),
(2, 'Marcel', 'Dupont', 'd.marcel@live.fr', '', 'e10adc3949ba59abbe56e057f20f883e', 'étudiant', 1, 'inactif', 1354612162, 1354612252, '50bdbdc24ccc38.30010791');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
