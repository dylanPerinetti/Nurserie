<?php 
  session_start();
  $title = "Données des Capteurs";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nurserie • Data</title>
  <link rel="stylesheet" href="style/styles.css">
  <link rel="stylesheet" href="style/theme-toggle.css">
  <link rel="stylesheet" href="style/navbar.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <?php require 'header.php'; ?>
  <form action="" method="get">
    <div class="widget-container">
      <div class="widget" id="table-widget">
        <label for="lignes">Lignes du tableau :</label>
        <select name="lignes" id="lignes" onchange="this.form.submit()">
          <option value="5" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 5) ? 'selected' : ''; ?>>5 lignes</option>
          <option value="10" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 10) ? 'selected' : ''; ?>>10 lignes</option>
          <option value="20" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 20) ? 'selected' : ''; ?>>20 lignes</option>
          <option value="50" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 50) ? 'selected' : ''; ?>>50 lignes</option>
          <option value="100" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 100) ? 'selected' : ''; ?>>100 lignes</option>
        </select>
        <?php
          require 'dbconfig.php';

          $nombreDeLignes = isset($_GET['lignes']) ? $_GET['lignes'] : 5;
          $nombreDePoints = isset($_GET['points']) ? $_GET['points'] : 5;
          $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
          $start = ($page - 1) * $nombreDeLignes;

          try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Récupération des données pour le tableau
            $sqlTotal = "SELECT COUNT(*) FROM capteur";
            $stmtTotal = $conn->prepare($sqlTotal);
            $stmtTotal->execute();
            $totalLignes = $stmtTotal->fetchColumn();

            if ($nombreDeLignes == 'all') {
              $stmtTable = $conn->prepare("SELECT id, Temperature, Humidite, Date_heure FROM capteur ORDER BY id DESC");
            } else {
              $totalPages = ceil($totalLignes / (int)$nombreDeLignes);
              $stmtTable = $conn->prepare("SELECT id, Temperature, Humidite, Date_heure FROM capteur ORDER BY id DESC LIMIT :start, :nombreDeLignes");
              $stmtTable->bindParam(':start', $start, PDO::PARAM_INT);
              $stmtTable->bindParam(':nombreDeLignes', $nombreDeLignes, PDO::PARAM_INT);
            }
            $stmtTable->execute();

            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Température</th><th>Humidité</th><th>Date et Heure</th></tr></thead>";
            echo "<tbody>";

            while ($row = $stmtTable->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr><td>".$row["id"]."</td><td>".$row["Temperature"]."&deg;C</td><td>".$row["Humidite"]."% Hr</td><td>".$row["Date_heure"]." (UTC)</td></tr>";
            }

            echo "</tbody>";
            echo "</table>";

            if ($nombreDeLignes != 'all') {
              // Pagination
              for ($i = 1; $i <= $totalPages; $i++) {
                echo "<a href='?page=".$i."&lignes=".$nombreDeLignes."&points=".$nombreDePoints."'>".$i."</a> ";
              }
            }
          } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
          }
        ?>
      </div>

      <div class="widget" id="chart-widget">
        <label for="points">Points sur le graphique :</label>
        <select name="points" id="points" onchange="this.form.submit()">
          <option value="5" <?php echo (isset($_GET['points']) && $_GET['points'] == 5) ? 'selected' : ''; ?>>5 points</option>
          <option value="10" <?php echo (isset($_GET['points']) && $_GET['points'] == 10) ? 'selected' : ''; ?>>10 points</option>
          <option value="20" <?php echo (isset($_GET['points']) && $_GET['points'] == 20) ? 'selected' : ''; ?>>20 points</option>
          <option value="50" <?php echo (isset($_GET['points']) && $_GET['points'] == 50) ? 'selected' : ''; ?>>50 points</option>
          <option value="100" <?php echo (isset($_GET['points']) && $_GET['points'] == 100) ? 'selected' : ''; ?>>100 points</option>
          <option value="all" <?php echo (isset($_GET['points']) && $_GET['points'] == 'all') ? 'selected' : ''; ?>>Tous les points</option>
        </select>
        <?php
          try {
            // Récupération des données pour le graphique
            if ($nombreDePoints == 'all') {
              $stmtChart = $conn->prepare("SELECT Temperature, Humidite, Date_heure FROM capteur ORDER BY id DESC");
            } else {
              $stmtChart = $conn->prepare("SELECT Temperature, Humidite, Date_heure FROM capteur ORDER BY id DESC LIMIT :nombreDePoints");
              $stmtChart->bindParam(':nombreDePoints', $nombreDePoints, PDO::PARAM_INT);
            }
            $stmtChart->execute();

            $temperatureData = [];
            $humiditeData = [];
            $labels = [];

            while ($row = $stmtChart->fetch(PDO::FETCH_ASSOC)) {
              $temperatureData[] = $row["Temperature"];
              $humiditeData[] = $row["Humidite"];
              $labels[] = $row["Date_heure"];
            }

            // Inverser les données pour qu'elles soient dans l'ordre chronologique
            $temperatureData = array_reverse($temperatureData);
            $humiditeData = array_reverse($humiditeData);
            $labels = array_reverse($labels);
        ?>
        <canvas id="myChart" width="400" height="200"></canvas>
        <script>
          var ctx = document.getElementById('myChart').getContext('2d');
          var myChart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: <?php echo json_encode($labels); ?>,
              datasets: [{
                label: 'Température (°C)',
                data: <?php echo json_encode($temperatureData); ?>,
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                fill: false
              }, {
                label: 'Humidité (% Hr)',
                data: <?php echo json_encode($humiditeData); ?>,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                fill: false
              }]
            },
            options: {
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });
        </script>
        <?php
          } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
          }
        ?>
      </div>
    </div>
  </form>

  <script src="script/theme-toggle.js"></script>
  <script src="script/error.js"></script>
</body>
</html>
