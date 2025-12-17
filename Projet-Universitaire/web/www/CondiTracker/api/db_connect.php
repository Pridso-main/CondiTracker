<?php
// Fichier : api/db_connect.php

// Paramètres de connexion (Adaptés à ta config Java/Wamp)
$host = '127.0.0.1'; // Localhost
$port = '3307';      // Port MariaDB spécifique WAMP (selon ton contexte)
$db   = 'conditracker'; // NOM DE TA BASE (Vérifie si c'est bien ça !)
$user = 'condi_user';       // Ton user créé précédemment
$pass = 'condi';            // Ton mot de passe

// La "phrase" de connexion (DSN)
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Affiche les erreurs SQL
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retourne des tableaux associatifs
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Création de la connexion PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En cas d'erreur, on arrête tout et on affiche le message
    die("Erreur de connexion BDD : " . $e->getMessage());
}
?>