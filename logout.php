<?php
session_start(); // Démarre la gestion de session
session_unset(); // Retire toutes les variables de session
session_destroy(); // Détruit la session
header("Location: index.php"); // Redirige vers la page d'accueil
exit();
?>
