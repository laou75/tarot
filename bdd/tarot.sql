-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 14 Juillet 2014 à 18:53
-- Version du serveur: 5.5.37-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `tarot`
--

-- --------------------------------------------------------

--
-- Structure de la table `joueurs`
--

CREATE TABLE IF NOT EXISTS `joueurs` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `nom` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `portrait` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nickname` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `mdp` varchar(32) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Table des joueurs' AUTO_INCREMENT=2 ;

--
-- Contenu de la table `menu`
--
INSERT INTO `joueurs` (`id`, `prenom`, `nom`, `portrait`, `nickname`, `mdp`) VALUES
(1, 'admin', 'admin', 'a3.jpg', 'admin', 'password');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(2) NOT NULL DEFAULT '0',
  `id_pere` int(2) NOT NULL DEFAULT '0',
  `ordre` int(2) NOT NULL DEFAULT '0',
  `visible_menu` int(1) NOT NULL DEFAULT '1',
  `url` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) NOT NULL DEFAULT '',
  `labelCourt` varchar(128) DEFAULT NULL,
  `icone` varchar(255) NOT NULL DEFAULT '',
  `glyphs` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`id_pere`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table des options de menu';

--
-- Contenu de la table `menu`
--

INSERT INTO `menu` (`id`, `id_pere`, `ordre`, `visible_menu`, `url`, `label`, `labelCourt`, `icone`, `glyphs`, `logo`, `description`) VALUES
(1, 0, 0, 1, 'index.php', 'Accueil', 'Accueil', '', NULL, 'home.gif', 'Bienvenue'),
(2, 1, 0, 1, 'tournois.php', 'Liste des tournois', 'Tournois', 'liste.gif', 'glyphicon glyphicon-list text-primary', 'liste.gif', 'Liste des tournois'),
(3, 2, 0, 0, 'ajoutertournoi.php', 'Ajouter un tournoi', 'Ajouter tournoi', 'ajouter.gif', 'glyphicon glyphicon-plus text-warning', 'ajouter.gif', 'Ajouter un tournoi'),
(4, 2, 0, 0, 'modifiertournoi.php', 'Modifier', 'Modifier', 'modifier.gif', 'glyphicon glyphicon-pencil text-warning', 'modifier.gif', 'Modifier un tournoi'),
(5, 2, 0, 0, 'supprimertournoi.php', 'Supprimer', 'Supprimer', 'supprimer.gif', 'glyphicon glyphicon-remove text-danger', 'supprimer.gif', 'Supprimer un tournoi'),
(10, 30, 0, 0, 'parties.php', 'Parties', 'Parties', 'liste.gif', 'glyphicon glyphicon-list text-primary', 'liste.gif', 'Liste des parties'),
(11, 10, 1, 0, 'ajouterpartie.php', 'Ajouter une partie', 'Ajouter partie', 'ajouter.gif', 'glyphicon glyphicon-plus text-warning', 'ajouter.gif', 'Ajouter une partie'),
(12, 10, 2, 0, 'modifierpartie.php', 'Modifier', 'Modifier', 'modifier.gif', 'glyphicon glyphicon-pencil text-warning', 'modifier.gif', 'Modifier une partie'),
(20, 1, 0, 1, 'joueurs.php', 'Liste des joueurs', 'Joueurs', 'liste.gif', 'glyphicon glyphicon-list text-primary', 'liste.gif', 'Liste des joueurs'),
(21, 20, 1, 0, 'ajouterjoueur.php', 'Ajouter un joueur', 'Ajouter joueur', 'ajouter.gif', 'glyphicon glyphicon-plus text-warning', 'ajouter.gif', 'Ajouter un joueur'),
(22, 20, 3, 0, 'modifierjoueur.php', 'Modifier', 'Modifier', 'modifier.gif', 'glyphicon glyphicon-pencil text-warning', 'modifier.gif', 'Modifier un joueur'),
(23, 20, 4, 0, 'supprimerjoueur.php', 'Supprimer', 'Supprimer', 'supprimer.gif', 'glyphicon glyphicon-remove text-danger', 'supprimer.gif', 'Supprimer un joueur'),
(13, 10, 4, 0, 'supprimerpartie.php', 'Supprimer', 'Supprimer', 'supprimer.gif', 'glyphicon glyphicon-remove text-danger', 'supprimer.gif', 'Supprimer une partie'),
(14, 10, 0, 0, '', 'Commentaires', 'Commentaires', 'commentaires.gif', 'glyphicon glyphicon-comment', 'commentaires.gif', 'Commentaires'),
(30, 2, 0, 0, '', 'Sessions', 'Sessions', 'liste.gif', 'glyphicon glyphicon-list text-primary', 'liste.gif', 'Liste des sessions'),
(31, 30, 1, 0, 'ajoutersession.php', 'Ajouter une session', 'Ajouter session', 'ajouter.gif', 'glyphicon glyphicon-plus text-warning', 'ajouter.gif', 'Ajouter une session'),
(32, 30, 2, 0, 'modifiersession.php', 'Modifier', 'Modifier', 'modifier.gif', 'glyphicon glyphicon-pencil text-warning', 'modifier.gif', 'Modifier une session'),
(33, 30, 4, 0, 'supprimersession.php', 'Supprimer', 'Supprimer', 'supprimer.gif', 'glyphicon glyphicon-remove text-danger', 'supprimer.gif', 'Supprimer une session'),
(34, 30, 0, 0, '', 'Statistiques', 'Statistiques', 'stats.gif', 'glyphicon glyphicon-stats text-primary', 'stats.gif', 'Statistiques de la session'),
(6, 2, 0, 0, '', 'Statistiques', 'Statistiques', 'stats.gif', 'glyphicon glyphicon-stats text-primary', 'stats.gif', 'Statistiques du tournoi');

-- --------------------------------------------------------

--
-- Structure de la table `nouvelles`
--

CREATE TABLE IF NOT EXISTS `nouvelles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `flash_info` tinyint(4) NOT NULL DEFAULT '0',
  `titre_flash_info` varchar(255) NOT NULL DEFAULT '',
  `date_issue` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `parties`
--

CREATE TABLE IF NOT EXISTS `parties` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `id_tournoi` int(6) NOT NULL DEFAULT '0',
  `id_session` int(6) NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL DEFAULT '0',
  `id_preneur` int(3) NOT NULL DEFAULT '0',
  `annonce` enum('Petite','Pousse','Garde','Garde sans le Chien','Garde contre le Chien') NOT NULL DEFAULT 'Petite',
  `nombre_bouts` int(1) NOT NULL DEFAULT '0',
  `petitaubout` int(1) NOT NULL DEFAULT '0',
  `poignee` enum('aucune','simple','double','triple') NOT NULL DEFAULT 'aucune',
  `chelem` int(1) NOT NULL DEFAULT '0',
  `chelem_annonce` int(1) NOT NULL DEFAULT '0',
  `chelem_reussi` int(1) NOT NULL DEFAULT '0',
  `annonce_reussie` int(1) NOT NULL DEFAULT '0',
  `points` float NOT NULL DEFAULT '0',
  `id_second` int(3) DEFAULT NULL,
  `mort1` int(3) DEFAULT NULL,
  `mort2` int(3) DEFAULT NULL,
  `mort3` int(3) DEFAULT NULL,
  `commentaires` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Table des tournois' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `r_parties_joueurs`
--

CREATE TABLE IF NOT EXISTS `r_parties_joueurs` (
  `id_tournoi` int(6) NOT NULL DEFAULT '0',
  `id_session` int(6) NOT NULL DEFAULT '0',
  `id_partie` int(6) NOT NULL DEFAULT '0',
  `id_joueur` int(3) NOT NULL DEFAULT '0',
  `type` enum('preneur','called','defense','mort') NOT NULL DEFAULT 'mort',
  `points` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_tournoi`,`id_session`,`id_partie`,`id_joueur`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table des joueurs par parties';

-- --------------------------------------------------------

--
-- Structure de la table `r_sessions_joueurs`
--

CREATE TABLE IF NOT EXISTS `r_sessions_joueurs` (
  `id_tournoi` int(6) NOT NULL DEFAULT '0',
  `id_session` int(6) NOT NULL DEFAULT '0',
  `id_joueur` int(3) NOT NULL DEFAULT '0',
  `position` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_tournoi`,`id_session`,`id_joueur`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table des joueurs par parties';

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `id_tournoi` int(6) NOT NULL DEFAULT '0',
  `datedeb` int(10) NOT NULL DEFAULT '0',
  `datefin` int(10) DEFAULT NULL,
  `commentaires` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Table des tournois' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `stages`
--

CREATE TABLE IF NOT EXISTS `stages` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `lieu` int(5) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nbmax` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table des stages programmés' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tournois`
--

CREATE TABLE IF NOT EXISTS `tournois` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `datedeb` int(10) NOT NULL DEFAULT '0',
  `datefin` int(10) DEFAULT NULL,
  `commentaires` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Table des tournois' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
