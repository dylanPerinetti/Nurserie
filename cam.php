<?php 
session_start();
$title = "Caméras";
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
  <script src="script/error.js" defer></script>
  <script src="script/cam.js" defer></script>
</head>
<body>
  <?php require 'header.php'; ?>
  <div class="widget-container">
    <?php require 'widget/widget_historique_photo.php'; ?>
    <?php require 'widget/widget_en_direct_video.php'; ?>
    <?php require 'widget/widget_recording_video.php'; ?>
  </div>
</body>
</html>
