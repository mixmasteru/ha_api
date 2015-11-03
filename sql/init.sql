-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Host: dd32814.kasserver.com
-- Erstellungszeit: 19. Okt 2015 um 21:30
-- Server Version: 5.5.43-nmm1-log
-- PHP-Version: 5.4.42-nmm1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `temperature`
--

CREATE TABLE IF NOT EXISTS `temperature` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` tinyint(3) unsigned NOT NULL,
  `value` float NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE `humidity` (
  `id` int( 10 ) unsigned NOT NULL AUTO_INCREMENT ,
  `device_id` tinyint( 3 ) unsigned NOT NULL ,
  `value` float NOT NULL ,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY ( `id` ) ,
  UNIQUE KEY `id` ( `id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_bin;