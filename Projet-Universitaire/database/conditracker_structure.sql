-- =================================================================
-- PROJET CONDITRACKER - VERSION FINALE (DATA ENRICHIE)
-- =================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `conditracker` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `conditracker`;

-- -----------------------------------------------------------------
-- 1. STRUCTURE DES TABLES
-- -----------------------------------------------------------------

-- Table Utilisateurs (Mots de passe EN CLAIR)
DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE `utilisateurs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `identifiant` VARCHAR(50) NOT NULL UNIQUE,
    `mot_de_passe` VARCHAR(255) NOT NULL,
    `role` ENUM('ADMIN', 'OPERATEUR') DEFAULT 'OPERATEUR',
    `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table Stations
DROP TABLE IF EXISTS `stations`;
CREATE TABLE `stations` (
    `id` INT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL,
    `localisation` VARCHAR(255),
    `latitude` DECIMAL(10, 8),
    `longitude` DECIMAL(11, 8),
    `etat` ENUM('EN_LIGNE', 'HORS_LIGNE', 'MAINTENANCE') DEFAULT 'HORS_LIGNE',
    `derniere_synchro` TIMESTAMP NULL
) ENGINE=InnoDB;

-- Table Mesures
DROP TABLE IF EXISTS `mesures`;
CREATE TABLE `mesures` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT NOT NULL,
    `temperature` FLOAT NOT NULL,
    `humidite` FLOAT NOT NULL,
    `luminosite` INT NOT NULL,
    `pluie` INT DEFAULT 0, -- 0-300: Pluie, 300-700: Humide, >700: Sec
    `date_heure` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_mesure_station` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE CASCADE,
    INDEX `idx_history` (`station_id`, `date_heure`)
) ENGINE=InnoDB;

-- Table Logs
DROP TABLE IF EXISTS `logs_systeme`;
CREATE TABLE `logs_systeme` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT NULL,
    `niveau` ENUM('INFO', 'WARNING', 'ERROR') NOT NULL,
    `message` TEXT NOT NULL,
    `source` VARCHAR(50) DEFAULT 'ServeurJava',
    `date_heure` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------------------
-- 2. INSERTION DES DONNEES (SCENARIOS METEO)
-- -----------------------------------------------------------------

-- Utilisateurs
INSERT INTO `utilisateurs` (`identifiant`, `mot_de_passe`, `role`) VALUES
('admin', 'admin', 'ADMIN'),
('operateur_sud', 'condi', 'OPERATEUR');

-- Stations
-- Station 1 : Parfaite
-- Station 2 : Mauvais temps (mais on la met EN_LIGNE pour voir les données s'afficher)
INSERT INTO `stations` (`id`, `nom`, `localisation`, `etat`, `derniere_synchro`) VALUES
(1, 'Falaise de Saint-Péray', 'Ardèche, France', 'EN_LIGNE', NOW()),
(2, 'Site de Omblèze', 'Drôme, France', 'EN_LIGNE', NOW()); 

-- =================================================================
-- SCENARIO STATION 1 : "CONDITIONS PARFAITES" (12 dernières heures)
-- Température agréable (18-24°C), Sec (1023), Ensoleillé
-- =================================================================
INSERT INTO `mesures` (`station_id`, `temperature`, `humidite`, `luminosite`, `pluie`, `date_heure`) VALUES
(1, 14.5, 60.0, 100, 1023, NOW() - INTERVAL 12 HOUR), -- Matin frais
(1, 16.0, 55.0, 300, 1023, NOW() - INTERVAL 11 HOUR),
(1, 17.5, 50.0, 500, 1023, NOW() - INTERVAL 10 HOUR),
(1, 19.0, 48.0, 700, 1023, NOW() - INTERVAL 9 HOUR),
(1, 20.5, 45.0, 850, 1023, NOW() - INTERVAL 8 HOUR), -- Midi
(1, 22.0, 42.0, 950, 1023, NOW() - INTERVAL 7 HOUR),
(1, 23.5, 40.0, 1000, 1023, NOW() - INTERVAL 6 HOUR), -- Pic de chaleur
(1, 24.0, 38.0, 900, 1023, NOW() - INTERVAL 5 HOUR),
(1, 23.5, 40.0, 800, 1023, NOW() - INTERVAL 4 HOUR),
(1, 22.5, 42.0, 600, 1023, NOW() - INTERVAL 3 HOUR),
(1, 21.0, 44.0, 400, 1023, NOW() - INTERVAL 2 HOUR),
(1, 20.0, 45.0, 200, 1023, NOW() - INTERVAL 1 HOUR),
(1, 19.5, 46.0, 100, 1023, NOW());                 -- Maintenant (Soirée idéale)

-- =================================================================
-- SCENARIO STATION 2 : "MAUVAIS TEMPS" (12 dernières heures)
-- Froid (5-8°C), Humide (>80%), Pluie (<400), Sombre
-- =================================================================
INSERT INTO `mesures` (`station_id`, `temperature`, `humidite`, `luminosite`, `pluie`, `date_heure`) VALUES
(2, 4.5, 95.0, 0, 200, NOW() - INTERVAL 12 HOUR),   -- Nuit glaciale et pluvieuse
(2, 4.8, 94.0, 20, 250, NOW() - INTERVAL 11 HOUR),
(2, 5.0, 92.0, 50, 300, NOW() - INTERVAL 10 HOUR),
(2, 5.2, 90.0, 80, 200, NOW() - INTERVAL 9 HOUR),   -- Averse
(2, 5.5, 88.0, 100, 150, NOW() - INTERVAL 8 HOUR),
(2, 6.0, 85.0, 150, 300, NOW() - INTERVAL 7 HOUR),  -- Légère éclaircie
(2, 6.5, 85.0, 120, 100, NOW() - INTERVAL 6 HOUR),  -- Grosse pluie
(2, 6.0, 88.0, 80, 100, NOW() - INTERVAL 5 HOUR),
(2, 5.8, 90.0, 60, 200, NOW() - INTERVAL 4 HOUR),
(2, 5.5, 92.0, 40, 300, NOW() - INTERVAL 3 HOUR),
(2, 5.2, 94.0, 20, 350, NOW() - INTERVAL 2 HOUR),
(2, 5.0, 95.0, 10, 200, NOW() - INTERVAL 1 HOUR),
(2, 4.8, 96.0, 5, 150, NOW());                      -- Maintenant (Invivable)

-- Logs de démo pour l'opérateur
INSERT INTO `logs_systeme` (`station_id`, `niveau`, `message`, `source`, `date_heure`) VALUES
(1, 'INFO', 'Démarrage système Station 01', 'Arduino', NOW() - INTERVAL 12 HOUR),
(2, 'WARNING', 'Taux humidité critique (>90%)', 'ServeurJava', NOW() - INTERVAL 6 HOUR),
(1, 'INFO', 'Synchronisation PUSH reçue', 'ServeurJava', NOW() - INTERVAL 10 MINUTE),
(2, 'ERROR', 'Capteur Luminosité : Valeur trop faible anormale', 'Arduino', NOW() - INTERVAL 5 MINUTE);

SET FOREIGN_KEY_CHECKS = 1;