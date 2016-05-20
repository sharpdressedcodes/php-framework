-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Sep 03, 2015 at 06:33 PM
-- Server version: 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `test`
--

-- --------------------------------------------------------

drop table if exists `user`;

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(10) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `role` varchar(256) NOT NULL,
  `department` varchar(256) NOT NULL,
  `dob` date NOT NULL,
  `street_address_1` varchar(256) NOT NULL,
  `street_address_2` varchar(256) NOT NULL,
  `suburb` varchar(64) NOT NULL,
  `state` varchar(64) NOT NULL,
  `postcode` int(5) NOT NULL,
  `country` varchar(64) NOT NULL DEFAULT 'Australia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `last_name`, `first_name`, `email`, `role`, `department`, `dob`, `street_address_1`, `street_address_2`, `suburb`, `state`, `postcode`, `country`) VALUES
(0, 'Nixon', 'Tiger', 'tnixon@example.com', 'System Architect', 'IT', '1955-09-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(1, 'Winters', 'Garrett', 'gwinters@example.com', 'Accountant', 'Finance', '1976-06-03', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(2, 'Cox', 'Ashton', 'acox@example.com', 'Junior Technical Author', 'IT', '1993-03-03', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(3, 'Kelly', 'Cedric', 'ckelly@example.com', 'Senior Javascript Developer', 'IT', '1975-09-07', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(4, 'Satou', 'Airi', 'asatou@example.com', 'Accountant', 'Finance', '1985-01-12', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(5, 'Williamson', 'Brielle', 'bwilliamson@example.com', 'Integration Specialist', 'IT', '1973-08-27', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(6, 'Chandler', 'Herrod', 'hchandler@example.com', 'Sales Assistant', 'Sales', '1994-08-03', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(7, 'Davidson', 'Rhona', 'rdavidson@example.com', 'Integration Specialist', 'IT', '1963-08-19', '1 Maple St', '', 'Bathurst', 'NSW', 2000, 'Australia'),
(8, 'Hurst', 'Colleen', 'churst@example.com', 'Javascript Developer', 'IT', '1990-09-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(9, 'Frost', 'Sonya', 'sfrost@example.com', 'Software Engineer', 'IT', '1977-09-15', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(10, 'Gaines', 'Jena', 'jgaines@example.com', 'Office Manager', 'Operations', '1955-09-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(11, 'Flynn', 'Quinn', 'qflynn@example.com', 'Support Lead', 'Client Service', '1980-09-08', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(12, 'Marshall', 'Charde', 'cmarshall@example.com', 'Regional Director', 'Operations', '1972-08-18', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(13, 'Kennedy', 'Haley', 'hkennedy@example.com', 'Senior Marketing Designer', 'Marketing', '1995-05-12', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(14, 'Fitzpatrick', 'Tatyana', 'tfitzpatrick@example.com', 'Regional Director', 'Sales', '1975-09-07', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(15, 'Silva', 'Michael', 'msilva@example.com', 'Marketing Designer', 'Marketing', '1991-10-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(16, 'Byrd', 'Paul', 'pbyrd@example.com', 'Chief Financial Officer (CFO)', 'Finance', '1960-08-08', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(17, 'Little', 'Gloria', 'glittle@example.com', 'Systems Administrator', 'IT', '1989-04-07', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(18, 'Greer', 'Bradley', 'bgreer@example.com', 'Software Engineer', 'IT', '1990-09-21', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(19, 'Rios', 'Dai', 'drios@example.com', 'Personnel Lead', 'Head Office', '1971-01-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(20, 'Caldwell', 'Jenette', 'jcaldwell@example.com', 'Development Lead', 'IT', '1987-09-14', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(21, 'Berry', 'Yuri', 'yberry@example.com', 'Chief Marketing Officer (CMO)', 'Marketing', '1969-08-11', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(22, 'Vance', 'Caesar', 'cvance@example.com', 'Pre-Sales Support', 'Sales', '1979-09-29', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(23, 'Wilder', 'Doris', 'dwilder@example.com', 'Sales Assistant', 'Sales', '1995-09-06', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(24, 'Ramos', 'Angelica', 'aramos@example.com', 'Chief Executive Officer (CEO)', 'Head Office', '1960-03-10', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(25, 'Joyce', 'Gavin', 'gjoyce@example.com', 'Developer', 'IT', '1992-09-10', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(26, 'Chandler', 'Herrod', 'hchandler@example.com', 'Sales Assistant', 'Sales', '1994-08-03', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(27, 'Davidson', 'Rhona', 'rdavidson@example.com', 'Integration Specialist', 'IT', '1963-08-19', '1 Maple St', '', 'Bathurst', 'NSW', 2000, 'Australia'),
(28, 'Hurst', 'Colleen', 'churst@example.com', 'Javascript Developer', 'IT', '1990-09-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(29, 'Frost', 'Sonya', 'sfrost@example.com', 'Software Engineer', 'IT', '1977-09-15', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(30, 'Gaines', 'Jena', 'jgaines@example.com', 'Office Manager', 'Operations', '1955-09-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(31, 'Flynn', 'Quinn', 'qflynn@example.com', 'Support Lead', 'Client Service', '1980-09-08', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(32, 'Marshall', 'Charde', 'cmarshall@example.com', 'Regional Director', 'Operations', '1972-08-18', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(33, 'Kennedy', 'Haley', 'hkennedy@example.com', 'Senior Marketing Designer', 'Marketing', '1995-05-12', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(34, 'Fitzpatrick', 'Tatyana', 'tfitzpatrick@example.com', 'Regional Director', 'Sales', '1975-09-07', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(35, 'Silva', 'Michael', 'msilva@example.com', 'Marketing Designer', 'Marketing', '1991-10-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(36, 'Byrd', 'Paul', 'pbyrd@example.com', 'Chief Financial Officer (CFO)', 'Finance', '1960-08-08', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(37, 'Little', 'Gloria', 'glittle@example.com', 'Systems Administrator', 'IT', '1989-04-07', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(38, 'Greer', 'Bradley', 'bgreer@example.com', 'Software Engineer', 'IT', '1990-09-21', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(39, 'Rios', 'Dai', 'drios@example.com', 'Personnel Lead', 'Head Office', '1971-01-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(40, 'Caldwell', 'Jenette', 'jcaldwell@example.com', 'Development Lead', 'IT', '1987-09-14', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(41, 'Berry', 'Yuri', 'yberry@example.com', 'Chief Marketing Officer (CMO)', 'Marketing', '1969-08-11', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(42, 'Vance', 'Caesar', 'cvance@example.com', 'Pre-Sales Support', 'Sales', '1979-09-29', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(43, 'Wilder', 'Doris', 'dwilder@example.com', 'Sales Assistant', 'Sales', '1995-09-06', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(44, 'Ramos', 'Angelica', 'aramos@example.com', 'Chief Executive Officer (CEO)', 'Head Office', '1960-03-10', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(45, 'Joyce', 'Gavin', 'gjoyce@example.com', 'Developer', 'IT', '1992-09-10', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(46, 'Chandler', 'Herrod', 'hchandler@example.com', 'Sales Assistant', 'Sales', '1994-08-03', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(47, 'Davidson', 'Rhona', 'rdavidson@example.com', 'Integration Specialist', 'IT', '1963-08-19', '1 Maple St', '', 'Bathurst', 'NSW', 2000, 'Australia'),
(48, 'Hurst', 'Colleen', 'churst@example.com', 'Javascript Developer', 'IT', '1990-09-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(49, 'Frost', 'Sonya', 'sfrost@example.com', 'Software Engineer', 'IT', '1977-09-15', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(50, 'Gaines', 'Jena', 'jgaines@example.com', 'Office Manager', 'Operations', '1955-09-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(51, 'Flynn', 'Quinn', 'qflynn@example.com', 'Support Lead', 'Client Service', '1980-09-08', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(52, 'Marshall', 'Charde', 'cmarshall@example.com', 'Regional Director', 'Operations', '1972-08-18', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(53, 'Kennedy', 'Haley', 'hkennedy@example.com', 'Senior Marketing Designer', 'Marketing', '1995-05-12', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(54, 'Fitzpatrick', 'Tatyana', 'tfitzpatrick@example.com', 'Regional Director', 'Sales', '1975-09-07', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(55, 'Silva', 'Michael', 'msilva@example.com', 'Marketing Designer', 'Marketing', '1991-10-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(56, 'Byrd', 'Paul', 'pbyrd@example.com', 'Chief Financial Officer (CFO)', 'Finance', '1960-08-08', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(57, 'Little', 'Gloria', 'glittle@example.com', 'Systems Administrator', 'IT', '1989-04-07', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(58, 'Greer', 'Bradley', 'bgreer@example.com', 'Software Engineer', 'IT', '1990-09-21', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(59, 'Rios', 'Dai', 'drios@example.com', 'Personnel Lead', 'Head Office', '1971-01-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(60, 'Caldwell', 'Jenette', 'jcaldwell@example.com', 'Development Lead', 'IT', '1987-09-14', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(61, 'Berry', 'Yuri', 'yberry@example.com', 'Chief Marketing Officer (CMO)', 'Marketing', '1969-08-11', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(62, 'Vance', 'Caesar', 'cvance@example.com', 'Pre-Sales Support', 'Sales', '1979-09-29', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(63, 'Wilder', 'Doris', 'dwilder@example.com', 'Sales Assistant', 'Sales', '1995-09-06', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(64, 'Ramos', 'Angelica', 'aramos@example.com', 'Chief Executive Officer (CEO)', 'Head Office', '1960-03-10', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(65, 'Joyce', 'Gavin', 'gjoyce@example.com', 'Developer', 'IT', '1992-09-10', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(66, 'Chandler', 'Herrod', 'hchandler@example.com', 'Sales Assistant', 'Sales', '1994-08-03', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(67, 'Davidson', 'Rhona', 'rdavidson@example.com', 'Integration Specialist', 'IT', '1963-08-19', '1 Maple St', '', 'Bathurst', 'NSW', 2000, 'Australia'),
(68, 'Hurst', 'Colleen', 'churst@example.com', 'Javascript Developer', 'IT', '1990-09-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(69, 'Frost', 'Sonya', 'sfrost@example.com', 'Software Engineer', 'IT', '1977-09-15', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(70, 'Gaines', 'Jena', 'jgaines@example.com', 'Office Manager', 'Operations', '1955-09-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(71, 'Flynn', 'Quinn', 'qflynn@example.com', 'Support Lead', 'Client Service', '1980-09-08', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(72, 'Marshall', 'Charde', 'cmarshall@example.com', 'Regional Director', 'Operations', '1972-08-18', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(73, 'Kennedy', 'Haley', 'hkennedy@example.com', 'Senior Marketing Designer', 'Marketing', '1995-05-12', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(74, 'Fitzpatrick', 'Tatyana', 'tfitzpatrick@example.com', 'Regional Director', 'Sales', '1975-09-07', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(75, 'Silva', 'Michael', 'msilva@example.com', 'Marketing Designer', 'Marketing', '1991-10-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(76, 'Byrd', 'Paul', 'pbyrd@example.com', 'Chief Financial Officer (CFO)', 'Finance', '1960-08-08', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(77, 'Little', 'Gloria', 'glittle@example.com', 'Systems Administrator', 'IT', '1989-04-07', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(78, 'Greer', 'Bradley', 'bgreer@example.com', 'Software Engineer', 'IT', '1990-09-21', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia'),
(79, 'Rios', 'Dai', 'drios@example.com', 'Personnel Lead', 'Head Office', '1971-01-01', '1 Example St', '', 'Sydney', 'NSW', 2000, 'Australia');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);