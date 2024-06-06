<div class="widget" id="humidite-widget">
    <label for="pointsHumidite">Points sur le graphique d'humidité :</label>
    <select name="pointsHumidite" id="pointsHumidite" onchange="this.form.submit()">
        <option value="5" <?php echo (isset($_GET['pointsHumidite']) && $_GET['pointsHumidite'] == 5) ? 'selected' : ''; ?>>5 points</option>
        <option value="10" <?php echo (isset($_GET['pointsHumidite']) && $_GET['pointsHumidite'] == 10) ? 'selected' : ''; ?>>10 points</option>
        <option value="20" <?php echo (isset($_GET['pointsHumidite']) && $_GET['pointsHumidite'] == 20) ? 'selected' : ''; ?>>20 points</option>
        <option value="50" <?php echo (isset($_GET['pointsHumidite']) && $_GET['pointsHumidite'] == 50) ? 'selected' : ''; ?>>50 points</option>
        <option value="100" <?php echo (isset($_GET['pointsHumidite']) && $_GET['pointsHumidite'] == 100) ? 'selected' : ''; ?>>100 points</option>
        <option value="250" <?php echo (isset($_GET['pointsHumidite']) && $_GET['pointsHumidite'] == 250) ? 'selected' : ''; ?>>250 points</option>
        <option value="500" <?php echo (isset($_GET['pointsHumidite']) && $_GET['pointsHumidite'] == 500) ? 'selected' : ''; ?>>500 points</option>
        <option value="1000" <?php echo (isset($_GET['pointsHumidite']) && $_GET['pointsHumidite'] == 1000) ? 'selected' : ''; ?>>1000 points</option>
        <option value="all" <?php echo (isset($_GET['pointsHumidite']) && $_GET['pointsHumidite'] == 'all') ? 'selected' : ''; ?>>Tous les points</option>
    </select>
    <?php
    if (isset($_GET['pointsHumidite']) && $_GET['pointsHumidite'] == 'all') {
        $stmtChartHumidite = $conn->prepare("SELECT Humidite, Date_heure FROM capteur ORDER BY id DESC");
    } else {
        $nombreDePointsHumidite = isset($_GET['pointsHumidite']) ? $_GET['pointsHumidite'] : 5;
        $stmtChartHumidite = $conn->prepare("SELECT Humidite, Date_heure FROM capteur ORDER BY id DESC LIMIT :nombreDePointsHumidite");
        $stmtChartHumidite->bindParam(':nombreDePointsHumidite', $nombreDePointsHumidite, PDO::PARAM_INT);
    }
    $stmtChartHumidite->execute();

    $humiditeData = [];
    $labels = [];

    while ($row = $stmtChartHumidite->fetch(PDO::FETCH_ASSOC)) {
        $humiditeData[] = $row["Humidite"];
        $labels[] = $row["Date_heure"];
    }

    $humiditeData = array_reverse($humiditeData);
    $labels = array_reverse($labels);
    ?>
    <canvas id="humiditeChart" width="400" height="200"></canvas>
    <script>
        var ctx = document.getElementById('humiditeChart').getContext('2d');
        var humiditeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
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
</div>
