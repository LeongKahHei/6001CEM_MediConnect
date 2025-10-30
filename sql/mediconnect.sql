-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 04:52 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mediconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','accepted','rejected','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `doctor_id`, `patient_id`, `booking_date`, `booking_time`, `created_at`, `status`) VALUES
(5, 2, 11, '2025-10-25', '16:00:00', '2025-10-21 14:30:54', 'pending'),
(7, 2, 2, '2025-10-21', '12:00:00', '2025-10-23 06:19:22', 'pending'),
(8, 1, 1, '2025-10-21', '13:00:00', '2025-10-25 16:46:36', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_availability`
--

CREATE TABLE `doctor_availability` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor_availability`
--

INSERT INTO `doctor_availability` (`id`, `doctor_id`, `day_of_week`, `start_time`, `end_time`) VALUES
(19, 1, 'Tuesday', '13:00:00', '23:00:00'),
(23, 2, 'Tuesday', '13:00:00', '18:00:00'),
(24, 2, 'Saturday', '12:00:00', '18:00:00'),
(25, 2, 'Sunday', '14:00:00', '17:00:00'),
(26, 3, 'Thursday', '15:00:00', '17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users_doctor`
--

CREATE TABLE `users_doctor` (
  `id` int(11) NOT NULL,
  `username` varchar(22) NOT NULL,
  `email` varchar(22) NOT NULL,
  `password` varchar(222) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `specialty` enum('Cardiology','Urology','Dermatology','Neurology') DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `languages` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_doctor`
--

INSERT INTO `users_doctor` (`id`, `username`, `email`, `password`, `profile_picture`, `specialty`, `experience`, `phone`, `languages`) VALUES
(1, 'doc a', 'doc1@gmail.com', '7815696ecbf1c96e6894b779456d330e', '../uploads/doctor_profiles/1760542913_twittericon.png', 'Cardiology', '', '0124567892', 'English, Malay, Mandarin'),
(2, 'michelle', 'doc2@gmail.com', '7815696ecbf1c96e6894b779456d330e', '../uploads/doctor_profiles/1761137660_Untitled.png', 'Urology', '3 years', '0134822377', 'English'),
(3, 'doc c', 'doc3@gmail.com', '7815696ecbf1c96e6894b779456d330e', NULL, 'Dermatology', NULL, NULL, NULL),
(4, 'doc d', 'doc4@gmail.com', '7815696ecbf1c96e6894b779456d330e', NULL, 'Neurology', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_patient`
--

CREATE TABLE `users_patient` (
  `id` int(11) NOT NULL,
  `username` varchar(22) NOT NULL,
  `email` varchar(22) NOT NULL,
  `password` varchar(222) NOT NULL,
  `status` varchar(22) DEFAULT 'unverified',
  `phone` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_patient`
--

INSERT INTO `users_patient` (`id`, `username`, `email`, `password`, `status`, `phone`) VALUES
(1, 'user 0', 'leong@gmail.com', '7815696ecbf1c96e6894b779456d330e', 'unverified', '0124567891'),
(2, 'user a', 'user1@gmail.com', '7815696ecbf1c96e6894b779456d330e', 'unverified', ''),
(3, 'user b', 'user2@gmail.com', '7815696ecbf1c96e6894b779456d330e', 'unverified', ''),
(11, 'user c', 'user3@gmail.com', '7815696ecbf1c96e6894b779456d330e', 'unverified', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `users_doctor`
--
ALTER TABLE `users_doctor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_patient`
--
ALTER TABLE `users_patient`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users_doctor`
--
ALTER TABLE `users_doctor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users_patient`
--
ALTER TABLE `users_patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `fk_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `users_doctor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_patient` FOREIGN KEY (`patient_id`) REFERENCES `users_patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD CONSTRAINT `doctor_availability_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `users_doctor` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
