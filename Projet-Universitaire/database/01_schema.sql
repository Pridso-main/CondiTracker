-- CondiTracker Database Schema
-- Version: 1.0 (Professional Upgrade)
-- Engine: MariaDB / MySQL
-- Charset: utf8mb4 (Support universel, emojis inclus)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Création de la Base de Données
CREATE DATABASE IF NOT EXISTS `conditracker` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `conditracker`;

-- 2. Table : Utilisateurs (Opérateurs)
-- Sécurisation : mot_de_passe_hash stockera le hash (MD5 pour l'instant, évolutif vers Bcrypt)
CREATE TABLE IF NOT EXISTS `utilisateurs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `identifiant` VARCHAR(50) NOT NULL UNIQUE,
    `mot_de_passe_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('ADMIN', 'OPERATEUR') DEFAULT 'OPERATEUR',
    `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Table : Stations (Inventaire du parc)
-- Permet de gérer proprement les IDs (01, 02...) et leur localisation
CREATE TABLE IF NOT EXISTS `stations` (
    `id` INT PRIMARY KEY, -- On force l'ID pour correspondre à celui codé en dur dans l'Arduino (ex: 1)
    `nom` VARCHAR(100) NOT NULL,
    `localisation` VARCHAR(255),
    `latitude` DECIMAL(10, 8),
    `longitude` DECIMAL(11, 8),
    `etat` ENUM('EN_LIGNE', 'HORS_LIGNE', 'MAINTENANCE') DEFAULT 'HORS_LIGNE',
    `derniere_synchro` TIMESTAMP NULL
) ENGINE=InnoDB;

-- 4. Table : Mesures (Données IoT)
-- Stockage des relevés. 
-- AJOUT PRO : Index sur (station_id, date_heure) pour que les requêtes d'historique soient instantanées.
CREATE TABLE IF NOT EXISTS `mesures` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT NOT NULL,
    `temperature` FLOAT NOT NULL,
    `humidite` FLOAT NOT NULL,
    `luminosite` INT NOT NULL,     -- En Lux ou 0/1 selon le capteur
    `pluie` INT DEFAULT 0,         -- Valeur brute du capteur (0-1023)
    `date_heure` DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    -- Contrainte de Clé Étrangère : Impossible d'insérer une mesure pour une station inexistante
    CONSTRAINT `fk_mesure_station` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE CASCADE,
    
    -- Index de performance pour les graphiques
    INDEX `idx_station_date` (`station_id`, `date_heure`)
) ENGINE=InnoDB;

-- 5. Table : Logs Système (Supervision)
-- Pour tracer les erreurs (ex: "ERR_DHT") sans polluer la table de mesures
CREATE TABLE IF NOT EXISTS `logs_systeme` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT NULL,
    `niveau` ENUM('INFO', 'WARNING', 'ERROR') NOT NULL,
    `message` TEXT NOT NULL,
    `source` VARCHAR(50) DEFAULT 'ServeurJava', -- 'ServeurJava' ou 'Arduino'
    `date_heure` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT `fk_log_station` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;