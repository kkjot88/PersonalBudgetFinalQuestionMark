-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 20 Gru 2021, 15:28
-- Wersja serwera: 10.4.22-MariaDB
-- Wersja PHP: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `personal_budget`
--
CREATE DATABASE IF NOT EXISTS `personal_budget` DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci;
USE `personal_budget`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expensecategories`
--

CREATE TABLE `expensecategories` (
  `categoryid` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expensecategories_default`
--

CREATE TABLE `expensecategories_default` (
  `categoryid` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expensecategories_default`
--

INSERT INTO `expensecategories_default` (`categoryid`, `category`) VALUES
(1, 'Jedzenie'),
(2, 'Mieszkanie'),
(3, 'Inne wydatki');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expenses`
--

CREATE TABLE `expenses` (
  `expenseid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `amount` decimal(65,2) NOT NULL,
  `date` date NOT NULL,
  `methodid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `comment` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomecategories`
--

CREATE TABLE `incomecategories` (
  `categoryid` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomecategories_default`
--

CREATE TABLE `incomecategories_default` (
  `categoryid` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `incomecategories_default`
--

INSERT INTO `incomecategories_default` (`categoryid`, `category`) VALUES
(1, 'Wynagrodzenie'),
(2, 'Odsetki bankowe'),
(3, 'Sprzedaż na allegro'),
(4, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomes`
--

CREATE TABLE `incomes` (
  `incomeid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `amount` decimal(65,2) NOT NULL,
  `date` date NOT NULL,
  `categoryid` int(11) NOT NULL,
  `comment` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `paymentmethods`
--

CREATE TABLE `paymentmethods` (
  `methodid` int(11) NOT NULL,
  `method` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `paymentmethods_default`
--

CREATE TABLE `paymentmethods_default` (
  `methodid` int(11) NOT NULL,
  `method` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `paymentmethods_default`
--

INSERT INTO `paymentmethods_default` (`methodid`, `method`) VALUES
(1, 'Karta kredytowa'),
(2, 'Gotówka'),
(3, 'Karta debetowa'),
(4, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `remembered_logins`
--

CREATE TABLE `remembered_logins` (
  `tokenhash` varchar(64) NOT NULL,
  `userid` int(11) NOT NULL,
  `expiresat` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `name` text COLLATE utf8_polish_ci NOT NULL,
  `password` text COLLATE utf8_polish_ci NOT NULL,
  `email` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_expensecategories`
--

CREATE TABLE `users_expensecategories` (
  `userid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_incomecategories`
--

CREATE TABLE `users_incomecategories` (
  `userid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_paymentmethods`
--

CREATE TABLE `users_paymentmethods` (
  `userid` int(11) NOT NULL,
  `methodid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `expensecategories`
--
ALTER TABLE `expensecategories`
  ADD PRIMARY KEY (`categoryid`),
  ADD UNIQUE KEY `category` (`category`) USING HASH;

--
-- Indeksy dla tabeli `expensecategories_default`
--
ALTER TABLE `expensecategories_default`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indeksy dla tabeli `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expenseid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `categoryid` (`categoryid`),
  ADD KEY `methodid` (`methodid`);

--
-- Indeksy dla tabeli `incomecategories`
--
ALTER TABLE `incomecategories`
  ADD PRIMARY KEY (`categoryid`),
  ADD UNIQUE KEY `category` (`category`) USING HASH;

--
-- Indeksy dla tabeli `incomecategories_default`
--
ALTER TABLE `incomecategories_default`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indeksy dla tabeli `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`incomeid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indeksy dla tabeli `paymentmethods`
--
ALTER TABLE `paymentmethods`
  ADD PRIMARY KEY (`methodid`),
  ADD UNIQUE KEY `method` (`method`) USING HASH;

--
-- Indeksy dla tabeli `paymentmethods_default`
--
ALTER TABLE `paymentmethods_default`
  ADD PRIMARY KEY (`methodid`);

--
-- Indeksy dla tabeli `remembered_logins`
--
ALTER TABLE `remembered_logins`
  ADD PRIMARY KEY (`tokenhash`),
  ADD KEY `user_id` (`userid`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- Indeksy dla tabeli `users_expensecategories`
--
ALTER TABLE `users_expensecategories`
  ADD UNIQUE KEY `userid_2` (`userid`,`categoryid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indeksy dla tabeli `users_incomecategories`
--
ALTER TABLE `users_incomecategories`
  ADD UNIQUE KEY `userid_2` (`userid`,`categoryid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indeksy dla tabeli `users_paymentmethods`
--
ALTER TABLE `users_paymentmethods`
  ADD UNIQUE KEY `userid_2` (`userid`,`methodid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `methodid` (`methodid`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `expensecategories`
--
ALTER TABLE `expensecategories`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `expensecategories_default`
--
ALTER TABLE `expensecategories_default`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expenseid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `incomecategories`
--
ALTER TABLE `incomecategories`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `incomecategories_default`
--
ALTER TABLE `incomecategories_default`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `incomes`
--
ALTER TABLE `incomes`
  MODIFY `incomeid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `paymentmethods`
--
ALTER TABLE `paymentmethods`
  MODIFY `methodid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `paymentmethods_default`
--
ALTER TABLE `paymentmethods_default`
  MODIFY `methodid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `expensecategories` (`categoryid`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_3` FOREIGN KEY (`methodid`) REFERENCES `paymentmethods` (`methodid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `incomes_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `incomecategories` (`categoryid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `users_expensecategories`
--
ALTER TABLE `users_expensecategories`
  ADD CONSTRAINT `users_expensecategories_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_expensecategories_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `expensecategories` (`categoryid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `users_incomecategories`
--
ALTER TABLE `users_incomecategories`
  ADD CONSTRAINT `users_incomecategories_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_incomecategories_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `incomecategories` (`categoryid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `users_paymentmethods`
--
ALTER TABLE `users_paymentmethods`
  ADD CONSTRAINT `users_paymentmethods_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_paymentmethods_ibfk_2` FOREIGN KEY (`methodid`) REFERENCES `paymentmethods` (`methodid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
