<?php 
session_start();
$title = "Données des Capteurs";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nurserie • <?=$title?></title>
    <link rel="stylesheet" href="style/styles.css">
    <link rel="stylesheet" href="style/navbar.css">
    <script src="script/theme-toggle.js" defer></script>
    <script src="script/error.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php require 'header.php'; ?>
    <?php require 'widget/data-capteur-add-in.php'; ?>
    <form action="" method="get">
        <div class="widget-container">
            <?php include 'widget/table-widget.php'; ?>
            <?php include 'widget/chart-widget.php'; ?>
            <?php include 'widget/temperature-widget.php'; ?>
            <?php include 'widget/humidite-widget.php'; ?>
        </div>
    </form>
</body>
</html>
