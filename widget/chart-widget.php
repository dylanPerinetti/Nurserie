<div class="widget" id="chart-widget">
    <label for="points">Points sur le graphique :</label>
    <select name="points" id="points" onchange="this.form.submit()">
        <option value="5" <?php echo (isset($_GET['points']) && $_GET['points'] == 5) ? 'selected' : ''; ?>>5 points</option>
        <option value="10" <?php echo (isset($_GET['points']) && $_GET['points'] == 10) ? 'selected' : ''; ?>>10 points</option>
        <option value="20" <?php echo (isset($_GET['points']) && $_GET['points'] == 20) ? 'selected' : ''; ?>>20 points</option>
        <option value="50" <?php echo (isset($_GET['points']) && $_GET['points'] == 50) ? 'selected' : ''; ?>>50 points</option>
        <option value="100" <?php echo (isset($_GET['points']) && $_GET['points'] == 100) ? 'selected' : ''; ?>>100 points</option>
        <option value="250" <?php echo (isset($_GET['points']) && $_GET['points'] == 250) ? 'selected' : ''; ?>>250 points</option>
        <option value="500" <?php echo (isset($_GET['points']) && $_GET['points'] == 500) ? 'selected' : ''; ?>>500 points</option>
        <option value="1000" <?php echo (isset($_GET['points']) && $_GET['points'] == 1000) ? 'selected' : ''; ?>>1000 points</option>
        <option value="all" <?php echo (isset($_GET['points']) && $_GET['points'] == 'all') ? 'selected' : ''; ?>>Tous les points</option>
    </select>
    <?php
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
</div>
