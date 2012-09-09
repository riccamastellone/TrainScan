-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Set 09, 2012 alle 09:51
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
-- Struttura della tabella `operatori`
--

CREATE TABLE IF NOT EXISTS `operatori` (
  `id` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `nome_operatore` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `operatori`
--

INSERT INTO `operatori` (`id`, `nome_operatore`) VALUES
('I', 'ItaloTreno'),
('T', 'Trenitalia');

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
  `indirizzo_ip` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `preventivi`
--

INSERT INTO `preventivi` (`id`, `id_origine`, `id_destinazione`, `data`, `data_generazione`, `indirizzo_ip`) VALUES
(1, 'Firenze', 'Milano', '2012-09-23', '2012-09-09 09:51:02', '127.0.0.1');

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
  `durata` time DEFAULT NULL,
  `id_offerta` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=42 ;

--
-- Dump dei dati per la tabella `preventivi_result`
--

INSERT INTO `preventivi_result` (`id`, `id_preventivo`, `codice_treno`, `partenza`, `arrivo`, `id_classe`, `prezzo`, `id_operatore`, `durata`, `id_offerta`) VALUES
(1, 1, 9907, '06:19:00', '08:15:00', 'C', 73, 'I', NULL, NULL),
(2, 1, 9911, '07:19:00', '09:15:00', 'C', 73, 'I', NULL, NULL),
(3, 1, 9915, '08:19:00', '10:15:00', 'C', 73, 'I', NULL, NULL),
(4, 1, 9923, '10:19:00', '12:15:00', 'C', 73, 'I', NULL, NULL),
(5, 1, 9927, '11:19:00', '13:15:00', 'C', 73, 'I', NULL, NULL),
(6, 1, 9931, '12:19:00', '14:15:00', 'C', 73, 'I', NULL, NULL),
(7, 1, 9943, '15:19:00', '17:15:00', 'C', 73, 'I', NULL, NULL),
(8, 1, 9951, '17:19:00', '19:15:00', 'C', 73, 'I', NULL, NULL),
(9, 1, 9959, '19:19:00', '21:15:00', 'C', 73, 'I', NULL, NULL),
(10, 1, 9570, '11:30:00', '13:26:00', '1', 71, 'T', '01:56:00', 1),
(11, 1, 9570, '11:30:00', '13:26:00', '2', 50, 'T', '01:56:00', 1),
(12, 1, 9520, '11:55:00', '13:40:00', '1', 71, 'T', '01:45:00', 1),
(13, 1, 9520, '11:55:00', '13:40:00', '2', 50, 'T', '01:45:00', 1),
(14, 1, 9526, '12:55:00', '14:40:00', '1', 71, 'T', '01:45:00', 1),
(15, 1, 9526, '12:55:00', '14:40:00', '2', 50, 'T', '01:45:00', 1),
(16, 1, 9528, '13:55:00', '15:40:00', '1', 71, 'T', '01:45:00', 1),
(17, 1, 9528, '13:55:00', '15:40:00', '2', 50, 'T', '01:45:00', 1),
(18, 1, 9532, '14:55:00', '16:40:00', '1', 71, 'T', '01:45:00', 1),
(19, 1, 9532, '14:55:00', '16:40:00', '2', 50, 'T', '01:45:00', 1),
(20, 1, 9536, '15:55:00', '17:40:00', '1', 71, 'T', '01:45:00', 1),
(21, 1, 9536, '15:55:00', '17:40:00', '2', 50, 'T', '01:45:00', 1),
(22, 1, 9572, '16:30:00', '18:26:00', '1', 71, 'T', '01:56:00', 1),
(23, 1, 9572, '16:30:00', '18:26:00', '2', 50, 'T', '01:56:00', 1),
(24, 1, 9540, '16:55:00', '18:40:00', '1', 71, 'T', '01:45:00', 1),
(25, 1, 9540, '16:55:00', '18:40:00', '2', 50, 'T', '01:45:00', 1),
(26, 1, 9574, '17:30:00', '19:26:00', '1', 71, 'T', '01:56:00', 1),
(27, 1, 9574, '17:30:00', '19:26:00', '2', 50, 'T', '01:56:00', 1),
(28, 1, 9544, '17:55:00', '19:40:00', '1', 71, 'T', '01:45:00', 1),
(29, 1, 9544, '17:55:00', '19:40:00', '2', 50, 'T', '01:45:00', 1),
(30, 1, 9550, '18:55:00', '20:40:00', '1', 71, 'T', '01:45:00', 1),
(31, 1, 9550, '18:55:00', '20:40:00', '2', 50, 'T', '01:45:00', 1),
(32, 1, 9578, '19:30:00', '21:26:00', '1', 71, 'T', '01:56:00', 1),
(33, 1, 9578, '19:30:00', '21:26:00', '2', 50, 'T', '01:56:00', 1),
(34, 1, 9552, '19:55:00', '21:40:00', '1', 71, 'T', '01:45:00', 1),
(35, 1, 9552, '19:55:00', '21:40:00', '2', 50, 'T', '01:45:00', 1),
(36, 1, 9586, '20:00:00', '21:45:00', '1', 71, 'T', '01:45:00', 1),
(37, 1, 9586, '20:00:00', '21:45:00', '2', 50, 'T', '01:45:00', 1),
(38, 1, 9558, '20:55:00', '22:40:00', '1', 71, 'T', '01:45:00', 1),
(39, 1, 9558, '20:55:00', '22:40:00', '2', 50, 'T', '01:45:00', 1),
(40, 1, 9560, '21:55:00', '23:40:00', '1', 71, 'T', '01:45:00', 1),
(41, 1, 9560, '21:55:00', '23:40:00', '2', 50, 'T', '01:45:00', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `trenitalia_classi`
--

CREATE TABLE IF NOT EXISTS `trenitalia_classi` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `codice_classe` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `nome_classe` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `trenitalia_classi`
--

INSERT INTO `trenitalia_classi` (`id`, `codice_classe`, `nome_classe`) VALUES
(1, '1', '1° Classe'),
(2, '2', '2° Classe');

-- --------------------------------------------------------

--
-- Struttura della tabella `trenitalia_stazioni`
--

CREATE TABLE IF NOT EXISTS `trenitalia_stazioni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `railwaycode` int(11) NOT NULL,
  `stationcode` int(11) NOT NULL,
  `nome_stazione` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=88 ;

--
-- Dump dei dati per la tabella `trenitalia_stazioni`
--

INSERT INTO `trenitalia_stazioni` (`id`, `railwaycode`, `stationcode`, `nome_stazione`) VALUES
(1, 83, 1650, 'Milano'),
(2, 83, 25005, 'Milano Affori'),
(3, 83, 1642, 'Milano Bovisa'),
(4, 83, 25006, 'Milano Bruzzano'),
(5, 83, 1700, 'Milano Centrale'),
(6, 83, 1640, 'Milano Certosa'),
(7, 83, 1665, 'Milano Dateo'),
(8, 83, 25750, 'Milano Domodossola'),
(9, 83, 1326, 'Milano Greco Pirelli'),
(10, 83, 1701, 'Milano Lambrate'),
(11, 83, 1661, 'Milano Lancetti'),
(12, 83, 25635, 'Milano Malpensa Airport'),
(13, 83, 5814, 'Milano Marittima'),
(14, 83, 1642, 'Milano Nord Bovisa'),
(15, 83, 25001, 'Milano Nord Cadorna'),
(16, 83, 25750, 'Milano Nord Domodossola'),
(17, 83, 1645, 'Milano Porta Garibaldi'),
(18, 83, 1662, 'Milano Porta Garibaldi Passante'),
(19, 83, 1631, 'Milano Porta Genova'),
(20, 83, 1632, 'Milano Porta Romana'),
(21, 83, 1664, 'Milano Porta Venezia'),
(22, 83, 1633, 'Milano Porta Vittoria'),
(23, 83, 25751, 'Milano Quarto Oggiaro'),
(24, 83, 1663, 'Milano Repubblica'),
(25, 83, 1820, 'Milano Rogoredo'),
(26, 83, 1032, 'Milano Romolo'),
(27, 83, 1630, 'Milano S. Cristoforo'),
(28, 83, 1666, 'Milano Villapizzone'),
(29, 83, 27207, 'BOLOGNA RIMESSE'),
(30, 83, 27295, 'BOLOGNA S. RITA'),
(31, 83, 27204, 'BOLOGNA S. VITALE'),
(32, 83, 27297, 'BOLOGNA VIA LARGA'),
(33, 83, 5999, 'Bologna'),
(34, 83, 5999, 'Bologna ( Tutte Le Stazioni )'),
(35, 83, 5100, 'Bologna Borgo Panigale'),
(36, 83, 5043, 'Bologna Centrale'),
(37, 83, 5725, 'Bologna Corticella'),
(38, 83, 5323, 'Bologna Panigale Scala'),
(39, 83, 5130, 'Bologna S. Ruffillo'),
(40, 83, 6998, 'Firenze'),
(41, 83, 6900, 'Firenze Campo di Marte'),
(42, 83, 6515, 'Firenze Cascine'),
(43, 83, 6419, 'Firenze Castello'),
(44, 83, 6518, 'Firenze Porta a Prato'),
(45, 83, 6420, 'Firenze Rifredi'),
(46, 83, 6901, 'Firenze Rovezzano'),
(47, 83, 6421, 'Firenze S. M. Novella'),
(48, 83, 6950, 'Firenze S. Marco Vecchio'),
(49, 83, 6430, 'Firenze Statuto'),
(50, 83, 8349, 'Roma'),
(51, 83, 8349, 'Roma ( Tutte Le Stazioni )'),
(52, 83, 8412, 'Roma Aeroporto Fiumicino'),
(53, 83, 8025, 'Roma Aurelia'),
(54, 83, 8325, 'Roma Balduina'),
(55, 83, 8674, 'Roma Casilina'),
(56, 83, 8322, 'Roma Monte Mario'),
(57, 83, 8224, 'Roma Nomentana'),
(58, 83, 8406, 'Roma Ostiense'),
(59, 83, 8500, 'Roma Prenestina'),
(60, 83, 8005, 'Roma Quattro Venti'),
(61, 83, 8328, 'Roma S. Filippo Neri'),
(62, 83, 8323, 'Roma S. Pietro'),
(63, 83, 8409, 'Roma Termini'),
(64, 83, 8217, 'Roma Tiburtina'),
(65, 83, 8405, 'Roma Trastevere'),
(66, 83, 8408, 'Roma Tuscolana'),
(67, 83, 109, 'Romagnano Sesia'),
(68, 83, 9829, 'Romagnano-Vietri-Salvitelle'),
(69, 83, 1711, 'Romano'),
(70, 83, 2337, 'Romanore'),
(71, 83, 16506, 'NAPOLI MARITTIMA'),
(72, 83, 86708, 'NAPOLI MOLO MERGELLINA'),
(73, 83, 9993, 'Napoli'),
(74, 83, 9993, 'Napoli ( Tutte Le Stazioni )'),
(75, 83, 9103, 'Napoli Campi Flegrei'),
(76, 83, 9218, 'Napoli Centrale'),
(77, 83, 9110, 'Napoli Gianturco'),
(78, 83, 9105, 'Napoli Mergellina'),
(79, 83, 86707, 'Napoli Molo Beverello'),
(80, 83, 9107, 'Napoli Montesanto'),
(81, 83, 9108, 'Napoli P.Za Cavour'),
(82, 83, 9109, 'Napoli P.Za Garibaldi'),
(83, 83, 9106, 'Napoli Piazza Amedeo'),
(84, 83, 9104, 'Napoli Piazza Leopardi'),
(85, 83, 9800, 'Napoli S. Giovanni-Barra'),
(86, 83, 9818, 'Salerno'),
(87, 83, 9532, 'Salerno Irno');

-- --------------------------------------------------------

--
-- Struttura della tabella `trenitalia_tariffe`
--

CREATE TABLE IF NOT EXISTS `trenitalia_tariffe` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `codice_offerta` int(11) NOT NULL,
  `nome_offerta` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `trenitalia_tariffe`
--

INSERT INTO `trenitalia_tariffe` (`id`, `codice_offerta`, `nome_offerta`) VALUES
(1, 83, 'Super Economy'),
(2, 82, 'Economy'),
(3, 1, 'Base');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
