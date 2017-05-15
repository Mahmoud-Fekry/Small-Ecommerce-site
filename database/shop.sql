-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2017 at 08:59 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `Cat_ID` int(11) NOT NULL,
  `Cat_Name` varchar(255) NOT NULL,
  `Cat_Description` text NOT NULL,
  `Cat_Parent` int(11) NOT NULL,
  `Cat_Ordering` int(11) NOT NULL,
  `Cat_Visibility` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`Cat_ID`, `Cat_Name`, `Cat_Description`, `Cat_Parent`, `Cat_Ordering`, `Cat_Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(6, 'Hand Made', 'Hand made items', 0, 1, 1, 1, 1),
(7, 'Computers', 'Computer items', 0, 2, 1, 1, 1),
(8, 'Phones', 'phones', 0, 3, 1, 1, 1),
(9, 'Clothing', 'Clothing and fashion', 0, 4, 1, 1, 1),
(10, 'Tools', 'Home tools', 0, 5, 1, 1, 1),
(11, 'Cell Phone', 'different type of cell phone', 8, 6, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `Comment_ID` int(11) NOT NULL,
  `Comment_Value` text NOT NULL,
  `Comment_Status` tinyint(4) NOT NULL DEFAULT '0',
  `Comment_Date` date NOT NULL,
  `Item_Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`Comment_ID`, `Comment_Value`, `Comment_Status`, `Comment_Date`, `Item_Id`, `User_Id`) VALUES
(1, 'hello from s1', 0, '2017-04-04', 2, 2),
(2, 'hello agin from member number 1', 0, '2017-04-04', 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Item_Name` varchar(255) NOT NULL,
  `Item_Description` text NOT NULL,
  `Item_Price` smallint(7) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Item_Image` varchar(255) NOT NULL,
  `Item_Status` varchar(255) NOT NULL,
  `Item_Rating` smallint(6) NOT NULL,
  `Item_Approve` tinyint(4) NOT NULL DEFAULT '0',
  `Cat_Id` int(11) NOT NULL,
  `Member_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Item_Name`, `Item_Description`, `Item_Price`, `Add_Date`, `Country_Made`, `Item_Image`, `Item_Status`, `Item_Rating`, `Item_Approve`, `Cat_Id`, `Member_Id`) VALUES
(1, 'Iphone 6S', 'no description', 400, '2017-03-25', 'India', '', '1', 0, 1, 8, 2),
(2, 'Yeti Blue Mic', 'very good mic', 30, '2017-03-25', 'Egypt', '', '1', 0, 1, 7, 5),
(3, 'Magic Mouse', 'Apple magic mouse', 60, '2017-03-26', 'USA', '', '1', 0, 1, 7, 2),
(4, 'Network Cable', 'Cat 9 Network cable', 15, '2017-03-26', 'USA', '', '2', 0, 1, 7, 3),
(5, 'Tiger Speaker', 'Very good speaker', 35, '2017-03-26', 'India', '', '1', 0, 1, 7, 4),
(7, 'Galaxy Note 7', 'High performance with good camera', 550, '2017-04-26', 'Jaban', '', '1', 0, 1, 8, 2),
(8, 'test item', 'no description', 65, '2017-05-01', 'China', '', '1', 0, 1, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `User_Name` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Full_Name` varchar(255) NOT NULL,
  `Group_ID` int(11) NOT NULL,
  `Trust_Status` int(11) NOT NULL DEFAULT '0',
  `Reg_Status` int(11) NOT NULL DEFAULT '0',
  `User_Add_Date` date NOT NULL,
  `User_Img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `User_Name`, `Password`, `Email`, `Full_Name`, `Group_ID`, `Trust_Status`, `Reg_Status`, `User_Add_Date`, `User_Img`) VALUES
(1, 'mm', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'hyper_20155332@hotmail.com', 'Mahmoud Fekry', 1, 1, 1, '2017-03-19', ''),
(2, 'm1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'm1@a.s', 's1', 0, 0, 1, '2017-03-19', ''),
(3, 'm2', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'm2@a.s', 's2', 0, 0, 1, '2017-03-19', ''),
(4, 'm3', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'm3@a.s', 's3', 0, 0, 1, '2017-03-19', ''),
(5, 'm4', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'm4@a.s', 's4', 0, 0, 1, '2017-03-19', ''),
(6, 'member5', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'm5@a.s', 'member number 5', 0, 0, 1, '2017-04-13', ''),
(7, 'member6', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'm6@a.s', 'member number 6', 0, 0, 1, '2017-04-13', ''),
(8, 'member7', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'm7@a.s', 'member number 7', 0, 0, 1, '2017-04-13', ''),
(9, 'member8', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'm8@a.s', 'member number 8', 0, 0, 1, '2017-04-13', ''),
(11, 'member9', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'm9@a.s', 'member number 9', 0, 0, 1, '2017-04-13', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`Cat_ID`),
  ADD UNIQUE KEY `Cat_Name` (`Cat_Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`Comment_ID`),
  ADD KEY `item_comment` (`Item_Id`),
  ADD KEY `user_comment` (`User_Id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `Cat_Id` (`Cat_Id`),
  ADD KEY `Member_Id` (`Member_Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `User_Name` (`User_Name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `Cat_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `Comment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `item_comment` FOREIGN KEY (`Item_Id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_comment` FOREIGN KEY (`User_Id`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`Member_Id`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`Cat_Id`) REFERENCES `categories` (`Cat_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
