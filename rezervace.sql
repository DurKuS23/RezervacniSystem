-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Čtv 22. úno 2024, 20:22
-- Verze serveru: 10.4.28-MariaDB
-- Verze PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `rezervace`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Vypisuji data pro tabulku `admins`
--

INSERT INTO `admins` (`admin_id`, `email`, `password_hash`) VALUES
(1, 'novacekd.05@spst.eu', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918'),
(2, 'Danny2301@seznam.cz', 'e45e1fd78068586716bba00def34f4d685b9e9ff0b178087a4fd306ce1daffc5');

-- --------------------------------------------------------

--
-- Struktura tabulky `operator`
--

CREATE TABLE `operator` (
  `id` int(11) NOT NULL,
  `jmeno` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Vypisuji data pro tabulku `operator`
--

INSERT INTO `operator` (`id`, `jmeno`) VALUES
(1, 'Je mi to jedno'),
(2, 'Franta'),
(3, 'Pavla'),
(4, 'Marie'),
(5, 'Mario');

-- --------------------------------------------------------

--
-- Struktura tabulky `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `uzivatel_id` int(11) NOT NULL,
  `operator_id` int(11) DEFAULT NULL,
  `datum_sluzby` date DEFAULT NULL,
  `cas_sluzby` time DEFAULT NULL,
  `sluzba_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Vypisuji data pro tabulku `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `uzivatel_id`, `operator_id`, `datum_sluzby`, `cas_sluzby`, `sluzba_id`) VALUES
(1, 0, 1, '2024-01-13', '07:00:00', 3),
(9, 0, 2, '0000-00-00', '07:45:00', 2),
(10, 0, 5, '2024-02-19', '09:00:00', 1),
(11, 0, 2, '2024-02-13', '07:15:00', 1),
(12, 0, 1, '2024-02-12', '07:15:00', 2),
(13, 0, 2, '2024-02-13', '07:00:00', 1),
(14, 0, 2, '2024-02-19', '07:15:00', 1),
(15, 0, 2, '2024-03-06', '07:15:00', 2),
(16, 0, 2, '2024-02-12', '07:15:00', 2),
(17, 0, 3, '2024-02-12', '07:30:00', 1),
(18, 0, 3, '2024-02-13', '07:15:00', 1),
(19, 0, 1, '2024-02-08', '07:00:00', 1),
(20, 0, 2, '2024-02-13', '07:00:00', 1),
(21, 0, 2, '2024-02-29', '07:15:00', 1),
(22, 0, 2, '2024-02-13', '07:30:00', 1),
(23, 0, 3, '2024-02-20', '09:15:00', 6),
(24, 0, 2, '2024-02-13', '07:30:00', 1),
(25, 0, 3, '2024-02-19', '07:15:00', 1),
(26, 0, 2, '2024-02-20', '07:15:00', 1),
(27, 0, 1, '2024-02-20', '07:15:00', 6),
(28, 0, 2, '2024-02-27', '07:30:00', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `sluzba`
--

CREATE TABLE `sluzba` (
  `id` int(11) NOT NULL,
  `typ_sluzby` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Vypisuji data pro tabulku `sluzba`
--

INSERT INTO `sluzba` (`id`, `typ_sluzby`) VALUES
(1, 'Klasický střih'),
(2, 'Střih strojkem'),
(3, 'Klasický střih a úprava vousů hot towel'),
(4, 'Úprava vousů pouze strojkem'),
(5, 'Střih strojkem a úprava vousů'),
(6, 'Barvení vousů');

-- --------------------------------------------------------

--
-- Struktura tabulky `uzivatele`
--

CREATE TABLE `uzivatele` (
  `id` int(10) NOT NULL,
  `Jmeno` varchar(30) NOT NULL,
  `Prijmeni` varchar(30) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Heslo` varchar(65) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Vypisuji data pro tabulku `uzivatele`
--

INSERT INTO `uzivatele` (`id`, `Jmeno`, `Prijmeni`, `Email`, `Heslo`) VALUES
(24, 'Daniel', 'Nováček', 'novacekd.05@spst.eu', '1b53452e8795a67f57cbe0ab01c63a53740e505edc583ca0d6964a363bd644df'),
(25, 'Daniel', 'Nováček', 'Danny2301@seznam.cz', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918'),
(26, 'Daniel', 'Nováček', 'Danny230147@seznam.cz', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
(28, 'Daniel', 'Nováček', 'novacekdaniel3@gmail.com', 'de04d58dc5ccc4b9671c3627fb8d626fe4a15810bc1fe3e724feea761965fb71'),
(31, 'Daniel', 'Nováček', 'Tester@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexy pro tabulku `operator`
--
ALTER TABLE `operator`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `operator_id` (`operator_id`),
  ADD KEY `služba_id` (`sluzba_id`);

--
-- Indexy pro tabulku `sluzba`
--
ALTER TABLE `sluzba`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pro tabulku `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`operator_id`) REFERENCES `operator` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`sluzba_id`) REFERENCES `sluzba` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
