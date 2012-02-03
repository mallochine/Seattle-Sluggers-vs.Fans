-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 04, 2011 at 07:54 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `matchdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE IF NOT EXISTS `matches` (
  `matchid` int(255) NOT NULL AUTO_INCREMENT,
  `matchname` varchar(25) NOT NULL,
  `directorid` int(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `numBoards` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`matchid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`matchid`, `matchname`, `directorid`, `status`, `numBoards`) VALUES
(1, 'empty', 0, 1, 0),
(2, 'Seattle Sluggers vs. Fans', 1, 2, 4),
(3, 'something', 1, 400, 4);

-- --------------------------------------------------------

--
-- Table structure for table `match_boards2`
--

CREATE TABLE IF NOT EXISTS `match_boards2` (
  `boardid` int(50) NOT NULL AUTO_INCREMENT,
  `playerid` int(50) NOT NULL,
  `description` text NOT NULL,
  `status` int(4) NOT NULL DEFAULT '200',
  PRIMARY KEY (`boardid`),
  UNIQUE KEY `playerid` (`playerid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `match_boards2`
--


-- --------------------------------------------------------

--
-- Table structure for table `match_boards3`
--

CREATE TABLE IF NOT EXISTS `match_boards3` (
  `boardid` int(50) NOT NULL AUTO_INCREMENT,
  `playerid` int(50) NOT NULL,
  `color` int(1) NOT NULL,
  `description` text,
  `status` int(4) DEFAULT '200',
  PRIMARY KEY (`boardid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `match_boards3`
--

INSERT INTO `match_boards3` (`boardid`, `playerid`, `color`, `description`, `status`) VALUES
(3, 5, 1, '', NULL),
(2, 4, 0, '', NULL),
(1, 2, 1, '', NULL),
(4, 6, 0, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `match_players`
--

CREATE TABLE IF NOT EXISTS `match_players` (
  `primaryid` int(255) NOT NULL AUTO_INCREMENT,
  `matchid` int(50) NOT NULL,
  `playerid` int(50) NOT NULL,
  `status` int(5) NOT NULL DEFAULT '200',
  PRIMARY KEY (`primaryid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `match_players`
--

INSERT INTO `match_players` (`primaryid`, `matchid`, `playerid`, `status`) VALUES
(32, 3, 6, 400),
(31, 3, 5, 400),
(30, 3, 4, 400),
(29, 3, 2, 400);

-- --------------------------------------------------------

--
-- Table structure for table `match_users2`
--

CREATE TABLE IF NOT EXISTS `match_users2` (
  `boardid` int(50) NOT NULL,
  `userid` int(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `match_users2`
--


-- --------------------------------------------------------

--
-- Table structure for table `match_votes2`
--

CREATE TABLE IF NOT EXISTS `match_votes2` (
  `boardid` int(50) NOT NULL,
  `firstsq` int(3) NOT NULL,
  `lastsq` int(3) NOT NULL,
  `numvotes` int(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `match_votes2`
--


-- --------------------------------------------------------

--
-- Table structure for table `match_votes3`
--

CREATE TABLE IF NOT EXISTS `match_votes3` (
  `boardid` int(50) NOT NULL,
  `firstsq` int(3) NOT NULL,
  `lastsq` int(3) NOT NULL,
  `numvotes` int(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `match_votes3`
--


-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `playerid` int(255) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(15) NOT NULL,
  `lastname` varchar(15) NOT NULL,
  `rating` int(4) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`playerid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`playerid`, `firstname`, `lastname`, `rating`, `email`, `password`, `status`) VALUES
(2, 'Alex', 'Guo', 2175, 'alexchesskid@msn.com', '5f4dcc3b5aa765d61d8327deb882cf99', 400),
(5, 'Alex', 'Guo', 2175, 'alexguo123@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 400),
(4, 'Alex', 'Guo', 2175, 'guo.xander@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 400),
(6, 'Alex', 'Guo', 2175, 'interlakechessblogger@live.com', '5f4dcc3b5aa765d61d8327deb882cf99', 400);

-- --------------------------------------------------------

--
-- Table structure for table `tourney_directors`
--

CREATE TABLE IF NOT EXISTS `tourney_directors` (
  `directorid` int(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL,
  `email` varchar(30) NOT NULL,
  `firstname` varchar(15) NOT NULL,
  `lastname` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`directorid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tourney_directors`
--

INSERT INTO `tourney_directors` (`directorid`, `username`, `email`, `firstname`, `lastname`, `password`) VALUES
(1, 'Kasprosian', 'alexchesskid@msn.com', 'Alex', 'Guo', '5f4dcc3b5aa765d61d8327deb882cf99');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
