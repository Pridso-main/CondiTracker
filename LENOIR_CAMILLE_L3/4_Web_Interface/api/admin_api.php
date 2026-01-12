<?php
// Fichier : api/admin_api.php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Utile pour éviter les blocages locaux
require_once 'db_connect.php';

// Récupération des données JSON (envoyées par le fetch JS)
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

// Détermine l'action (soit via URL pour getData, soit via JSON pour login)
$action = $_GET['action'] ?? ($input['action'] ?? '');

$response = ["success" => false, "message" => "Action inconnue"];

try {

    // --- CAS 1 : CONNEXION (LOGIN) ---
    if ($action === 'login') {
        // On récupère identifiant et mot de passe envoyés par le JS
        // Attention : on utilise les mêmes noms que dans la BDD pour ne pas se perdre
        $user = $input['identifiant'] ?? '';
        $pass = $input['mot_de_passe'] ?? '';

        // REQUÊTE VERSION "EN CLAIR" (Sans MD5)
        // On vérifie que l'identifiant ET le mot de passe correspondent exactement
        $sql = "SELECT * FROM utilisateurs WHERE identifiant = :u AND mot_de_passe = :p";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['u' => $user, 'p' => $pass]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            $response = [
                "success" => true, 
                "message" => "Connexion réussie",
                "role" => $admin['role'] // On renvoie le rôle (ADMIN ou OPERATEUR)
            ];
        } else {
            $response = [
                "success" => false, 
                "message" => "Identifiant ou mot de passe incorrect"
            ];
        }
    }

    // --- CAS 2 : TABLEAU DE BORD (GETDATA) ---
    elseif ($action === 'getData') {
        
        // A. Récupérer les LOGS
        // On prend les 20 derniers logs système
        $sqlLogs = "SELECT * FROM logs_systeme ORDER BY date_heure DESC LIMIT 20";
        $stmtLogs = $pdo->query($sqlLogs);
        $logs = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);

        // B. Récupérer l'ÉTAT DES STATIONS
        // On utilise la nouvelle table 'stations' qui contient déjà l'état (EN_LIGNE/HORS_LIGNE)
        // C'est beaucoup plus simple qu'avant !
        $sqlStations = "SELECT id, nom, localisation, etat, derniere_synchro FROM stations";
        $stmtStations = $pdo->query($sqlStations);
        $stations = $stmtStations->fetchAll(PDO::FETCH_ASSOC);

        // Formatage pour le frontend (pour que le JS comprenne bien)
        $stationsFormatted = [];
        foreach($stations as $s) {
            $stationsFormatted[] = [
                "id" => $s['id'],
                "nom" => $s['nom'],
                "last_seen" => $s['derniere_synchro'],
                // Le JS attend un booléen 'online' pour afficher la pastille verte/rouge
                "online" => ($s['etat'] === 'EN_LIGNE') 
            ];
        }

        $response = [
            "success" => true,
            "data" => [
                "logs" => $logs,
                "stations" => $stationsFormatted
            ]
        ];
    }

} catch(PDOException $e) {
    // En cas d'erreur technique (BDD coupée, etc.)
    $response = [
        "success" => false, 
        "message" => "Erreur SQL : " . $e->getMessage()
    ];
}

echo json_encode($response);
?>