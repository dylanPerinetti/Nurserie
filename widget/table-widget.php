<div class="widget" id="table-widget">
    <label for="lignes">Lignes du tableau :</label>
    <select name="lignes" id="lignes" onchange="this.form.submit()">
        <option value="10" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 10) ? 'selected' : ''; ?>>10 lignes</option>
        <option value="20" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 20) ? 'selected' : ''; ?>>20 lignes</option>
        <option value="50" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 50) ? 'selected' : ''; ?>>50 lignes</option>
        <option value="100" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 100) ? 'selected' : ''; ?>>100 lignes</option>
        <option value="250" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 250) ? 'selected' : ''; ?>>250 lignes</option>
        <option value="500" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 500) ? 'selected' : ''; ?>>500 lignes</option>
        <option value="1000" <?php echo (isset($_GET['lignes']) && $_GET['lignes'] == 1000) ? 'selected' : ''; ?>>1000 lignes</option>
    </select>
    <?php
    $nombreDeLignes = isset($_GET['lignes']) ? $_GET['lignes'] : 10;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $start = ($page - 1) * $nombreDeLignes;

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
        $visiblePages = 2; // Number of visible pages before and after current page
        $startPage = max(1, $page - $visiblePages);
        $endPage = min($totalPages, $page + $visiblePages);

        echo '<div class="pagination">';
        echo $page > 1 ? "<a href='?page=".($page-1)."&lignes=$nombreDeLignes'>Prev</a> " : "<a class='disabled'>Prev</a> ";

        if ($startPage > 1) {
            echo "<a href='?page=1&lignes=$nombreDeLignes'>1</a> ";
            if ($startPage > 2) {
                echo "<a href='?page=".($startPage-1)."&lignes=$nombreDeLignes'>".($startPage-1)."</a> ";
            }
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $page) {
                echo "<strong>$i</strong> ";
            } else {
                echo "<a href='?page=$i&lignes=$nombreDeLignes'>$i</a> ";
            }
        }

        if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
                echo "<a href='?page=".($endPage+1)."&lignes=$nombreDeLignes'>".($endPage+1)."</a> ";
            }
            echo "<a href='?page=$totalPages&lignes=$nombreDeLignes'>$totalPages</a> ";
        }

        echo $page < $totalPages ? "<a href='?page=".($page+1)."&lignes=$nombreDeLignes'>Next</a> " : "<a class='disabled'>Next</a> ";
        echo '</div>';
    }
    ?>
</div>
