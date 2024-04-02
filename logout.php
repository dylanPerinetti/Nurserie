<?php
session_start(); // Démarre la gestion de session

// Ouvrir le fichier de log en mode append
$file = fopen("connection_log.txt", "a");

// Vérifier si un utilisateur est actuellement connecté pour logger sa déconnexion
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Assumer que $_SESSION['username'] contient le nom d'utilisateur. 
    // Assurez-vous que cette variable est définie lors de la connexion.
    $Getusername = $_SESSION['Getusername']; // Vous devez définir cette variable lors de la connexion
    fwrite($file, "Déconnexion de l'utilisateur : $Getusername - " . date("Y-m-d H:i:s") . PHP_EOL);
}

session_unset(); // Retire toutes les variables de session
session_destroy(); // Détruit la session
fclose($file); // Fermer le fichier de log

header("Location: index.php"); // Redirige vers la page d'accueil
exit();
?>
