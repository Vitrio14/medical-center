-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 12, 2025 alle 22:28
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medical_center`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `accessi`
--

CREATE TABLE `accessi` (
  `id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `data_accesso` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `appuntamenti`
--

CREATE TABLE `appuntamenti` (
  `id` int(11) NOT NULL,
  `paziente_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `data_appuntamento` datetime NOT NULL,
  `note` text DEFAULT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT current_timestamp(),
  `ora_appuntamento` time NOT NULL,
  `stato` varchar(20) DEFAULT 'Non confermato'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `file`
--

CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `paziente_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `tipo_file` varchar(50) DEFAULT NULL,
  `data_caricamento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `medici`
--

CREATE TABLE `medici` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `specializzazione` varchar(255) DEFAULT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `medici`
--

INSERT INTO `medici` (`id`, `username`, `password`, `nome`, `cognome`, `email`, `specializzazione`, `data_creazione`) VALUES
(26, 'P01_arex', '$2y$10$pr45ZezhlKJR5QCpFHvgee091hEIBwfr7COVW1OeV62KRX8xaGV5a', 'Steven', 'Arex', 'stevenarex@impero.it', 'Neurologia - Cardiologia (Chirurgia)', '2025-01-12 21:27:49');

-- --------------------------------------------------------

--
-- Struttura della tabella `pazienti`
--

CREATE TABLE `pazienti` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `data_nascita` date NOT NULL,
  `indirizzo` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_allegato` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `pazienti_file`
--

CREATE TABLE `pazienti_file` (
  `id` int(11) NOT NULL,
  `paziente_id` int(11) NOT NULL,
  `nome_file` varchar(255) NOT NULL,
  `data_caricamento` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `pronto_soccorso`
--

CREATE TABLE `pronto_soccorso` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `data_nascita` date NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `codice_colore` enum('rosso','giallo','verde','bianco') NOT NULL,
  `trattamenti` text NOT NULL,
  `medico_id` int(11) DEFAULT NULL,
  `data_trattamento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ruolo` enum('medico','admin') NOT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `accessi`
--
ALTER TABLE `accessi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Indici per le tabelle `appuntamenti`
--
ALTER TABLE `appuntamenti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paziente_id` (`paziente_id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Indici per le tabelle `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paziente_id` (`paziente_id`);

--
-- Indici per le tabelle `medici`
--
ALTER TABLE `medici`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indici per le tabelle `pazienti`
--
ALTER TABLE `pazienti`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `pazienti_file`
--
ALTER TABLE `pazienti_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paziente_id` (`paziente_id`);

--
-- Indici per le tabelle `pronto_soccorso`
--
ALTER TABLE `pronto_soccorso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `accessi`
--
ALTER TABLE `accessi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `appuntamenti`
--
ALTER TABLE `appuntamenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `medici`
--
ALTER TABLE `medici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT per la tabella `pazienti`
--
ALTER TABLE `pazienti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT per la tabella `pazienti_file`
--
ALTER TABLE `pazienti_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `pronto_soccorso`
--
ALTER TABLE `pronto_soccorso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `accessi`
--
ALTER TABLE `accessi`
  ADD CONSTRAINT `accessi_ibfk_1` FOREIGN KEY (`medico_id`) REFERENCES `medici` (`id`);

--
-- Limiti per la tabella `appuntamenti`
--
ALTER TABLE `appuntamenti`
  ADD CONSTRAINT `appuntamenti_ibfk_1` FOREIGN KEY (`paziente_id`) REFERENCES `pazienti` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appuntamenti_ibfk_2` FOREIGN KEY (`medico_id`) REFERENCES `medici` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `file_ibfk_1` FOREIGN KEY (`paziente_id`) REFERENCES `pazienti` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `pazienti_file`
--
ALTER TABLE `pazienti_file`
  ADD CONSTRAINT `pazienti_file_ibfk_1` FOREIGN KEY (`paziente_id`) REFERENCES `pazienti` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `pronto_soccorso`
--
ALTER TABLE `pronto_soccorso`
  ADD CONSTRAINT `pronto_soccorso_ibfk_1` FOREIGN KEY (`medico_id`) REFERENCES `medici` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
