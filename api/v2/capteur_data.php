<?php
require '../APIdbconfig.php';

// Récupération des données du client de l'API
$Key = isset($_GET['Key']) ? $_GET['Key'] : '';
$Temp = isset($_GET['Temp']) ? $_GET['Temp'] : '';
$Hr = isset($_GET['Hr']) ? $_GET['Hr'] : '';
$Date_heure = date("Y-m-d H:i:s");

// Préparez la réponse JSON
$response = array();

// Lire la température consignée dans le fichier log
$logFile = '../../log/chauffage.log';
if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    $logData = json_decode($logContent, true);
    $logTemp = isset($logData['wanted_temperature']) ? floatval($logData['wanted_temperature']) : 0;
    $currentHeaterState = isset($logData['heater_state']) ? $logData['heater_state'] : 'OFF';
} else {
    $logTemp = 0; // ou une autre valeur par défaut
    $currentHeaterState = 'OFF';
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("INSERT INTO Capteur (Temperature, Humidite, Date_heure) VALUES (:Temp, :Hr, :Date_heure)");
    $stmt->bindParam(':Temp', $Temp);
    $stmt->bindParam(':Hr', $Hr);
    $stmt->bindParam(':Date_heure', $Date_heure);
    $stmt->execute();

    // Ajoutez un message de succès et les données insérées à la réponse
    $response['status'] = 'success';
    $response['message'] = 'Données insérées avec succès';
    $response['data'] = [
        'Temperature' => $Temp,
        'Humidite' => $Hr,
        'Date_heure' => $Date_heure.'(UTC)'
    ];

    // Comparer la température reçue avec la température du log et envoyer la requête appropriée
    $newHeaterState = $currentHeaterState;
    if ($Temp < $logTemp) {
        file_get_contents('http://172.16.21.222:5000/check?param=1'); // requête ON
        $newHeaterState = 'ON';
    } else {
        file_get_contents('http://172.16.21.222:5000/check?param=0'); // requête OFF
        $newHeaterState = 'OFF';
    }

    // Mettre à jour le fichier log si l'état du chauffage a changé
    if ($newHeaterState !== $currentHeaterState) {
        $logData['heater_state'] = $newHeaterState;
        file_put_contents($logFile, json_encode($logData));
    }

} catch(PDOException $e) {
    // Ajoutez un message d'erreur à la réponse
    $response['status'] = 'error';
    $response['message'] = 'Erreur de l\'API : ' . $e->getMessage();
}

// Définissez le type de contenu comme JSON
header('Content-Type: application/json');

// Encodez le tableau de réponse en JSON et renvoyez-le
echo json_encode($response);
?>
