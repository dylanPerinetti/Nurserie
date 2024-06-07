<?php
session_start();
$title = "Chauffage";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nurserie • <?=$title?></title>
  <link rel="stylesheet" href="style/styles.css">
  <link rel="stylesheet" href="style/navbar.css">
  <link rel="stylesheet" href="style/widget.css">
  <script src="script/theme-toggle.js" defer></script>
  <script src="script/chauffage.js" defer></script>
  <script src="script/error.js" defer></script>
</head>
<body>
  <?php require 'header.php'; ?>
  <?php include 'widget/widget_chauffage.php'; ?>
</body>
</html>
