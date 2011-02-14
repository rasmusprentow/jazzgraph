-- phpMyAdmin SQL Dump
-- version 2.9.1.1-Debian-10
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Nov 19, 2010 at 09:09 PM
-- Server version: 5.0.32
-- PHP Version: 5.2.0-8+etch13
-- 
-- Database: `phponline`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `activity_log`
-- 

CREATE TABLE `activity_log` (
  `id` int(8) NOT NULL auto_increment,
  `time` int(10) NOT NULL,
  `url` varchar(255) collate utf8_unicode_ci NOT NULL,
  `ip` varchar(16) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `activity_log`
-- 

