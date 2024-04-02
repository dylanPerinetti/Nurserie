<?php 
  session_start();
  $title = "Données des Capteurs"
?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nurserie • Data</title>
  <link rel="stylesheet" href="style/styles.css">
  <link rel="stylesheet" href="style/theme-toggle.css">
  <link rel="stylesheet" href="style/navbar.css">
</head>
<body>
  <?php require 'header.php'; ?>
  <form action="" method="get">
    <select name="lignes" onchange="this.form.submit()">
      <option value="5" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 5) ? 'selected' : ''; ?>>5 lignes</option>
      <option value="10" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 10) ? 'selected' : ''; ?>>10 lignes</option>
      <option value="20" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 20) ? 'selected' : ''; ?>>20 lignes</option>
      <option value="50" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 50) ? 'selected' : ''; ?>>50 lignes</option>
      <option value="100" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 100) ? 'selected' : ''; ?>>100 lignes</option>
    </select>
  </form>

  <?php
  require 'dbconfig.php';

  $nombreDeLignes = isset($_GET['lignes']) ? (int) $_GET['lignes'] : 5;
  $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
  $start = ($page - 1) * $nombreDeLignes;

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlTotal = "SELECT COUNT(*) FROM capteur";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->execute();
    $totalLignes = $stmtTotal->fetchColumn();
    $totalPages = ceil($totalLignes / $nombreDeLignes);

    $stmt = $conn->prepare("SELECT id, Temperature, Humidite, Date_heure FROM capteur LIMIT :start, :nombreDeLignes");
    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':nombreDeLignes', $nombreDeLignes, PDO::PARAM_INT);
    $stmt->execute();

    echo "<table>";
    echo "<thead><tr><th>ID</th><th>Température</th><th>Humidité</th><th>Date et Heure</th></tr></thead>";
    echo "<tbody>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>".$row["id"]."</td><td>".$row["Temperature"]."&deg;C</td><td>".$row["Humidite"]."% Hr</td><td>".$row["Date_heure"]."</td></tr>";
    }

    echo "</tbody>";
    echo "</table>";

    // Pagination
    for ($i = 1; $i <= $totalPages; $i++) {
      echo "<a href='?page=".$i."&lignes=".$nombreDeLignes."'>".$i."</a> ";
    }

  } catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
  }
  ?>

  <script src="script/theme-toggle.js"></script>
  <script src="script/error.js"></script>
</body>
</html>
