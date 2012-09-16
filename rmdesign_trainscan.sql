-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Set 16, 2012 alle 15:15
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
  `descrizione` text COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `italo_classi`
--

INSERT INTO `italo_classi` (`id`, `codice_classe`, `nome_classe`, `descrizione`) VALUES
(1, 'S', 'Smart', 'Sedili in pelle reclinabili\r\nFasciatoio\r\nServizio ristorazione\r\nDistributori automatici\r\nWi-Fi gratuito\r\nCarrozza cinema'),
(2, 'C', 'Club', 'Sedili in pelle reclinabili\r\nServizio ristorazione al posto\r\nWi-Fi gratuito\r\nTouch Screen personale da 9" con Tv Live\r\nSoluzione salotto'),
(3, 'P', 'Prima', 'Sedili in pelle reclinabili\r\nServizio ristorazione al posto\r\nWi-Fi gratuito\r\nCarrozza Relax\r\nServizio di benvenuto');

-- --------------------------------------------------------

--
-- Struttura della tabella `italo_tariffe`
--

CREATE TABLE IF NOT EXISTS `italo_tariffe` (
  `id` int(11) NOT NULL,
  `codice_offerta` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `nome_offerta` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `descrizione_offerta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `italo_tariffe`
--

INSERT INTO `italo_tariffe` (`id`, `codice_offerta`, `nome_offerta`, `descrizione_offerta`) VALUES
(1, '1', 'Base', 'Modificabile e rimborsabile\r\n<strong>Cambio Nome</strong> Gratuito\r\n<strong>Modifica Data/Orario</strong> Gratuito\r\n<strong>Rinuncia al viaggio</strong>  Trattenuta 20%\r\n<strong>Punti Italo Più</strong> Si\r\n\r\nLa modifica è possibile fino a tre minuti prima della partenza'),
(2, '2', 'Economy', 'Modificabile con integrazione e non rimborsabile\r\n<strong>Cambio Nome</strong> Gratuito\r\n<strong>Modifica Data/Orario</strong> Integrazione 10%\r\n<strong>Rinuncia al viaggio</strong>  Non rimborsabile\r\n<strong>Punti Italo Più</strong> Si'),
(3, '3', 'Low Cost', 'Non modificabile e non rimborsabile\r\n<strong>Cambio Nome</strong> Gratuito\r\n<strong>Modifica Data/Orario</strong> Non permessa\r\n<strong>Rinuncia al viaggio</strong>  Non rimborsabile\r\n<strong>Punti Italo Più</strong> Si'),
(4, '4', 'Altro', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `operatori`
--

CREATE TABLE IF NOT EXISTS `operatori` (
  `id` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `nome_operatore` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `path_logo` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `descrizione` text COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `operatori`
--

INSERT INTO `operatori` (`id`, `nome_operatore`, `path_logo`, `descrizione`) VALUES
('I', 'ItaloTreno', '/assets/img/italotreno.png', 'Nuovo Trasporto Viaggiatori (NTV) è una società per azioni italiana che opera nel campo dei trasporti ferroviari ad alta velocità.'),
('T', 'Trenitalia', '/assets/img/trenitalia.png', 'Trenitalia S.p.A. è un''azienda partecipata al 100% da Ferrovie dello Stato Italiane, ed è la principale società italiana per la gestione del trasporto ferroviario di passeggeri e merci.');

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
  `durata` time DEFAULT NULL,
  `id_offerta` tinyint(4) DEFAULT NULL,
  `id_partenza` int(11) DEFAULT NULL,
  `id_arrivo` int(11) DEFAULT NULL,
  `fermate` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `trenitalia_classi`
--

CREATE TABLE IF NOT EXISTS `trenitalia_classi` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `codice_classe` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `nome_classe` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `descrizione` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dump dei dati per la tabella `trenitalia_classi`
--

INSERT INTO `trenitalia_classi` (`id`, `codice_classe`, `nome_classe`, `descrizione`) VALUES
(1, '1', '1° Classe', 'Poltrone in pelle\r\nPareti divisorie in cristallo \r\nPiù spazio per i bagagli\r\nServizio di benvenuto con snack e bevande serviti al posto e quotidiani al mattino.   \r\nPortale Frecciarossa\r\nServizio bar/ristorante'),
(2, '2', '2° Classe', 'Nuova illuminazione con luci led\r\nPortale Frecciarossa\r\nServizio bar/ristorante'),
(3, '3', 'Executive', 'Attesa nei FrecciaClub\r\nAccolti al binario di partenza del treno dal nostro personale\r\nPoltrone in pelle\r\nReclining servo-assistito con comandi al bracciolo fino a 122° e poggiagambe regolabile\r\nDistanza fra le poltrone fino a 1,5 metri\r\nPareti divisorie in cristallo \r\nPiù spazio per i bagagli\r\nArea del Silenzio\r\nWelcome drink con prodotti e bevande fresche e calde di alta qualità e quotidiani nazionali ed internazionali al mattino\r\nPortale Frecciarossa\r\nServizio bar/ristorante\r\nSala meeting'),
(4, '4', 'Business', 'Accolti al binario di partenza del treno dal nostro personale\r\nPoltrone in pelle\r\nReclining servo-assistito con comandi al bracciolo fino a 115°\r\nPareti divisorie in cristallo \r\nPiù spazio per i bagagli\r\nArea del Silenzio\r\nWelcome drink con prodotti e bevande fresche e calde di alta qualità e quotidiani nazionali ed internazionali al mattino\r\nPortale Frecciarossa\r\nServizio bar/ristorante\r\nSalottini BusinessPlus'),
(5, '5', 'Premium', 'Poltrone in pelle\r\nPareti divisorie in cristallo \r\nPiù spazio per i bagagli\r\nServizio di benvenuto con snack e bevande serviti al posto e quotidiani al mattino.   \r\nPortale Frecciarossa\r\nServizio bar/ristorante'),
(6, '6', 'Standard', 'Nuova illuminazione con luci led\r\nPortale Frecciarossa\r\nServizio bar/ristorante');

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
  `descrizione_offerta` text COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `trenitalia_tariffe`
--

INSERT INTO `trenitalia_tariffe` (`id`, `codice_offerta`, `nome_offerta`, `descrizione_offerta`) VALUES
(1, 83, 'Super Economy', ''),
(2, 82, 'Economy', ''),
(3, 1, 'Base', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
