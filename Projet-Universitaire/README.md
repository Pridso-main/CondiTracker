# 🧗 CondiTracker

> **IoT Weather Station for Climbers** > *Projet Universitaire (SAE 33)*

## 📖 À propos

**CondiTracker** est une solution IoT complète conçue pour répondre à un problème spécifique des grimpeurs : connaître les conditions *réelles* d'une falaise (séchage du rocher, humidité locale, température de paroi) avant de se déplacer[cite: 9, 32].

Contrairement aux météos généralistes, CondiTracker déploie des capteurs directement sur site et calcule une **"Note Météo"** (/10) adaptée à la pratique de l'escalade[cite: 32, 79].

Ce projet a été initialement développé dans le cadre de la SAE 33 (R&T) et sert de fondation pour une version future plus robuste (LoRaWAN, IA prédictive).

## ⚙️ Architecture Technique

Le système repose sur une architecture 4-Tiers validée[cite: 58]:

1.  **Tier IoT (C Arduino) :** Station Arduino Uno avec capteurs DHT11 (Temp/Hum), Pluie et Luminosité. Utilise une machine à états (Modes PUSH/PULL) pour la stabilité[cite: 752, 753].
2.  **Tier Métier (Java) :** Serveur d'acquisition multi-threadé utilisant `jSerialComm`. Il valide, parse le protocole série propriétaire et persiste les données via JDBC[cite: 65].
3.  **Tier Données (SQL) :** Base de données MariaDB (WampServer) stockant l'historique et les utilisateurs.
4.  **Tier Web (HTML/CSS/PHP/JS) :** Interface utilisateur Responsive (API REST PHP native + Fetch JS) offrant un tableau de bord temps réel et une interface d'administration sécurisée[cite: 67].


## 🚀 Fonctionnalités Clés

* **Acquisition Temps Réel :** Remontée des données T°/Hum/Lux/Pluie toutes les minutes (Configurable)[cite: 19].
* **Protocole Robuste :** Communication Série bidirectionnelle avec Handshake (`<SYN>`, `<ACK>`) et gestion d'erreurs capteurs[cite: 89, 93].
* **Note de Condition :** Algorithme calculant une note de 0 à 10 pour l'escalade (Séchage, Confort thermique).
* **Supervision Opérateur :** Dashboard admin pour vérifier l'état de santé des stations (Heartbeat) et les logs systèmes[cite: 84].

## 🛠️ Installation & Démarrage

### Pré-requis
* Arduino IDE + Bibliothèques (DHT Sensor Library)
* Java JDK 21 + Eclipse/IntelliJ
* WampServer (Apache/MySQL/MariaDB)

### 1. Base de données
Importer le script `database/schema.sql` dans MariaDB pour créer la base `conditracker`.
User: `condi_user` / Pass: `condi`[cite: 66].

### 2. Station Météo
Téléverser le code `arduino/firmware/firmware` sur l'Arduino Uno connectée en USB.

### 3. Serveur Métier
Lancer l'application Java `server/src/ServeurMetier.java`. Il détectera automatiquement le port COM et initiera le Handshake `<SYN>`[cite: 94].

### 4. Interface Web
Placer le contenu du dossier `web/` dans le répertoire `www` de Wamp. Accéder via `http://localhost/conditracker`.


## 👤 Auteur

**LENOIR Camille** - *Etudiant en Réseaux et Télécommunications*
Projet réalisé dans le cadre du BUT R&T - IUT de Valence [\[cite: 124\]](https://www.iut-valence.fr).

---
