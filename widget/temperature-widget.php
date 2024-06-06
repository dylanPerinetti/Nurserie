<div class="widget" id="temperature-widget">
    <label for="pointsTemperature">Points sur le graphique de température :</label>
    <select name="pointsTemperature" id="pointsTemperature" onchange="this.form.submit()">
        <option value="5" <?php echo (isset($_GET['pointsTemperature']) && $_GET['pointsTemperature'] == 5) ? 'selected' : ''; ?>>5 points</option>
        <option value="10" <?php echo (isset($_GET['pointsTemperature']) && $_GET['pointsTemperature'] == 10) ? 'selected' : ''; ?>>10 points</option>
        <option value="20" <?php echo (isset($_GET['pointsTemperature']) && $_GET['pointsTemperature'] == 20) ? 'selected' : ''; ?>>20 points</option>
        <option value="50" <?php echo (isset($_GET['pointsTemperature']) && $_GET['pointsTemperature'] == 50) ? 'selected' : ''; ?>>50 points</option>
        <option value="100" <?php echo (isset($_GET['pointsTemperature']) && $_GET['pointsTemperature'] == 100) ? 'selected' : ''; ?>>100 points</option>
        <option value="250" <?php echo (isset($_GET['pointsTemperature']) && $_GET['pointsTemperature'] == 250) ? 'selected' : ''; ?>>250 points</option>
        <option value="500" <?php echo (isset($_GET['pointsTemperature']) && $_GET['pointsTemperature'] == 500) ? 'selected' : ''; ?>>500 points</option>
        <option value="1000" <?php echo (isset($_GET['pointsTemperature']) && $_GET['pointsTemperature'] == 1000) ? 'selected' : ''; ?>>1000 points</option>
        <option value="all" <?php echo (isset($_GET['pointsTemperature']) && $_GET['pointsTemperature'] == 'all') ? 'selected' : ''; ?>>Tous les points</option>
    </select>
    <?php
    if (isset($_GET['pointsTemperature']) && $_GET['pointsTemperature'] == 'all') {
        $stmtChartTemperature = $conn->prepare("SELECT Temperature, Date_heure FROM capteur ORDER BY id DESC");
    } else {
        $nombreDePointsTemperature = isset($_GET['pointsTemperature']) ? $_GET['pointsTemperature'] : 5;
        $stmtChartTemperature = $conn->prepare("SELECT Temperature, Date_heure FROM capteur ORDER BY id DESC LIMIT :nombreDePointsTemperature");
        $stmtChartTemperature->bindParam(':nombreDePointsTemperature', $nombreDePointsTemperature, PDO::PARAM_INT);
    }
    $stmtChartTemperature->execute();

    $temperatureData = [];
    $labels = [];

    while ($row = $stmtChartTemperature->fetch(PDO::FETCH_ASSOC)) {
        $temperatureData[] = $row["Temperature"];
        $labels[] = $row["Date_heure"];
    }

    $temperatureData = array_reverse($temperatureData);
    $labels = array_reverse($labels);
    ?>
    <canvas id="temperatureChart" width="400" height="200"></canvas>
    <script>
        var ctx = document.getElementById('temperatureChart').getContext('2d');
        var temperatureChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Température (°C)',
                    data: <?php echo json_encode($temperatureData); ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
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
