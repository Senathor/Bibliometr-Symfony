-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 06 Sty 2020, 16:40
-- Wersja serwera: 10.4.11-MariaDB
-- Wersja PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `bibliometr`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20200105204131', '2020-01-05 20:41:43'),
('20200105211700', '2020-01-05 21:17:05'),
('20200105212222', '2020-01-05 21:22:25'),
('20200105212825', '2020-01-05 21:28:28'),
('20200105232046', '2020-01-05 23:20:50'),
('20200106102303', '2020-01-06 10:23:06'),
('20200106134811', '2020-01-06 13:48:18'),
('20200106153851', '2020-01-06 15:38:57');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `publication`
--

CREATE TABLE `publication` (
  `id` int(11) NOT NULL,
  `title` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `authors` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `publication_date` datetime NOT NULL,
  `shares` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `points` int(11) NOT NULL,
  `magazine` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `conference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Zrzut danych tabeli `publication`
--

INSERT INTO `publication` (`id`, `title`, `authors`, `publication_date`, `shares`, `points`, `magazine`, `conference`, `url`, `timezone`) VALUES
(30, 'a12', 'maciek', '2020-01-08 00:00:00', 'maciek:100', 23, 'asd', NULL, 'asd', ''),
(31, 'a123', 'maciek', '2020-01-08 00:00:00', 'maciek:100', 2, 'asd', NULL, 'asd', ''),
(32, 'a12331', 'maciek', '2020-01-08 00:00:00', 'maciek:100', 21, 'asd', NULL, 'asd', ''),
(33, 'asd22', 'maciek', '2020-01-01 00:00:00', 'maciek:100', 12312, 'asd', NULL, 'asd', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `publications_list`
--

CREATE TABLE `publications_list` (
  `user_id` int(11) NOT NULL,
  `publication_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `publications_list`
--

INSERT INTO `publications_list` (`user_id`, `publication_id`) VALUES
(22, 30),
(22, 31),
(22, 32),
(22, 33);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(1024) NOT NULL,
  `email` varchar(512) NOT NULL,
  `afiliacja` varchar(1024) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `email`, `afiliacja`, `role`) VALUES
(20, 'asdq', '$2y$13$W5ClD9N0M8RtuGePF52zkOZdIP.HEu0Dypd4D0CBLMMW9tez7XqOW', 'a@a.a', 'asd', 'admin'),
(22, 'maciek', '$2y$13$SWb6yEOAjw6BMBGuE85oeOjmt9A3eqgsjix9P5LAfy3ztOK.UpT2G', 'a@l.pl', 'l', 'admin');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indeksy dla tabeli `publication`
--
ALTER TABLE `publication`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `publications_list`
--
ALTER TABLE `publications_list`
  ADD PRIMARY KEY (`user_id`,`publication_id`),
  ADD KEY `IDX_8C5D0BC2A76ED395` (`user_id`),
  ADD KEY `IDX_8C5D0BC238B217A7` (`publication_id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla tabel zrzutów
--

--
-- AUTO_INCREMENT dla tabeli `publication`
--
ALTER TABLE `publication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `publications_list`
--
ALTER TABLE `publications_list`
  ADD CONSTRAINT `FK_8C5D0BC238B217A7` FOREIGN KEY (`publication_id`) REFERENCES `publication` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8C5D0BC2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
