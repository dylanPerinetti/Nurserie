<?php
// Connexion à la base de données
require 'dbconfig.php';

// Configuration de l'emplacement du fichier de log
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/log/widgetFail.log');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    error_log("Erreur de connexion : " . $e->getMessage());
    die('Erreur de connexion, veuillez vérifier le fichier de log pour plus de détails.');
}

// Tableau des mois
$months = [
    '01' => 'Janvier',
    '02' => 'Février',
    '03' => 'Mars',
    '04' => 'Avril',
    '05' => 'Mai',
    '06' => 'Juin',
    '07' => 'Juillet',
    '08' => 'Août',
    '09' => 'Septembre',
    '10' => 'Octobre',
    '11' => 'Novembre',
    '12' => 'Décembre'
];

// Récupération des photos et vidéos par année, mois, jour
try {
    $sql = "SELECT DATE_FORMAT(Date_heure, '%Y') AS year, DATE_FORMAT(Date_heure, '%m') AS month, DATE_FORMAT(Date_heure, '%d') AS day, Photo, Video FROM camera";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $media = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $media[$row['year']][$row['month']][$row['day']][] = [
            'photo' => $row['Photo'],
            'video' => $row['Video']
        ];
    }
} catch(PDOException $e) {
    error_log("Erreur lors de la récupération des médias : " . $e->getMessage());
    die('Erreur lors de la récupération des médias, veuillez vérifier le fichier de log pour plus de détails.');
}

function generate_breadcrumb($year = null, $month = null, $day = null, $months) {
    $breadcrumb = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
    $breadcrumb .= '<li class="breadcrumb-item"><a href="?">Home</a></li>';

    if ($year) {
        $breadcrumb .= "<li class='breadcrumb-item'><a href='?year=$year'>$year</a></li>";
    }
    if ($month) {
        $month_name = $months[$month];
        $breadcrumb .= "<li class='breadcrumb-item'><a href='?year=$year&month=$month'>$month_name</a></li>";
    }
    if ($day) {
        $breadcrumb .= "<li class='breadcrumb-item'><a href='?year=$year&month=$month&day=$day'>$day</a></li>";
    }

    $breadcrumb .= '</ol></nav>';
    return $breadcrumb;
}

$year = isset($_GET['year']) ? $_GET['year'] : null;
$month = isset($_GET['month']) ? $_GET['month'] : null;
$day = isset($_GET['day']) ? $_GET['day'] : null;

$breadcrumb = generate_breadcrumb($year, $month, $day, $months);

$directories = [];
$files = [];

if ($year && $month && $day) {
    $files = $media[$year][$month][$day] ?? [];
} elseif ($year && $month) {
    $directories = array_keys($media[$year][$month]);
} elseif ($year) {
    $directories = array_keys($media[$year]);
} else {
    $directories = array_keys($media);
}
?>
<div class="widget widget-wide" id="file-explorer-widget">
  <h2>Historique des médias</h2>
  <?=$breadcrumb?>
  <?php if ($directories): ?>
    <ul class="directory-list">
      <?php foreach ($directories as $dir): ?>
        <?php if ($year && $month): ?>
          <li>
            <img src="img/files-icon.png" alt="Files Icon" class="files-icon">
            <a href="?year=<?=$year?>&month=<?=$month?>&day=<?=$dir?>">Jour <?=$dir?></a>
          </li>
        <?php elseif ($year): ?>
          <li>
            <img src="img/files-icon.png" alt="Files Icon" class="files-icon">
            <a href="?year=<?=$year?>&month=<?=$dir?>"><?=$months[$dir]?></a>
          </li>
        <?php else: ?>
          <li>
            <img src="img/files-icon.png" alt="Files Icon" class="files-icon">
            <a href="?year=<?=$dir?>">Année <?=$dir?></a>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  <?php elseif ($files): ?>
    <div class="media-gallery">
      <?php foreach ($files as $file): ?>
        <?php if (!empty($file['photo'])): ?>
          <div class="media-wrapper">
            <img src="../<?=$file['photo']?>" alt="Photo">
            <div class="overlay">
              <a href="../<?=$file['photo']?>" download class="download-icon" title="Télécharger">
                &#x21E9;
              </a>
            </div>
          </div>
        <?php endif; ?>
        <?php if (!empty($file['video'])): ?>
          <div class="media-wrapper">
            <video controls>
              <source src="../<?=$file['video']?>" type="video/mp4">
              Votre navigateur ne supporte pas la balise vidéo.
            </video>
            <div class="overlay">
              <a href="../<?=$file['video']?>" download class="download-icon" title="Télécharger">
                &#x21E9;
              </a>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>Sélectionnez une année, un mois ou un jour pour voir les médias.</p>
  <?php endif; ?>
</div>
