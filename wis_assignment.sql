-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 08:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wis_assignment`
--

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `order_id` int(11) NOT NULL,
  `product_id` int(4) NOT NULL,
  `price` decimal(4,2) NOT NULL,
  `unit` int(11) NOT NULL,
  `subtotal` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `count` int(11) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `prod_id` int(4) NOT NULL,
  `prod_name` varchar(100) NOT NULL,
  `prod_price` decimal(4,2) NOT NULL,
  `prod_img` varchar(100) NOT NULL,
  `prod_desc` varchar(200) NOT NULL,
  `prod_cat` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prod_id`, `prod_name`, `prod_price`, `prod_img`, `prod_desc`, `prod_cat`) VALUES
(1, 'Kuro Garlic Tsukemen', 23.90, 'kurogarlictsukemen1.jpg,kurogarlictsukemen2.jpg', 'Inspired by Japanese dipping noodles, served with mussels, prawns, shiitake mushrooms, soft-boiled onsen tamago, chuka wakame, and nori sheets.', 'Food'),
(2, 'Pad Kra Pao Pasta', 24.90, 'padkrapaopasta1.jpg, padkrapaopasta2.jpg', 'Spaghetti in hot & spicy basil sauce with grilled lemongrass chicken chop, cherry tomatoes & sriracha mayo.', 'Food'),
(3, 'The Gold Nest', 23.90, 'thegoldnest1.jpg, thegoldnest2.jpg', 'Classic golden egg fried rice with shrimps, chicken meatloaf, runny egg yolk, edamame, furikake, ebiko & nori strips, served with tangy sambal.', 'Food'),
(4, 'Hash Brown Stack', 20.90, 'hashbrownstack1.jpg, hashbrownstack2.jpg', 'Chicken meatloaf, crispy young mango tempura, fresh tomato salsa & pesto mayo.', 'Food'),
(5, 'Scwaffle', 18.90, 'scwaffle1.jpg, scwaffle2.jpg', 'Waffles with smoked duck & turkey ham, sunny-side-up, arugula, ricotta cheese & creamy tomato sauce.', 'Food'),
(6, 'The Breakfast Pancake', 19.90, 'thebreakfastpancake1.jpg, thebreakfastpancake2.jpg', 'A classic breakfast pancake served with butter and syrup.', 'Food'),
(7, 'Ultimate Cheese Melt', 31.90, 'ultimatecheesemelt1.jpg, ultimatecheesemelt2.jpg', 'Wholemeal grilled mozzarella & cheddar sandwich with turkey ham, beef bacon, arugula, fries, gherkin & ranch dip.', 'Food'),
(8, 'Sawadee Chicken Burger', 29.90, 'sawadeechickenburger1.jpg, burger2.jpg\r\n', 'Grilled chicken, pineapple ring, Japanese kyuri, tomato, cheddar, tom yum fries & sriracha mayo.', 'Food'),
(9, 'Supreme Beef Burger', 31.90, 'supremebeefburger1.jpg, burger2.jpg', 'Charcoal bun, 200g beef patty, creamy cheese sauce, tomato, Japanese kyuri & caramelized onions, with potato wedges.', 'Food'),
(10, 'Crispy Kale Salad', 17.90, 'crispykalesalad1.jpg, crispykalesalad2.jpg', 'Kale, yuba noodle, pomegranate, lemon garlic vinaigrette, shaved parmesan.', 'Food'),
(11, 'Sunrise Shakshuka', 19.90, 'sunriseshashuka1.jpg, sunriseshashuka2.jpg', 'Poached duck egg in hearty tomato sauce, avocado, ricotta, zucchini, yuba noodles, button mushrooms & edamame with sourdough toast.', 'Food'),
(12, 'Berries Matcha Latte', 16.90, 'berriesmatchalatte1.jpg, matcha2.jpg', 'A refreshing mix of matcha and berries.', 'Beverage'),
(13, 'Matcha Green Tea', 11.90, 'matchagreentea1.jpg, matcha2.jpg', 'Classic matcha green tea.', 'Beverage'),
(14, 'Milky Matcha', 13.90, 'milkymatcha1.jpg, matcha2.jpg', 'A creamy blend of milk and matcha.', 'Beverage'),
(15, 'Dirty Matcha Latte', 14.90, 'dirtymatchalatte1.jpg, matcha2.jpg', 'Espresso meets matcha in this bold drink.', 'Beverage'),
(16, 'Avocado Banana Smoothie', 19.90, 'avocadobananasmoothie1.jpg, smoothie2.jpg\r\n', 'A nutritious blend of avocado and banana.', 'Beverage'),
(17, 'Kale Banana Pineapple Smoothie', 19.90, 'kalebananapineapplesmoothie1.jpg, smoothie2.jpg', 'A tropical smoothie packed with vitamins.', 'Beverage'),
(18, 'Mixed Berries Smoothie', 20.90, 'mixedberriessmoothie1.jpg, smoothie2.jpg', 'A blend of strawberries, blueberries, and raspberries.', 'Beverage'),
(19, 'Berries Soda', 12.90, 'berriessoda1.jpg, soda2.jpg', 'A fizzy mixed berry soda.', 'Beverage'),
(20, 'Mango Soda', 13.90, 'mangosoda1.jpg, soda2.jpg', 'Refreshing mango-flavored soda.', 'Beverage'),
(21, 'Peach Soda', 11.90, 'peachsoda1.jpg,soda2.jpg', 'A light and fruity peach soda.', 'Beverage');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `gender` char(1) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `gender`, `email`, `password`, `name`, `photo`, `role`) VALUES
(1, 'F', '1@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Lisa Manobal', '67f814523ba4b.jpg', 'Admin'),
(2, 'F', '2@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Kim Jisoo', '680f8a96c9d7b.jpg', 'Member'),
(3, 'F', '3@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Kim Jennie ', '680f5af146cd1.jpg', 'Member'),
(4, 'M', '4@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Roseanne Park', '6814b6801731d.jpg', 'Member'),
(5, 'F', '5@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Law Joey', '680fa52daac75.jpg', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`prod_id`),
  ADD UNIQUE KEY `prod_id` (`prod_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prod_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`prod_id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`prod_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
