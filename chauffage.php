<?php 
  session_start();
    $title = "Chauffage"
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nurserie • Chauffage</title>
  <link rel="stylesheet" href="style/styles.css">
  <link rel="stylesheet" href="style/theme-toggle.css">
  <link rel="stylesheet" href="style/navbar.css">
  <link rel="stylesheet" href="style/widget.css">
</head>
<body>
  <?php require 'header.php'; ?>
  <div id="thermostat-widget">
    <div id="header">
      <span id="location">Salle 1</span>
    </div>
    <div id="temperature-display">
      <span id="current-temp">Température : 18.7°C</span>
      <button id="decrease-temp">-</button>
      <span id="wanted-temp">19.0°C</span>
      <button id="increase-temp">+</button>
    </div>
    <div id="controls">
      <button id="off-temp">OFF</button>
      <button id="on-temp">ON</button>
    </div>
    <div id="modes">
      <button id="eco">Eco</button>
      <button id="comfort">comfort</button>
      <button id="boost">boost</button>
    </div>
  </div>
  <script src="script/widget-chauffage.js"></script>
  <script src="script/theme-toggle.js"></script>
  <script src="script/error.js"></script>
</body>
</html>
