-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Set 08, 2012 alle 14:09
-- Versione del server: 5.5.16
-- Versione PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rmdesign_trainscan`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `italo_classi`
--

CREATE TABLE IF NOT EXISTS `italo_classi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codice_classe` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `nome_classe` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `italo_classi`
--

INSERT INTO `italo_classi` (`id`, `codice_classe`, `nome_classe`) VALUES
(1, 'S', 'Smart'),
(2, 'C', 'Club'),
(3, 'P', 'Prima');

-- --------------------------------------------------------

--
-- Struttura della tabella `preventivi`
--

CREATE TABLE IF NOT EXISTS `preventivi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_origine` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `id_destinazione` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `data` date NOT NULL,
  `data_generazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `preventivi_result`
--

CREATE TABLE IF NOT EXISTS `preventivi_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_preventivo` int(11) NOT NULL,
  `codice_treno` int(11) NOT NULL,
  `partenza` time NOT NULL,
  `arrivo` time NOT NULL,
  `id_classe` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `prezzo` int(11) NOT NULL,
  `id_operatore` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
