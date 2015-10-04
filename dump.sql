-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 04. Okt 2015 um 18:09
-- Server Version: 5.5.44
-- PHP-Version: 5.4.45-0+deb7u1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `nebenkosten`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `apartment`
--

CREATE TABLE IF NOT EXISTS `apartment` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `house_id` tinyint(3) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `size` decimal(5,2) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `house_id` (`house_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `costs_house`
--

CREATE TABLE IF NOT EXISTS `costs_house` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `year` year(4) NOT NULL,
  `usage` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `house_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `house_id` (`house_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `costs_person`
--

CREATE TABLE IF NOT EXISTS `costs_person` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `year` year(4) NOT NULL,
  `usage` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `house_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `house_id` (`house_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `costs_tenant`
--

CREATE TABLE IF NOT EXISTS `costs_tenant` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `year` year(4) NOT NULL,
  `usage` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tenant_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `house`
--

CREATE TABLE IF NOT EXISTS `house` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `size` decimal(6,2) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` smallint(5) unsigned NOT NULL,
  `date` date NOT NULL,
  `for_date` date NOT NULL,
  `amount_kind` tinyint(3) unsigned NOT NULL COMMENT '0 = rent, 1 = extra, 2 = deposit',
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tenant`
--

CREATE TABLE IF NOT EXISTS `tenant` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `persons` tinyint(1) unsigned NOT NULL,
  `entry` date NOT NULL,
  `extract` date DEFAULT NULL,
  `apartment_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `apartment_id` (`apartment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `apartment`
--
ALTER TABLE `apartment`
  ADD CONSTRAINT `apartment_ibfk_1` FOREIGN KEY (`house_id`) REFERENCES `house` (`id`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `costs_house`
--
ALTER TABLE `costs_house`
  ADD CONSTRAINT `costs_house_ibfk_1` FOREIGN KEY (`house_id`) REFERENCES `house` (`id`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `costs_person`
--
ALTER TABLE `costs_person`
  ADD CONSTRAINT `costs_person_ibfk_1` FOREIGN KEY (`house_id`) REFERENCES `house` (`id`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `costs_tenant`
--
ALTER TABLE `costs_tenant`
  ADD CONSTRAINT `costs_tenant_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`id`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`id`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `tenant`
--
ALTER TABLE `tenant`
  ADD CONSTRAINT `tenant_ibfk_1` FOREIGN KEY (`apartment_id`) REFERENCES `apartment` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
