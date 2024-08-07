CREATE DATABASE IF NOT EXISTS `invoice_app` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `invoice_app`;

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `invoice_id` varchar(36) NOT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `client_name` varchar(256) DEFAULT NULL,
  `client_email` varchar(128) DEFAULT NULL,
  `client_contact` varchar(10) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `title` text DEFAULT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

DROP TABLE IF EXISTS `invoice_items`;
CREATE TABLE IF NOT EXISTS `invoice_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` varchar(36) DEFAULT NULL,
  `product_name` varchar(256) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` varchar(36) NOT NULL,
  `first_name` varchar(256) DEFAULT NULL,
  `last_name` varchar(256) DEFAULT NULL,
  `contact` varchar(10) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `role` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

TRUNCATE TABLE `users`;

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `contact`, `password`, `role`) VALUES
('d0ed5f61-bef7-49f8-8b52-f9de6b77dd36', 'Irbba', 'Devs', '0251892785', '$2y$10$vbCf/nmxxbe/Vl82oN9K5OcYmLM3lilI2SrosYBkcYfZECCVkUb6e', 'manager');

