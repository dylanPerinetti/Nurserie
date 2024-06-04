<?php 
session_start();
$title = "Caméras";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nurserie • Caméras</title>
    <link rel="stylesheet" href="style/styles.css">
    <link rel="stylesheet" href="style/theme-toggle.css">
    <link rel="stylesheet" href="style/navbar.css">
    <link rel="stylesheet" href="style/calendar.css">
</head>
<body>
    <?php require 'header.php'; ?>

    <iframe width="560" height="315" src="https://www.youtube.com/embed/dIChLG4_WNs?si=6DViMsdKNRjalpyI&amp;controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

    <button>ON</button>
    <button>OFF</button>
    <script src="script/theme-toggle.js"></script>
    <script src="script/error.js"></script>
</body>
</html>
