<?php
// Fichier : api/api.php
header('Content-Type: application/json');
require_once 'db_connect.php';

// Récupération de l'ID (défaut 1)
$id_station = isset($_GET['id']) ? $_GET['id'] : '01';

$response = ["status" => "error", "message" => "Pas de données"];

try {
    // CORRECTION ICI : On utilise 'station_id' au lieu de 'id_station'
    $sql = "SELECT * FROM mesures WHERE station_id = :id ORDER BY date_heure DESC LIMIT 10";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_station]);
    $historique = $stmt->fetchAll();

    if ($historique && count($historique) > 0) {
        // La mesure la plus récente est la première (index 0)
        $lastMesure = $historique[0];

        // Calcul Note
        $note = 10;
        $temp = floatval($lastMesure['temperature']);
        $hum  = floatval($lastMesure['humidite']);
        $lum  = intval($lastMesure['luminosite']);

        // Algo de notation
        if ($temp < 5 || $temp > 30) $note -= 4;
        elseif ($temp < 10 || $temp > 25) $note -= 2;
        
        if ($hum > 80) $note -= 5;
        elseif ($hum > 60) $note -= 2;
        
        if ($lum < 100) $note -= 1;
        
        $note = max(0, min(10, $note));

        // Message
        $conditionTexte = "Conditions Moyennes";
        if ($note >= 8) $conditionTexte = "Conditions Idéales !";
        else if ($note <= 4) $conditionTexte = "Restez chez vous...";

        $response = [
            "status" => "success",
            "current" => [
                "temperature" => $temp,
                "humidite" => $hum,
                "luminosite" => $lum,
                "note" => $note,
                "message" => $conditionTexte,
                "date" => $lastMesure['date_heure']
            ],
            "history" => $historique
        ];
    } else {
        $response["message"] = "Aucune donnée trouvée pour la station ID " . $id_station;
    }

} catch(PDOException $e) {
    $response["message"] = "Erreur SQL : " . $e->getMessage();
}

echo json_encode($response);
?>