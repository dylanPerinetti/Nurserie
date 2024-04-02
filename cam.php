<?php 
session_start();
$title = "Caméras";
// Définit le fuseau horaire par défaut, à adapter selon votre localisation
date_default_timezone_set('Europe/Paris');

// Récupère l'année et le mois actuels
$year = date('Y');
$month = date('m');

// Premier jour du mois
$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);

// Crée un formateur de date pour le nom du mois en français
$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 
                                    'Europe/Paris', IntlDateFormatter::GREGORIAN, 'MMMM');
$monthName = $formatter->format($firstDayOfMonth);

// Nombre de jours dans le mois
$daysInMonth = date('t', $firstDayOfMonth);

// Information sur le premier jour du mois
$dayOfWeek = date('N', $firstDayOfMonth) - 1;

// HTML du calendrier
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
    <div class="month">
        <ul>
            <li class="prev">&#10094;</li>
            <li class="next">&#10095;</li>
            <li><?= $monthName ?><br><span style="font-size:18px"><?= $year ?></span></li>
        </ul>
    </div>

    <ul class="weekdays">
        <li>Lun</li>
        <li>Mar</li>
        <li>Mer</li>
        <li>Jeu</li>
        <li>Ven</li>
        <li>Sam</li>
        <li>Dim</li>
    </ul>

    <ul class="days">
        <?php
        // Crée des espaces vides si le premier jour du mois n'est pas un lundi
        for ($i = 0; $i < $dayOfWeek; $i++) {
            echo "<li></li>";
        }

        // Génère les jours du mois
        for ($day = 1; $day <= $daysInMonth; $day++) {
            echo "<li>$day</li>";
        }
        ?>
    </ul>

    <script src="script/calendar.js"></script>
    <script src="script/theme-toggle.js"></script>
    <script src="script/error.js"></script>
</body>
</html>
