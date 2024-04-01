<?php

session_start();
require 'dbconfig.php';

// Récupération des données du formulaire
$Getusername = $_POST['Getusername'];
$Getpassword = $_POST['Getpsw'];

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $conn->prepare("SELECT id FROM users WHERE Login = :Getusername AND Mot_de_passe = :Getpassword");
  $stmt->bindParam(':Getusername', $Getusername);
  
  // Idéalement, votre mot de passe devrait être hashé
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
    header("Location: index.php");
    exit;
  } else {
    // L'utilisateur n'existe pas ou le mot de passe est incorrect
    $_SESSION['loggedin'] = false;
    header("Location: index.php?error=invalidcredentials");
    exit;
  }
} catch(PDOException $e) {
  echo "Erreur de connexion : " . $e->getMessage();
  exit;
}
?>
