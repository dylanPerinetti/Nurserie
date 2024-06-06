<?php
// Configuration de l'emplacement du fichier de log
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/log/widgetFail.log');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error_message = file_get_contents('php://input');
    error_log($error_message);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
