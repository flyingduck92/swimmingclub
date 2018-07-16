-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2018 at 10:18 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pdocourse`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Distance'),
(8, 'hello'),
(2, 'Sprint');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_code` varchar(255) DEFAULT NULL,
  `event_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_code`, `event_name`) VALUES
(1, '02', '50m. FREESTYLE'),
(2, '02', '100m. FREESTYLE '),
(3, '03', '200m. FREESTYLE'),
(4, '04', '400m. FREESTYLE'),
(5, '05', '800m. FREESTYLE'),
(6, '06', '1500m. FREESTYLE'),
(7, '07', '50m. BREASTSTROKE'),
(8, '08', '100m. BREASTSTROKE'),
(9, '09', '200m. BREASTSTROKE'),
(10, '10', '50m. BUTTERFLY'),
(11, '11', '100m. BUTTERFLY'),
(12, '12', '200m. BUTTERFLY'),
(13, '13', '50m. BACKSTROKE'),
(14, '14', '100m. BACKSTROKE'),
(15, '15', '200m. BACKSTROKE'),
(16, '16', '200m. INDIVIDUAL MEDLEY'),
(17, '17', '400m. INDIVIDUAL MEDLEY'),
(18, '29', '100m. INDIVIDUAL MEDLEY'),
(19, '37', '150m. INDIVIDUAL MEDLEY');

-- --------------------------------------------------------

--
-- Table structure for table `gala`
--

CREATE TABLE `gala` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `heatfinal_desc` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `venue_name` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gala`
--

INSERT INTO `gala` (`id`, `category_name`, `heatfinal_desc`, `date`, `group_name`, `event_name`, `venue_name`, `note`) VALUES
(1, 'Distance', 'Heat 1', '2018-04-18', '10 Years Boys', '100m. BACKSTROKE', 'test 1', 'test note'),
(2, 'Sprint', 'Heat 2', '2018-04-19', 'Intermediate Boys', '50m. BACKSTROKE', 'Venue 5', ''),
(3, 'Sprint', 'Final', '2018-04-18', 'Junior/Senior Boys', '200m. INDIVIDUAL MEDLEY', 'Venue 5', ''),
(4, 'hello', 'Final', '2018-04-13', '10 Years Boys', '100m. BACKSTROKE', 'test 1', '87');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(15, '10 Years Boys'),
(16, '10 Years Girls'),
(17, '11 Years Boys'),
(18, '11 Years Girls'),
(19, '12 Years Boys'),
(20, '12 Years Girls'),
(21, '13 Years Boys'),
(22, '13 Years Girls'),
(23, '14 Years Boys'),
(24, '14 Years Girls'),
(11, '8 Under Boys'),
(12, '8 Under Girls'),
(13, '9 Under Boys'),
(14, '9 Under Girls'),
(9, 'Intermediate Boys'),
(10, 'Intermediate Girls'),
(4, 'Junior Boys'),
(3, 'Junior Girls'),
(6, 'Junior Mixed'),
(7, 'Junior/Senior Boys'),
(8, 'Junior/Senior Girls'),
(25, 'Open Boys'),
(26, 'Open Girls'),
(2, 'Senior Boys'),
(1, 'Senior Girl'),
(33, 'test 101');

-- --------------------------------------------------------

--
-- Table structure for table `heat_final`
--

CREATE TABLE `heat_final` (
  `id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `heat_final`
--

INSERT INTO `heat_final` (`id`, `description`) VALUES
(5, 'Final'),
(1, 'Heat 1'),
(7, 'Heat 12'),
(2, 'Heat 2'),
(3, 'Heat 3'),
(4, 'Heat 4'),
(8, 'test5');

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` char(60) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `officers`
--

INSERT INTO `officers` (`id`, `role_id`, `username`, `password`, `fname`, `lname`, `dob`, `active`) VALUES
(1, 1, 'admin123', '$2y$12$Qbi1vKnJnGtfkxjVXd02GOv1jOSgDNAu855uNPTP9ZSQqOYS0rWwy', 'admin', 'test', '2008-04-20', 1),
(8, 1, 'admin3', '$2y$12$F5Uzl7krVjnzOEBkVXzX8OFRmwhJFafawT7ymgqLD7InRC1kGWo/K', 'Admin', 'Three', '1970-08-08', 1),
(9, 1, 'admin2', '$2y$12$lQ1VbHo7Yu6e6tBb584wT.apLJW5PyhZLtZOJ0ZzD42X2/he8ZrW2', 'Admin', 'Two', '1998-06-06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `parent_name` varchar(40) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postcode` varchar(255) NOT NULL,
  `password` char(60) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `role_id`, `email`, `parent_name`, `phone`, `address`, `postcode`, `password`, `active`) VALUES
(77, 2, 'parent_one@gmail.com', 'Parent One One', '1234567890 1', 'Hill St', 'ST4 1NL', '$2y$12$WZEjlUD6VtMaEk2Em6Ed3eDebUughhpNfNK6H9A88OubaH0TxMJkm', 1),
(78, 2, 'parent_two@gmail.com', 'Parent Two', '555', 'test', 'st4', '$2y$12$Ey0Z17o4kamNaUSxBoXlu.d0KP.wrE5RLvue10oXmIzQIVK49EfnK', 1),
(79, 2, 'parent_three@gmail.com', 'parent three', '3333', 'address 3', 'st5', '$2y$12$Vyyh2eiWltMOyEYVxVhgrO4Khz6/Yae9u9pjKEyarC/ZaYoxTIpbW', 1),
(80, 2, 'parent_four@gmail.com', 'parent four', '4444', 'asdress 4', 'st4', '$2y$12$cOzAtwT/lgwu3qQVCHRIU.y5MJTg25G8SciVdBRwtvp7xLewbo0nq', 1),
(82, 2, 'parent_five@gmail.com', 'parent five', '5555', 'adress 5', 'st5', '$2y$12$vg6Iy1ux3ahBSnanpFMpz.WW.jI1BNSY8rbNtQltNDRAYyzQetOau', 1),
(83, 2, 'parent_six@gmail.com', 'parent six', '666', 'address 6', 'st6', '$2y$12$dVWGp/O0ONp4XcrHzxv3Serme2XgHBmR2bT77NXpJdRA5W7Ymyo8q', 1),
(84, 2, 'parent_seven@gmail.com', 'parent seven', '7777', 'address 77', 'st7', '$2y$12$LWkOFIvsSg3aEa7Lt0t9cO1815RhZzOsXcd6TFWEMUYosd9Mzb3lC', 1),
(85, 2, 'parent_eight@gmail.com', 'parent eight', '88', 'address 88', 'st8', '$2y$12$Pyoz63kXUbIJbi8GOdG6lOku7F9SQKiTPgWra.u.ub6Ue2GAuD7lm', 1),
(86, 2, 'parent_nine@gmail.com', 'Parent Nine', '999', 'address 99', 'st9', '$2y$12$sjhil2tunXJPmNt5yYj/J.UJZLWDAyyfFmXb1cLkIfP3Kx799I.62', 1),
(87, 2, 'parent_ten@gmail.com', 'Parent Ten', '1010', 'address 10', 'st10', '$2y$12$uVPIgP67PfrXm7wRLqLQNe/0lyqLOgnDZ5pv7W8Sbg5xFG4HPBzZe', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'officers'),
(2, 'parents'),
(3, 'swimmers');

-- --------------------------------------------------------

--
-- Table structure for table `swimmers`
--

CREATE TABLE `swimmers` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` char(60) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(225) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `swimmers`
--

INSERT INTO `swimmers` (`id`, `role_id`, `username`, `password`, `fname`, `lname`, `dob`, `email`, `active`) VALUES
(15, 3, 'user123', '$2y$12$9AL8EH.XfcIVK8v/94xwdOSC6QQ3JQ8oyEWpB22HDV9B.WV.GjDeS', 'User One', 'Test', '1992-05-08', 'parent_one@gmail.com', 1),
(16, 3, 'user2', '$2y$12$Ey0Z17o4kamNaUSxBoXlu.d0KP.wrE5RLvue10oXmIzQIVK49EfnK', 'user', 'two', '1992-02-02', 'parent_two@gmail.com', 1),
(17, 3, 'user3', '$2y$12$Vyyh2eiWltMOyEYVxVhgrO4Khz6/Yae9u9pjKEyarC/ZaYoxTIpbW', 'user', 'three', '1994-05-05', 'parent_three@gmail.com', 1),
(18, 3, 'user4', '$2y$12$cOzAtwT/lgwu3qQVCHRIU.y5MJTg25G8SciVdBRwtvp7xLewbo0nq', 'user', 'four', '1996-05-05', 'parent_four@gmail.com', 1),
(20, 3, 'user5', '$2y$12$fqYFEQge/5k4cy9tF65DmurQeHN5t7em3Esk5SXc0361Ziglg4/lC', 'user', 'five', '1995-05-05', 'parent_five@gmail.com', 1),
(21, 3, 'user6', '$2y$12$dVWGp/O0ONp4XcrHzxv3Serme2XgHBmR2bT77NXpJdRA5W7Ymyo8q', 'user', 'six', '1996-06-06', 'parent_six@gmail.com', 1),
(22, 3, 'user7', '$2y$12$LWkOFIvsSg3aEa7Lt0t9cO1815RhZzOsXcd6TFWEMUYosd9Mzb3lC', 'user', 'seven', '1994-07-07', 'parent_seven@gmail.com', 1),
(24, 3, 'user9', '$2y$12$sjhil2tunXJPmNt5yYj/J.UJZLWDAyyfFmXb1cLkIfP3Kx799I.62', 'user', 'nine', '1999-09-09', 'parent_nine@gmail.com', 1),
(25, 3, 'user10', '$2y$12$uVPIgP67PfrXm7wRLqLQNe/0lyqLOgnDZ5pv7W8Sbg5xFG4HPBzZe', 'user', 'ten', '2000-10-10', 'parent_ten@gmail.com', 1),
(30, 3, 'test123', '$2y$12$Iv3oD7j/1x9kvjoeFnlejOA/ATNfZ8.ENGSBXJIFYT1PNVYjseW9G', 'Test', 'OneTwoThree', '1988-09-09', 'parent_eight@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `timerecords`
--

CREATE TABLE `timerecords` (
  `id` int(11) NOT NULL,
  `gala_id` int(11) NOT NULL,
  `line_number` int(11) NOT NULL,
  `swimmer_name` varchar(255) NOT NULL,
  `recordtime` varchar(255) NOT NULL,
  `finish_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timerecords`
--

INSERT INTO `timerecords` (`id`, `gala_id`, `line_number`, `swimmer_name`, `recordtime`, `finish_number`) VALUES
(1, 1, 5, 'TEST, USER ONE', '15:22:02', 2),
(3, 1, 2, 'FIVE, USER', '15:22:02', 3),
(4, 1, 3, 'FOUR, USER', '15:22:02', 4),
(5, 1, 4, 'THREE, USER', '15:22:02', 5),
(6, 2, 2, 'EIGHT, USER', 'DNS', 3),
(7, 2, 3, 'FOUR, USER', 'DNS', 2),
(9, 2, 4, 'TEST, USER ONE', 'DNS', 4),
(10, 2, 1, 'NINE, USER', 'HHH', 1),
(15, 1, 1, 'TWO, USER', '15.05.940', 1),
(16, 2, 5, 'EIGHT, USER', 'DNS', 5);

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`id`, `name`) VALUES
(7, 'test 1'),
(1, 'Venue 1'),
(2, 'Venue 2'),
(3, 'Venue 3'),
(4, 'Venue 4'),
(5, 'Venue 5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_name` (`event_name`);

--
-- Indexes for table `gala`
--
ALTER TABLE `gala`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_name` (`category_name`),
  ADD KEY `heat_final_desc` (`heatfinal_desc`),
  ADD KEY `group_name` (`group_name`),
  ADD KEY `event_name` (`event_name`),
  ADD KEY `venue_name` (`venue_name`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `heat_final`
--
ALTER TABLE `heat_final`
  ADD PRIMARY KEY (`id`),
  ADD KEY `desc` (`description`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `swimmers`
--
ALTER TABLE `swimmers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `lname` (`lname`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `timerecords`
--
ALTER TABLE `timerecords`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gala_id` (`gala_id`),
  ADD KEY `swimmer_name` (`swimmer_name`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `gala`
--
ALTER TABLE `gala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `heat_final`
--
ALTER TABLE `heat_final`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `swimmers`
--
ALTER TABLE `swimmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `timerecords`
--
ALTER TABLE `timerecords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gala`
--
ALTER TABLE `gala`
  ADD CONSTRAINT `gala_ibfk_1` FOREIGN KEY (`event_name`) REFERENCES `events` (`event_name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gala_ibfk_10` FOREIGN KEY (`venue_name`) REFERENCES `venue` (`name`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `gala_ibfk_2` FOREIGN KEY (`category_name`) REFERENCES `categories` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gala_ibfk_3` FOREIGN KEY (`group_name`) REFERENCES `groups` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gala_ibfk_4` FOREIGN KEY (`heatfinal_desc`) REFERENCES `heat_final` (`description`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `officers`
--
ALTER TABLE `officers`
  ADD CONSTRAINT `officers_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parents_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `swimmers`
--
ALTER TABLE `swimmers`
  ADD CONSTRAINT `swimmers_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `swimmers_ibfk_3` FOREIGN KEY (`email`) REFERENCES `parents` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `timerecords`
--
ALTER TABLE `timerecords`
  ADD CONSTRAINT `timerecords_ibfk_1` FOREIGN KEY (`gala_id`) REFERENCES `gala` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
