<?php
session_start();
require 'dbconfig.php';

// Récupération des données du formulaire
$Getusername = $_POST['Getusername'];
$Getpassword = $_POST['Getpsw'];

// Ouvrir le fichier de log en mode append
$file = fopen("log/connection.log", "a");

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $conn->prepare("SELECT id FROM users WHERE Login = :Getusername AND Mot_de_passe = :Getpassword");
  $stmt->bindParam(':Getusername', $Getusername);
  
  // Idéalement, le mot de passe devrait être hashé
  // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  // $stmt->bindParam(':password', $hashedPassword);
  
  // Pour vérifier un mot de passe hashé :
  // if(password_verify($password, $hashedPassword)) {
  // ...
  // }
  $stmt->bindParam(':Getpassword', $Getpassword);
  $stmt->execute();

  if($stmt->rowCount() > 0) {
    // L'utilisateur existe
    $_SESSION['loggedin'] = true;
    $_SESSION['Getusername'] = $Getusername;
    // Écrire dans le fichier de log pour une connexion réussie
    fwrite($file, "Login réussi pour l'utilisateur : $Getusername - " . date("Y-m-d H:i:s") . PHP_EOL);
    header("Location: index.php");
    exit;
  } else {
    // L'utilisateur n'existe pas ou le mot de passe est incorrect
    $_SESSION['loggedin'] = false;
    // Écrire dans le fichier de log pour une tentative échouée, incluant le login et le mot de passe
    fwrite($file, "Tentative de connexion échouée pour l'utilisateur : $Getusername avec le mot de passe : $Getpassword - " . date("Y-m-d H:i:s") . PHP_EOL);
    header("Location: index.php?error=invalidcredentials");
    exit;
  }
} catch(PDOException $e) {
  echo "Erreur de connexion : " . $e->getMessage();
  // Écrire l'erreur de connexion dans le fichier de log
  fwrite($file, "Erreur de connexion pour l'utilisateur : $Getusername - " . $e->getMessage() . " - " . date("Y-m-d H:i:s") . PHP_EOL);
  exit;
} finally {
  // Fermer le fichier de log
  fclose($file);
}
?>

