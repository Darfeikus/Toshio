-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 06, 2020 at 01:32 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `calificador_registration`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `email`, `id`) VALUES
('A01172971', '75697fe1a9d63beea66154d3effec2f3', 'A01172971@itesm.mx', ''),
('A01326939', '0ee60383625a72449a435c02a5447f03', 'A01326939@email.com', ''),
('A01328782', 'a94b5a3b6fcbeec1207fb8aee0184d13', 'A01328782@itesm.mx', ''),
('A01328800', '0ee5ab66cb115ea4b31f8dff32a07724', 'A01328800@itesm.mx', ''),
('A01329173', '9ca7e03f89f8093245eec53f4adab66f', 'racso_boston@hotmail.com', '1'),
('A01329747', '7f8551de0ae07477b40858f9454b1796', 'A01329747@itesm.mx', ''),
('A01551723', '20c57a1de10a4701a92811d4f7ff3006', 'A01551723@itesm.mx', ''),
('A01730640', '3145cc588f61a966f5a07ff623c96962', 'A01730640@itesm.mx', ''),
('A01730779', 'b6c322adf2715441661b9a698f1b44c2', 'A01730779@itesm.mx', ''),
('A01730979', '59cbe7258c94bcd9f12d4f617fe887e3', 'A01730979@itesm.mx', ''),
('A01731489', '64310a1bc928c5962b3f596459b9c382', 'A01731489@itesm.mx', ''),
('A01731592', '0f48d8bfcf02732bfb3d522401c54edd', 'A01731592@itesm.mx', ''),
('A01731643', '035a33528ac5ca86132a5a9ae04d4628', 'A01731643@itesm.mx', ''),
('A01731751', '85163eb62e6b72c72f41e576978efd28', 'A01731751@itesm.mx', ''),
('A01732079', 'fa04176508bf55fbee33e583b841787d', 'A01732079@itesm.mx', ''),
('A01732113', '0e215e2418c7c7155ea1bf2b8b740c56', 'A01732113@itesm.mx', ''),
('A01732127', '713f08bd1fb77c5756f42a2b8f85ac2c', 'A01732127@itesm.mx', ''),
('A01732165', '35e158e5fd216abbe6ecf72c3449a3ae', 'A01732165@itesm.mx', ''),
('A01732167', '344a097384a230507f283233980e18d3', 'A01732167@itesm.mx', ''),
('A01732325', 'ca303b6984253d331cf1424ab9cf1af9', 'A01732325@itesm.mx', ''),
('A01732343', '91c3c2b4ed891981da848a6888e763be', 'A01732343@itesm.mx', ''),
('A01732412', '63dd4675a1e7f92f5bbfbbf98a8cec8d', 'A01732412@itesm.mx', ''),
('A01732525', '3739fb130d3f5418d92dabe6cceccbc4', 'A01732525@itesm.mx', ''),
('A01740197', '48df8968f3b9e49cce82345a2e8c8205', 'A01740197@itesm.mx', ''),
('A07144620', '06954f3fde2770303ca282358ea50ecd', 'A07144620@itesm.mx', ''),
('test', '098f6bcd4621d373cade4e832627b4f6', 's@email.com', ''),
('X01171525', 'b2e80f52af1b83e4a43052a35909492a', 'email@email.com', '2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
