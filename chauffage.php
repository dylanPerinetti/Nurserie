<?php
session_start();
$title = "Chauffage";
require 'dbconfig.php';

function getConnection($servername, $dbname, $username, $password) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        exit;
    }
}

function getLatestTemperature($conn) {
    $sqltemp = "SELECT Temperature FROM capteur ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($sqltemp);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['Temperature'] : 'N/A';
}

function getLogData() {
    $logFile = 'log/chauffage.log';
    if (file_exists($logFile)) {
        return json_decode(file_get_contents($logFile), true);
    }
    return ['wanted_temperature' => 'N/A', 'heater_state' => 'OFF'];
}

function logStatus($temp, $state) {
    $logFile = 'log/chauffage.log';
    $logData = ['wanted_temperature' => $temp, 'heater_state' => $state];
    file_put_contents($logFile, json_encode($logData));
}

$conn = getConnection($servername, $dbname, $username, $password);
$temperature = getLatestTemperature($conn) ?? 'N/A';
$logData = getLogData();
$wantedTemp = $logData['wanted_temperature'] ?? 'N/A';
$heaterState = $logData['heater_state'] ?? 'OFF';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['wanted_temp'])) {
        $currentState = file_exists('log/chauffage.log') ? json_decode(file_get_contents('log/chauffage.log'))->heater_state : 'OFF';
        logStatus($data['wanted_temp'], $currentState);
        echo json_encode(['success' => true]);
        exit;
    } elseif (isset($data['state'])) {
        $currentTemp = file_exists('log/chauffage.log') ? json_decode(file_get_contents('log/chauffage.log'))->wanted_temperature : $temperature;
        logStatus($currentTemp, $data['state']);
        echo json_encode(['success' => true ]);
        exit;
    } else {
        echo json_encode(['error' => 'Invalid data']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nurserie • Chauffage</title>
  <link rel="stylesheet" href="style/styles.css">
  <link rel="stylesheet" href="style/theme-toggle.css">
  <link rel="stylesheet" href="style/navbar.css">
</head>
<body>
  <?php require 'header.php'; ?>
  <div id="thermostat-widget">
    <div id="header">
      <span id="location">Salle 1</span>
    </div>
    <div id="temperature-display">
      <span id="current-temp">Température : <span id="temp-value"><?= htmlspecialchars($temperature) ?>°C</span></span>
      <button id="decrease-temp">-</button>
      <span id="wanted-temp"><?= htmlspecialchars($wantedTemp) ?>°C</span>
      <button id="increase-temp">+</button>
    </div>
    <div id="controls">
      <button id="off-temp">OFF</button>
      <button id="on-temp">ON</button>
    </div>
    <span id="heater-state" style="display: none;"><?= htmlspecialchars($heaterState) ?></span>
  </div>
  <script src="script/chauffage.js"></script>
  <script src="script/theme-toggle.js"></script>
  <script src="script/error.js"></script>
</body>
</html>
