# 🧗 CondiTracker

> **La Station Météo Connectée dédiée à l'Escalade.**
> *Du prototype universitaire à la solution IoT professionnelle.*

![Status](https://img.shields.io/badge/Status-V1.0%20Stable-success)
![Java](https://img.shields.io/badge/Backend-Java%2021-orange)
![Arduino](https://img.shields.io/badge/IoT-Arduino%20Uno-blue)
![Web](https://img.shields.io/badge/Frontend-PHP%20%2F%20JS-yellow)
![License](https://img.shields.io/badge/License-MIT-green)

---

## 📜 L'Histoire du Projet

### Le Problème
Pour un grimpeur, la météo standard (Google, Météo France) ne suffit pas. Savoir qu'il fait 20°C en ville ne dit pas si :
* Le rocher est **sec** après la pluie de la veille.
* La falaise est en plein **soleil** (trop chaud) ou à l'ombre.
* L'humidité de l'air rend la roche "collante" (bonnes conditions) ou glissante.

### La Genèse (SAE 33)
**CondiTracker** est né d'un projet universitaire de 2ème année de BUT R&T (SAE 33). L'objectif initial était académique : développer une chaîne d'acquisition complète (Capteur → BDD → Web).
Cependant, face à la pertinence du besoin, le projet a évolué pour devenir une véritable preuve de concept (POC) d'une station météo autonome capable de calculer une **"Note de Condition"** (/10) spécifique à l'escalade.

---

## 🛠️ Architecture & Système

Le système repose sur une architecture **4-Tiers** cloisonnée et modulaire, garantissant la fiabilité des données du capteur jusqu'à l'utilisateur final.

### Vue d'ensemble
1.  **Tier Physique (IoT)** : Une station autonome collecte les données brutes.
2.  **Tier Acquisition (Métier)** : Un serveur centralise, valide et traite les flux de données.
3.  **Tier Persistance (Data)** : Stockage sécurisé de l'historique et des configurations.
4.  **Tier Présentation (Web)** : Interface utilisateur temps réel et dashboard d'administration.

![Architecture Web](docs/img_archi_web.png)

### Détails Techniques

| Module | Technologie | Rôle & Fonctionnement |
| :--- | :--- | :--- |
| **Station Météo** | **Arduino Uno (C++)** | Gère les capteurs (DHT11, Pluie, Lux). Utilise une **Machine à États** (Init, Push, Pull) pour assurer la stabilité. Communique via un protocole Série propriétaire (`<SYN>`, `<ACK>`, `<DATA>`). |
| **Serveur Métier** | **Java (Eclipse)** | Application multi-threadée utilisant `jSerialComm`. Elle écoute le port série, décode les trames, filtre les erreurs capteurs et insère les données propres en base via JDBC. |
| **Base de Données** | **MariaDB (SQL)** | Structure relationnelle optimisée. Tables : `mesures` (Données brutes), `stations` (Inventaire), `utilisateurs` (Admin), `logs_systeme` (Supervision). |
| **Interface Web** | **PHP / JS Vanilla** | **Frontend :** Tableau de bord responsive avec Fetch API (pas de rechargement de page).<br>**Backend :** API REST PHP native (`api.php`) servant les données au format JSON. |

---

## 📂 Structure du Dépôt

L'arborescence du projet a été restructurée pour séparer clairement les responsabilités :

```text
/CondiTracker
├── /arduino              # Firmware C++ (Code Arduino .ino)
├── /server               # Code Source Java (Serveur d'Acquisition)
├── /web                  # Application Web (HTML/CSS/JS/PHP)
├── /database             # Scripts SQL (Schéma de création + Données de simulation)
└── /docs                 # Documentation, Protocoles et Schémas UML
