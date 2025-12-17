-- CondiTracker Data Simulation
-- Objectif : Peupler la BDD pour la démonstration et les tests frontend.

USE `conditracker`;

-- 1. Création des Utilisateurs (Admin / Pass: condi)
-- Note: Le hash ci-dessous est MD5('condi') pour correspondre à ton prototype Java actuel.
INSERT INTO `utilisateurs` (`identifiant`, `mot_de_passe_hash`, `role`) VALUES
('admin', '1f32aa4c9a1d2ea010adcf2348166a04', 'ADMIN'),
('operateur_sud', '1f32aa4c9a1d2ea010adcf2348166a04', 'OPERATEUR');

-- 2. Création des Stations
INSERT INTO `stations` (`id`, `nom`, `localisation`, `etat`, `derniere_synchro`) VALUES
(1, 'Falaise de Saint-Péray', 'Ardèche, France', 'EN_LIGNE', NOW()),
(2, 'Site de Omblèze', 'Drôme, France', 'HORS_LIGNE', NOW() - INTERVAL 2 DAY);

-- 3. Simulation Historique sur 24h (Pour Station 1)
-- Scénario : Nuit fraîche -> Matin humide -> Après-midi sec et chaud -> Soirée douce

-- Il y a 24h (Nuit)
INSERT INTO `mesures` (`station_id`, `temperature`, `humidite`, `luminosite`, `pluie`, `date_heure`) VALUES
(1, 12.5, 80.0, 0, 1023, NOW() - INTERVAL 24 HOUR),
(1, 12.0, 82.0, 0, 1023, NOW() - INTERVAL 22 HOUR),
(1, 11.5, 85.0, 0, 1023, NOW() - INTERVAL 20 HOUR);

-- Il y a 12h (Matinée - Soleil levant)
INSERT INTO `mesures` (`station_id`, `temperature`, `humidite`, `luminosite`, `pluie`, `date_heure`) VALUES
(1, 14.0, 75.0, 200, 1023, NOW() - INTERVAL 12 HOUR),
(1, 16.5, 60.0, 450, 1023, NOW() - INTERVAL 10 HOUR);

-- Il y a 6h (Après-midi - Pic de chaleur, conditions idéales)
INSERT INTO `mesures` (`station_id`, `temperature`, `humidite`, `luminosite`, `pluie`, `date_heure`) VALUES
(1, 22.0, 45.0, 900, 1023, NOW() - INTERVAL 6 HOUR),
(1, 23.5, 40.0, 950, 1023, NOW() - INTERVAL 4 HOUR),
(1, 23.0, 42.0, 800, 1023, NOW() - INTERVAL 2 HOUR);

-- Maintenant (Temps réel)
INSERT INTO `mesures` (`station_id`, `temperature`, `humidite`, `luminosite`, `pluie`, `date_heure`) VALUES
(1, 21.5, 44.0, 600, 1023, NOW());

-- 4. Simulation Logs (Pour la supervision)
INSERT INTO `logs_systeme` (`station_id`, `niveau`, `message`, `source`, `date_heure`) VALUES
(1, 'INFO', 'Démarrage du système - Handshake OK', 'Arduino', NOW() - INTERVAL 1 DAY),
(1, 'INFO', 'Connexion Opérateur admin réussie', 'ServeurJava', NOW() - INTERVAL 2 HOUR),
(2, 'ERROR', 'Perte de signal série - Timeout', 'ServeurJava', NOW() - INTERVAL 1 DAY),
(1, 'WARNING', 'Batterie faible (simulation)', 'Arduino', NOW() - INTERVAL 30 MINUTE);