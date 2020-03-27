-- phpMyAdmin SQL Dump
-- version 5.0.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mar 27, 2020 alle 19:43
-- Versione del server: 8.0.19
-- Versione PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_riassunty`
--
DROP DATABASE IF EXISTS `my_riassunty`;
CREATE DATABASE IF NOT EXISTS `my_riassunty` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `my_riassunty`;

-- --------------------------------------------------------

--
-- Struttura della tabella `Cronologia`
--

DROP TABLE IF EXISTS `Cronologia`;
CREATE TABLE `Cronologia` (
  `IDUtente` int NOT NULL,
  `IDRiassunto` int NOT NULL,
  `DataVisualizzazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Materie`
--

DROP TABLE IF EXISTS `Materie`;
CREATE TABLE `Materie` (
  `IDMateria` int NOT NULL,
  `Materia` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Indirizzo` enum('Trasversale','Informatica','Biologico Sanitario','Biologico Ambientale','Elettronica','Meccanica','Chimica e Materiali','Automazione') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `Materie`
--

INSERT INTO `Materie` (`IDMateria`, `Materia`, `Indirizzo`) VALUES
(1, 'Storia', 'Trasversale'),
(2, 'Italiano', 'Trasversale'),
(3, 'Informatica', 'Informatica'),
(4, 'Sistemi', 'Informatica'),
(5, 'TPSIT', 'Informatica'),
(6, 'Gestione', 'Informatica'),
(7, 'Analitica', 'Chimica e Materiali'),
(8, 'Organica', 'Biologico Sanitario'),
(9, 'Sistemi', 'Automazione'),
(10, 'Fisica', 'Biologico Ambientale'),
(11, 'Tecnologia', 'Meccanica'),
(12, 'Elettrotecnica', 'Elettronica'),
(13, 'Matematica', 'Trasversale'),
(14, 'Chimica', 'Chimica e Materiali'),
(15, 'Meccanica', 'Meccanica'),
(16, 'Sistemi', 'Meccanica'),
(17, 'Disegno', 'Meccanica'),
(18, 'TPSEE', 'Automazione'),
(19, 'Elettrotecnica', 'Automazione'),
(20, 'Biologia', 'Biologico Ambientale'),
(21, 'Organica', 'Biologico Ambientale'),
(22, 'Analitica', 'Biologico Ambientale'),
(23, 'Biologia', 'Trasversale'),
(24, 'Anatomia', 'Biologico Sanitario'),
(25, 'Analitica', 'Biologico Sanitario'),
(26, 'Organica', 'Chimica e Materiali'),
(27, 'Sistemi Automatici', 'Automazione'),
(28, 'TPSEE', 'Elettronica'),
(29, 'Sistemi', 'Elettronica'),
(30, 'Telecomunicazioni', 'Informatica'),
(31, 'Inglese', 'Trasversale'),
(32, 'Fisica', 'Trasversale'),
(33, 'Chimica', 'Trasversale'),
(34, 'Biologia', 'Biologico Sanitario'),
(35, 'Scienze', 'Trasversale'),
(36, 'Tecnologie Informatiche', 'Trasversale'),
(37, 'STA', 'Trasversale');

-- --------------------------------------------------------

--
-- Struttura della tabella `Parole`
--

DROP TABLE IF EXISTS `Parole`;
CREATE TABLE `Parole` (
  `IDParola` int NOT NULL,
  `IDRiassunto` int NOT NULL,
  `Parola` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Riassunti`
--

DROP TABLE IF EXISTS `Riassunti`;
CREATE TABLE `Riassunti` (
  `IDRiassunto` int NOT NULL,
  `IDUtente` int NOT NULL,
  `Titolo` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `UrlPDF` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `UrlIMG` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `IDMateria` int DEFAULT NULL,
  `Anno` enum('1','2','3','4','5') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `DataPubblicazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `Riassunti`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `RiassuntiApprovati`
--

DROP TABLE IF EXISTS `RiassuntiApprovati`;
CREATE TABLE `RiassuntiApprovati` (
  `IDRiassunto` int NOT NULL,
  `DataApprovazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ApprovatoDa` int NOT NULL,
  `Stato` enum('Visibile','Non Visibile') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `RiassuntiApprovati`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `RiassuntiTemporanei`
--

DROP TABLE IF EXISTS `RiassuntiTemporanei`;
CREATE TABLE `RiassuntiTemporanei` (
  `IDRiassunto` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `IDUtente` int NOT NULL,
  `Nome` varchar(1000) NOT NULL,
  `DataCreazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `RiassuntiTemporanei`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Tokens`
--

DROP TABLE IF EXISTS `Tokens`;
CREATE TABLE `Tokens` (
  `ID` int NOT NULL,
  `Token` varchar(128) NOT NULL,
  `IDUtente` int NOT NULL,
  `Scadenza` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Struttura della tabella `Utenti`
--

DROP TABLE IF EXISTS `Utenti`;
CREATE TABLE `Utenti` (
  `IDUtente` int NOT NULL,
  `IDGoogle` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Mail` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Ruolo` enum('Master','Docente','Studente') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `Utenti`
--

INSERT INTO `Utenti` (`IDUtente`, `IDGoogle`, `Mail`, `Username`, `Ruolo`) VALUES
(1, '101708114242190573416', 'tommaso.vincioni@gmail.com', 'tom', 'Master'),
(2, '109232597291200925390', 'lgvitiello9@gmail.com', 'Davide Vitiello', 'Master'),
(3, '103115444144048531524', 'fporjgpoejgpoerj@gmail.com', 'Prova1 Prova1', 'Studente'),
(4, '101975289005652954705', 'lellolellolellolello7@gmail.com', 'LelloLello LelloLello', 'Studente'),
(7, '108473214175649616671', 'giuseppecaruso003@gmail.com', 'Giuseppe Caruso', 'Studente'),
(8, '103009610739803811870', 'davide.vitiello@studenti.itisarezzo.it', 'Davide Vitiello', 'Studente');

-- --------------------------------------------------------

--
-- Struttura della tabella `Valutazioni`
--

DROP TABLE IF EXISTS `Valutazioni`;
CREATE TABLE `Valutazioni` (
  `IDValutazione` int NOT NULL,
  `IDRiassunto` int DEFAULT NULL,
  `Valutazione` enum('1','2','3','4','5') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `VersioniRiassuntiTemporanei`
--

DROP TABLE IF EXISTS `VersioniRiassuntiTemporanei`;
CREATE TABLE `VersioniRiassuntiTemporanei` (
  `IDFile` varchar(40) NOT NULL,
  `IDRiassunto` varchar(128) NOT NULL,
  `Posizione` varchar(1000) NOT NULL,
  `UltimaModifica` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `VersioniRiassuntiTemporanei`
--

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `v_riassuntiapprovati`
-- (Vedi sotto per la vista effettiva)
--


-- --------------------------------------------------------

create or replace view `v_RiassuntiApprovati`  AS (  select `Riassunti`.`IDRiassunto` AS `IDRiassunto`,`Riassunti`.`Titolo` AS `Titolo`,`Riassunti`.`UrlPDF` AS `UrlPDF`,`Riassunti`.`UrlIMG` AS `UrlIMG`,`Riassunti`.`IDMateria` AS `IDMateria`,`Riassunti`.`Anno` AS `Anno`,`Riassunti`.`DataPubblicazione` AS `DataPubblicazione`,`Riassunti`.`IDUtente` AS `IDUtente`,avg(`Valutazioni`.`Valutazione`) AS `Val` from ((`Riassunti` left join `Valutazioni` on((`Riassunti`.`IDRiassunto` = `Valutazioni`.`IDRiassunto`))) join `RiassuntiApprovati` on((`Riassunti`.`IDRiassunto` = `RiassuntiApprovati`.`IDRiassunto`))) group by `Riassunti`.`IDRiassunto`,`Riassunti`.`Titolo`,`Riassunti`.`UrlPDF`,`Riassunti`.`UrlIMG`,`Riassunti`.`Anno`,`Riassunti`.`IDMateria`,`Riassunti`.`DataPubblicazione`,`Riassunti`.`IDUtente` order by `Val`,`Riassunti`.`DataPubblicazione` desc );

create or replace view  `v_RiassuntiNonAprrovati`  AS  (select `Riassunti`.`IDRiassunto` AS `IDRiassunto`,`Riassunti`.`Titolo` AS `Titolo`,`Riassunti`.`UrlPDF` AS `UrlPDF`,`Riassunti`.`UrlIMG` AS `UrlIMG`,`Riassunti`.`IDMateria` AS `IDMateria`,`Riassunti`.`Anno` AS `Anno`,`Riassunti`.`DataPubblicazione` AS `DataPubblicazione`,avg(`Valutazioni`.`Valutazione`) AS `Val` from (`Riassunti` left join `Valutazioni` on((`Riassunti`.`IDRiassunto` = `Valutazioni`.`IDRiassunto`))) where ((0 <> 1) and `Riassunti`.`IDRiassunto` in (select `r2`.`IDRiassunto` from `RiassuntiApprovati` `r2`) is false) group by `Riassunti`.`IDRiassunto`,`Riassunti`.`Titolo`,`Riassunti`.`UrlPDF`,`Riassunti`.`UrlIMG`,`Riassunti`.`Anno`,`Riassunti`.`IDMateria`,`Riassunti`.`DataPubblicazione`,`Riassunti`.`IDUtente` order by `Val`,`Riassunti`.`DataPubblicazione` desc) ;

create or replace view  `v_RiassuntiTemporanei` AS ( select `RiassuntiTemporanei`.`Nome` AS `Nome`,`RiassuntiTemporanei`.`IDUtente` AS `IDUtente`,`RiassuntiTemporanei`.`IDRiassunto` AS `IDRiassunto`,`VersioniRiassuntiTemporanei`.`IDFile` AS `IDFile`,`RiassuntiTemporanei`.`DataCreazione` AS `DataCreazione`,`VersioniRiassuntiTemporanei`.`UltimaModifica` AS `UltimaModifica`,`VersioniRiassuntiTemporanei`.`Posizione` AS `Posizione` from (`RiassuntiTemporanei` join `VersioniRiassuntiTemporanei` on((`RiassuntiTemporanei`.`IDRiassunto` = `VersioniRiassuntiTemporanei`.`IDRiassunto`))) order by `VersioniRiassuntiTemporanei`.`UltimaModifica` desc) ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Cronologia`
--
ALTER TABLE `Cronologia`
  ADD KEY `fk_maiale` (`IDUtente`),
  ADD KEY `fk_chiodi` (`IDRiassunto`);

--
-- Indici per le tabelle `Materie`
--
ALTER TABLE `Materie`
  ADD PRIMARY KEY (`IDMateria`);

--
-- Indici per le tabelle `Parole`
--
ALTER TABLE `Parole`
  ADD PRIMARY KEY (`IDParola`),
  ADD KEY `fk_riassunti` (`IDRiassunto`);

--
-- Indici per le tabelle `Riassunti`
--
ALTER TABLE `Riassunti`
  ADD PRIMARY KEY (`IDRiassunto`),
  ADD UNIQUE KEY `UrlPDF` (`UrlPDF`),
  ADD KEY `IDMateria` (`IDMateria`),
  ADD KEY `fk_Utenti` (`IDUtente`);

--
-- Indici per le tabelle `RiassuntiApprovati`
--
ALTER TABLE `RiassuntiApprovati`
  ADD UNIQUE KEY `IDRiassunto` (`IDRiassunto`),
  ADD KEY `fk_Approvazione_Utente` (`ApprovatoDa`);

--
-- Indici per le tabelle `RiassuntiTemporanei`
--
ALTER TABLE `RiassuntiTemporanei`
  ADD PRIMARY KEY (`IDRiassunto`),
  ADD KEY `fk_riass_utente` (`IDUtente`);

--
-- Indici per le tabelle `Tokens`
--
ALTER TABLE `Tokens`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Token` (`Token`),
  ADD KEY `fk_Token_Utenti` (`IDUtente`);

--
-- Indici per le tabelle `Utenti`
--
ALTER TABLE `Utenti`
  ADD PRIMARY KEY (`IDUtente`);

--
-- Indici per le tabelle `Valutazioni`
--
ALTER TABLE `Valutazioni`
  ADD PRIMARY KEY (`IDValutazione`),
  ADD KEY `fk_Riassunti_Valutazioni` (`IDRiassunto`);

--
-- Indici per le tabelle `VersioniRiassuntiTemporanei`
--
ALTER TABLE `VersioniRiassuntiTemporanei`
  ADD PRIMARY KEY (`IDFile`),
  ADD UNIQUE KEY `IDFile` (`IDFile`,`IDRiassunto`),
  ADD KEY `fk_Versioni_RiassuntiTemp` (`IDRiassunto`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Materie`
--
ALTER TABLE `Materie`
  MODIFY `IDMateria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT per la tabella `Parole`
--
ALTER TABLE `Parole`
  MODIFY `IDParola` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Riassunti`
--
ALTER TABLE `Riassunti`
  MODIFY `IDRiassunto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `Tokens`
--
ALTER TABLE `Tokens`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT per la tabella `Utenti`
--
ALTER TABLE `Utenti`
  MODIFY `IDUtente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `Valutazioni`
--
ALTER TABLE `Valutazioni`
  MODIFY `IDValutazione` int NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Cronologia`
--
ALTER TABLE `Cronologia`
  ADD CONSTRAINT `fk_chiodi` FOREIGN KEY (`IDRiassunto`) REFERENCES `RiassuntiApprovati` (`IDRiassunto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_maiale` FOREIGN KEY (`IDUtente`) REFERENCES `Utenti` (`IDUtente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `Parole`
--
ALTER TABLE `Parole`
  ADD CONSTRAINT `fk_riassunti` FOREIGN KEY (`IDRiassunto`) REFERENCES `Riassunti` (`IDRiassunto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `Riassunti`
--
ALTER TABLE `Riassunti`
  ADD CONSTRAINT `fk_materie` FOREIGN KEY (`IDMateria`) REFERENCES `Materie` (`IDMateria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Utenti` FOREIGN KEY (`IDUtente`) REFERENCES `Utenti` (`IDUtente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `RiassuntiTemporanei`
--
ALTER TABLE `RiassuntiTemporanei`
  ADD CONSTRAINT `fk_riass_utente` FOREIGN KEY (`IDUtente`) REFERENCES `Utenti` (`IDUtente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `Tokens`
--
ALTER TABLE `Tokens`
  ADD CONSTRAINT `fk_Token_Utenti` FOREIGN KEY (`IDUtente`) REFERENCES `Utenti` (`IDUtente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `Valutazioni`
--
ALTER TABLE `Valutazioni`
  ADD CONSTRAINT `fk_Riassunti_Valutazioni` FOREIGN KEY (`IDRiassunto`) REFERENCES `Riassunti` (`IDRiassunto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `VersioniRiassuntiTemporanei`
--
ALTER TABLE `VersioniRiassuntiTemporanei`
  ADD CONSTRAINT `fk_Versioni_RiassuntiTemp` FOREIGN KEY (`IDRiassunto`) REFERENCES `RiassuntiTemporanei` (`IDRiassunto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

