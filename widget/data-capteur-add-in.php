<?php
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

$conn = getConnection($servername, $dbname, $username, $password);

// Définitions par défaut des variables
$nombreDeLignes = isset($_GET['lignes']) ? $_GET['lignes'] : 10;
$nombreDePoints = isset($_GET['points']) ? $_GET['points'] : 5;
$nombreDePointsTemperature = isset($_GET['pointsTemperature']) ? $_GET['pointsTemperature'] : 5;
$nombreDePointsHumidite = isset($_GET['pointsHumidite']) ? $_GET['pointsHumidite'] : 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $nombreDeLignes;
?>
