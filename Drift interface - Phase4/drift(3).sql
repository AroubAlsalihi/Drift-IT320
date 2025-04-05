-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 05, 2025 at 02:43 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `drift`
--

-- --------------------------------------------------------

--
-- Table structure for table `Car`
--

CREATE TABLE `Car` (
  `Car_Id` int NOT NULL,
  `Year` year NOT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Condition` enum('new','used','customizable') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Gear` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Interior_Color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Exterior_Color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Engine_Type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Fuel_Consumption` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Brand` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Speed` decimal(5,2) NOT NULL,
  `Model` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Car`
--

INSERT INTO `Car` (`Car_Id`, `Year`, `Description`, `Price`, `Condition`, `Gear`, `Interior_Color`, `Exterior_Color`, `Engine_Type`, `Fuel_Consumption`, `Brand`, `Speed`, `Model`) VALUES
(1, '2025', 'The 2025 Porsche Panamera is a luxury sports sedan that combines high performance with everyday practicality. It features a 2.9-liter twin-turbocharged V6 engine producing 348 horsepower, paired with an 8-speed Porsche Doppelkupplung (PDK) transmission. The vehicle offers a spacious interior with premium materials, advanced technology, and seating for four passengers.', 102800.00, 'new', '8-speed Porsche Doppelkupplung (PDK) Automatic', 'Black', 'Chalk', '2.9L Twin-Turbocharged V6', '21 MPG combined', 'Porsche', 280.00, 'Panamera'),
(2, '2023', 'The Audi Q5 is a premium all-electric SUV that blends luxury, performance, and sustainability. With a sleek design, dual electric motors, and Audi’s legendary quattro®️ all-wheel drive, the E-tron delivers smooth, powerful acceleration and outstanding control. Its high-tech interior features a virtual cockpit, dual touchscreens, and upscale finishes, ensuring a refined driving experience. Offering fast charging, intelligent driver assistance, and zero emissions, the E-tron represents Audi’s bold step into the future of mobility.', 175900.00, 'customizable', 'Automatic', 'Black', 'Black', 'Electric', '0.0', 'Audi', 200.00, 'Q5'),
(3, '2023', 'The 2023 Tesla Model X is a fully electric luxury SUV known for its impressive acceleration, spacious interior, and cutting-edge technology. It features a dual motor all-wheel-drive system delivering rapid performance and a range of advanced autopilot capabilities.', 98900.00, 'new', 'Single-speed automatic', 'Black', 'White', 'Electric', 'Approx. 102 MPGe', 'Tesla', 155.00, 'Model X'),
(4, '2025', 'The Tesla Model 3 is a fully electric mid-size sedan introduced in 2017, designed to offer a more affordable entry into the electric vehicle market without compromising on performance or technology. The 2025 Model 3 continues this tradition, featuring a sleek fastback design and advanced features. The interior boasts a minimalist design with a 15-inch touchscreen interface that controls most of the car\'s functions. Standard features include heated front and rear seats, a premium audio system.', 49990.00, 'new', 'Automatic', 'Black', 'red', 'Electric', '15 kWh/100 miles', 'Tesla', 162.00, 'Model 3'),
(5, '2017', 'Built for a life centred around sport. The 718 models transfer the racing spirit of the legendary Porsche mid-engined race cars from the 1950s and 1960s – such as the 718 RSK Spyder or the 718 GTR Coupe – to the roads of today with one goal: to elevate the everyday beyond the ordinary.', 33714.04, 'used', 'Automatic', 'Black', 'Graphite Blue Metallic', '2.5 L', '12.4 l/100 km', 'Porsche', 290.00, 'Cayman 718');

-- --------------------------------------------------------

--
-- Table structure for table `Car_Photo`
--

CREATE TABLE `Car_Photo` (
  `Photo_Id` int NOT NULL,
  `Car_Id` int NOT NULL,
  `Photo_URL` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Car_Photo`
--

INSERT INTO `Car_Photo` (`Photo_Id`, `Car_Id`, `Photo_URL`) VALUES
(1, 3, 'home_img/featured1.png'),
(2, 4, 'home_img/featured2.png'),
(3, 2, 'home_img/featured3.png'),
(4, 5, 'home_img/featured4.png'),
(5, 1, 'home_img/featured5.png'),
(6, 1, 'ProcutImages/car1.png'),
(7, 1, 'ProcutImages/Seat.jpg'),
(8, 1, 'ProcutImages/inside.jpg'),
(9, 1, 'ProcutImages/CarFromFront.png'),
(10, 3, 'ProcutImages/tesla-white-outer1.png'),
(11, 3, 'ProcutImages/tesla-modelx-inside2.png'),
(12, 3, 'ProcutImages/tesla-white-back3.png'),
(13, 3, 'ProcutImages/tesla-white-front4.png'),
(14, 4, 'ProcutImages/tesla-red-out1.png'),
(15, 4, 'ProcutImages/tesla-red-inside2.png'),
(16, 4, 'ProcutImages/tesla-red-back3.png'),
(17, 4, 'ProcutImages/tesla-red-front4.png'),
(18, 5, 'ProcutImages/porche-blue-outer1.png'),
(19, 5, 'ProcutImages/porche-blue-inside2.png'),
(20, 5, 'ProcutImages/porche-blue-back3.png'),
(21, 5, 'ProcutImages/porche-blue-front4.png');

-- --------------------------------------------------------

--
-- Table structure for table `Customer`
--

CREATE TABLE `Customer` (
  `Customer_Id` int NOT NULL,
  `Email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Age` int NOT NULL,
  `PhoneNumber` char(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Customer`
--

INSERT INTO `Customer` (`Customer_Id`, `Email`, `Name`, `Password`, `Age`, `PhoneNumber`) VALUES
(5, 'sara@gmail.com', 'sara', '$2y$10$ZEdYtAT45gXwhKBoAY/Yxur3.PxqsVef5P7w31nKAmNayBdKnt5f.', 19, '0987654321'),
(6, 'haya@gmail.com', 'haya', '$2y$10$8K0fHmET0XMYfKrOLJBvKeRs3jbJbxh9RefQBycUuOjFRhcgA3jzG', 19, '0987654321');

-- --------------------------------------------------------

--
-- Table structure for table `Order`
--

CREATE TABLE `Order` (
  `Order_Id` int NOT NULL,
  `Car_Id` int NOT NULL,
  `Customer_Id` int NOT NULL,
  `Status` enum('pending','confirmed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Order`
--

INSERT INTO `Order` (`Order_Id`, `Car_Id`, `Customer_Id`, `Status`, `Location`, `Date`, `Time`) VALUES
(22, 5, 5, 'confirmed', 'Riyadh,rafha,alsahafa', '2025-04-05', '12:33:13'),
(23, 1, 6, 'confirmed', 'Riyadh, rafha, alsafhah', '2025-04-12', '13:35:39'),
(24, 2, 6, 'confirmed', 'Riyadh, rafha, alsafhah', '2025-04-12', '13:42:55');

-- --------------------------------------------------------

--
-- Table structure for table `Report`
--

CREATE TABLE `Report` (
  `Report_Id` int NOT NULL,
  `Vehical_Interior` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Vehical_Exterior` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Vehical_Chassis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Mechanical_Condition` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Additional_Note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Date` date NOT NULL,
  `Car_Id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Report`
--

INSERT INTO `Report` (`Report_Id`, `Vehical_Interior`, `Vehical_Exterior`, `Vehical_Chassis`, `Mechanical_Condition`, `Additional_Note`, `Date`, `Car_Id`) VALUES
(1, 'Interior is in excellent condition', 'Exterior has minor scratches', 'VIN1234567890', 'All systems functioning as expected', 'No additional notes', '2023-10-01', 5);

-- --------------------------------------------------------

--
-- Table structure for table `Review`
--

CREATE TABLE `Review` (
  `Review_Id` int NOT NULL,
  `Order_Id` int NOT NULL,
  `Comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Review`
--

INSERT INTO `Review` (`Review_Id`, `Order_Id`, `Comment`) VALUES
(11, 22, 'The website is super easy to navigate. I found the car I wanted in minutes — everything is clearly organized!');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Car`
--
ALTER TABLE `Car`
  ADD PRIMARY KEY (`Car_Id`);

--
-- Indexes for table `Car_Photo`
--
ALTER TABLE `Car_Photo`
  ADD PRIMARY KEY (`Photo_Id`),
  ADD KEY `Car_Id` (`Car_Id`);

--
-- Indexes for table `Customer`
--
ALTER TABLE `Customer`
  ADD PRIMARY KEY (`Customer_Id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `Order`
--
ALTER TABLE `Order`
  ADD PRIMARY KEY (`Order_Id`),
  ADD KEY `Car_Id` (`Car_Id`),
  ADD KEY `Customer_Id` (`Customer_Id`);

--
-- Indexes for table `Report`
--
ALTER TABLE `Report`
  ADD PRIMARY KEY (`Report_Id`),
  ADD KEY `Car_Id` (`Car_Id`) USING BTREE;

--
-- Indexes for table `Review`
--
ALTER TABLE `Review`
  ADD PRIMARY KEY (`Review_Id`),
  ADD UNIQUE KEY `Order_Id` (`Order_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Car`
--
ALTER TABLE `Car`
  MODIFY `Car_Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Customer`
--
ALTER TABLE `Customer`
  MODIFY `Customer_Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Order`
--
ALTER TABLE `Order`
  MODIFY `Order_Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `Report`
--
ALTER TABLE `Report`
  MODIFY `Report_Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Review`
--
ALTER TABLE `Review`
  MODIFY `Review_Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Car_Photo`
--
ALTER TABLE `Car_Photo`
  ADD CONSTRAINT `car_photo_ibfk_1` FOREIGN KEY (`Car_Id`) REFERENCES `Car` (`Car_Id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `Order`
--
ALTER TABLE `Order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`Car_Id`) REFERENCES `Car` (`Car_Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`Customer_Id`) REFERENCES `Customer` (`Customer_Id`) ON DELETE CASCADE;

--
-- Constraints for table `Report`
--
ALTER TABLE `Report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`Car_Id`) REFERENCES `Car` (`Car_Id`) ON DELETE RESTRICT;

--
-- Constraints for table `Review`
--
ALTER TABLE `Review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`Order_Id`) REFERENCES `Order` (`Order_Id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
