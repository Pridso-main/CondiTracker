<?php
// Fichier : api/admin_api.php
header('Content-Type: application/json');
require_once 'db_connect.php';

// On regarde quelle action est demandée (login ou getData)
$action = $_GET['action'] ?? '';
$response = ["status" => "error", "message" => "Action inconnue"];

try {

    // --- CAS 1 : TENTATIVE DE CONNEXION ---
    if ($action === 'login') {
        // On récupère les données envoyées par le formulaire JS
        $input = json_decode(file_get_contents('php://input'), true);
        $user = $input['username'] ?? '';
        $pass = $input['password'] ?? '';

        // Requête sécurisée pour vérifier le mot de passe
        // Note : On utilise MD5 car on l'a défini comme ça dans le script SQL précédent
        $sql = "SELECT id, identifiant FROM utilisateurs WHERE identifiant = :u AND mot_de_passe = MD5(:p)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['u' => $user, 'p' => $pass]);
        $admin = $stmt->fetch();

        if ($admin) {
            $response = ["status" => "success", "message" => "Connexion OK"];
        } else {
            $response = ["status" => "error", "message" => "Identifiant ou mot de passe incorrect"];
        }
    }

    // --- CAS 2 : RÉCUPÉRATION DES DONNÉES DASHBOARD ---
    elseif ($action === 'getData') {
        
        // A. Récupérer les LOGS
        $sqlLogs = "SELECT * FROM logs_systeme ORDER BY date_heure DESC LIMIT 20";
        $logs = $pdo->query($sqlLogs)->fetchAll();

        // B. Récupérer l'ÉTAT DES STATIONS (Dernière fois vue)
        // On regarde la dernière mesure pour chaque station
        $sqlStatus = "SELECT station_id, MAX(date_heure) as derniere_vue FROM mesures GROUP BY station_id";
        $stations = $pdo->query($sqlStatus)->fetchAll();

        $stationsStatus = [];
        foreach($stations as $s) {
            // Calcul si en ligne (Si la dernière mesure date de moins de 10 min)
            $lastSeen = strtotime($s['derniere_vue']);
            $now = time();
            $diffMinutes = ($now - $lastSeen) / 60;
            
            $isOnline = ($diffMinutes < 10); // Moins de 10 min = En ligne

            $stationsStatus[] = [
                "id" => $s['station_id'],
                "last_seen" => $s['derniere_vue'],
                "online" => $isOnline
            ];
        }

        $response = [
            "status" => "success",
            "data" => [
                "logs" => $logs,
                "stations" => $stationsStatus
            ]
        ];
    }

} catch(PDOException $e) {
    $response["message"] = "Erreur SQL : " . $e->getMessage();
}

echo json_encode($response);
?>