<?php 
  session_start();
?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nurserie</title>
  <link rel="stylesheet" href="style/styles.css">
  <link rel="stylesheet" href="style/theme-toggle.css">
  <link rel="stylesheet" href="style/navbar.css">
</head>
<body>
  <!-- Logo -->
  <div class="logo-container">
    <img src="img/1-removebg-preview.png" alt="Logo BFP">
  </div>
  <!-- Navbar -->
  <div class="topnav">
    <a class="active" href="#home">Home</a>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
      <a href="cameras.php">Caméras</a>
      <div class="login-container">
        <form action="logout.php" method="post">
          <button type="submit">Déconnexion</button>
        </form>
      </div>
    <?php else: ?>
      <div class="login-container">
        <form action="login.php" method="post">
          <input type="text" placeholder="Username" name="Getusername" required>
          <input type="password" placeholder="Password" name="Getpsw" required>
          <button type="submit">Connexion</button>
        </form>
        <div id="errorMessage" style="display:none; color:red; text-align:center;">
          Nom d’utilisateur ou mot de passe incorrect.
        </div>
      </div>

    <?php endif; ?>
  </div>


  <label class="switch">
    <input type="checkbox" id="darkModeToggle">
    <span class="slider round"></span>
  </label>

  <h1>Données des Capteurs</h1>

  <?php require 'datasensors.php'; ?>

  <script src="script/theme-toggle.js"></script>
  <script src="script/error.js"></script>
</body>
</html>
