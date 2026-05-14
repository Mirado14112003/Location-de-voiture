-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage de la structure de table location_voiture. admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table location_voiture.admins : ~0 rows (environ)
INSERT INTO `admins` (`id`, `username`, `email`, `password`, `created_at`) VALUES
	(15, 'Mirado', 'razafimandimbymirado@gmail.com', '$2y$10$nV8sbH7IPGi2.HZ48bkznOJtESmuWAiw1cKB7867aFpVUSUoC6r.W', '2024-03-06 11:27:55'),
	(16, 'Misaela', 'misaela@gmail.com', '$2y$10$K3YCA/K7/TcA12r9ZxVoLOr4foJ1hgWflKuKlCBeufFUXxvRtytaO', '2024-03-13 06:10:58');

-- Listage de la structure de table location_voiture. client
CREATE TABLE IF NOT EXISTS `client` (
  `ID_client` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(40) DEFAULT NULL,
  `prenom` varchar(35) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `adresse` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID_client`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table location_voiture.client : ~7 rows (environ)
INSERT INTO `client` (`ID_client`, `nom`, `prenom`, `email`, `telephone`, `adresse`) VALUES
	(11, 'Razafimandimby', 'Mirado', 'razafimandimbymirado@gmail.com', '034 77 605 35', 'itaosy'),
	(14, 'Rakotonandrasana', 'Andraina', 'andraina@gmail.com', '034 87 544 69', 'Mahazoarivo'),
	(15, 'Ramanantsialoninarisoa', 'Fara Lisy', 'faralisy@gmail.com', '033 22 547 87', 'Andranomadio'),
	(16, 'Raharijaona', 'Manoa', 'manoa@gmail.com', '038 77 455 41', 'Vohitsoa'),
	(17, 'Rakotonirina', 'Aubry Nicolas', 'aubrynicolas@gmail.com', '037 88 547 45', 'Ampefiloha'),
	(18, 'Razafimandimby', 'Aina', 'aina.harintsialonina@gmail.com', '034 55 874 56', 'Itaosy'),
	(19, 'Rabenamana', 'Hary Lala', 'hary@gmail.com', '038 55 874 15', 'Diego');

-- Listage de la structure de table location_voiture. reservation
CREATE TABLE IF NOT EXISTS `reservation` (
  `reservation_id` int NOT NULL AUTO_INCREMENT,
  `client_id` varchar(5) DEFAULT NULL,
  `immatr` varchar(10) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  PRIMARY KEY (`reservation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table location_voiture.reservation : ~1 rows (environ)
INSERT INTO `reservation` (`reservation_id`, `client_id`, `immatr`, `date_debut`, `date_fin`) VALUES
	(8, '19', '8086 FC', '2023-03-12', '2023-04-12'),
	(9, '11', '1234 TBH', '2024-04-03', '2024-05-03'),
	(10, '14', '1254 TBA', '2024-05-02', '2024-05-05'),
	(11, '16', '4444 TBV', '2024-06-05', '2024-08-05'),
	(12, '18', '5555 TCA', '2024-02-05', '2025-05-04'),
	(13, '14', '4567 WWT', '2022-08-05', '2023-05-04'),
	(14, '17', '6666 TBU', '2024-03-13', '2024-04-13'),
	(15, '15', '7777 TBU', '2024-03-13', '2024-04-13');

-- Listage de la structure de table location_voiture. voiture
CREATE TABLE IF NOT EXISTS `voiture` (
  `immatr` varchar(10) NOT NULL,
  `marque` varchar(20) DEFAULT NULL,
  `modele` varchar(20) DEFAULT NULL,
  `couleur` varchar(15) DEFAULT NULL,
  `disponibilite` tinyint(1) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`immatr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table location_voiture.voiture : ~9 rows (environ)
INSERT INTO `voiture` (`immatr`, `marque`, `modele`, `couleur`, `disponibilite`, `image`) VALUES
	('1234 TBH', 'Mercedes-Benz', 'Class G', 'Noir', 0, '../image/Joinsteer_lld_Mercedes_Benz_G_63_AMG_585_ch_0.jpg'),
	('1254 TBA', 'Mercedes-Benz', 'Maybach GLS', 'Grenat', 0, '../image/S0-mercedes-devoile-le-maybach-gls-611155.jpg'),
	('4444 TBV', 'Citroën', '2 Chevaux', 'Rose', 0, 'mariag14.jpg'),
	('4567 WWT', 'Toyota', 'V8', 'Noir', 0, '../image/115c758d-e936-48dc-a33b-56cdd7665fee-732.jpg'),
	('5555 TCA', 'Lamborghini', 'Aventador', 'Jaune', 0, '2023_Lamborghini_Aventador_Ultimae.jpg'),
	('6666 TBU', 'Lamborghini', 'Huracan', 'Jaune', 0, '2017_Lamborghini_Huracan_LP610.jpg'),
	('7777 TBU', 'Dodge', 'Challenger', 'Gris', 0, 'dodge-challenger-2023-03.jpg'),
	('8086 FC', 'Peugeot', '405', 'Rouge', 0, '02 Peugeot 405 T16.jpg'),
	('8888 TBV', 'Dodge', 'Challenger', 'Rouge', 1, '525639-dodge-challenger-d-occasion-quelle-version-choisir.jpg');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
