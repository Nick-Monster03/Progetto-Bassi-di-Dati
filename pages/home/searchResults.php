<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultati della Ricerca</title>
    <link rel="stylesheet" href="../../home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>
<body>
    <header>
        <div class="container d-flex flex-column align-items-center py-3">
            <h1 class="titleWebSite">Bostarter</h1>
        </div>
    </header>

    <div class="container mt-5">
        <div class="text-center mt-4">
            <button class="btn btn-secondary" onclick="window.history.back()">Torna Indietro</button>
        </div>
        <div class="row mt-4">
            <?php
            include('homeController.php');
            $query = $_GET['query'];
            $results = [];
            try {
                $res = $pdo->prepare("SELECT nome FROM Progetto WHERE nome LIKE :query LIMIT 10");
                $res->bindValue(":query", "%$query%");
                $res->execute();
                $results = $res->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "<p class='text-center text-danger'>Errore nella ricerca: " . $e->getMessage() . "</p>";
            }

            if (!empty($results)) {
                foreach ($results as $i => $project) {
                    $project_name = htmlspecialchars($project['nome']);
                    $project_link = "../item/item.php?nome=" . urlencode($project_name);
                    $card_colors = ['#f8f9fa', '#e9ecef', '#dee2e6', '#ced4da', '#adb5bd'];
            ?>
            <div class="col-12 col-md-6 mb-4">
                <div class="card" style="background-color: <?php echo $card_colors[$i % count($card_colors)]; ?>; cursor: pointer; transition: transform 0.3s;" onclick="window.location.href='<?php echo $project_link; ?>'">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $project_name; ?></h5>
                        <p class="card-text">Descrizione breve del progetto. Clicca per maggiori dettagli.</p>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p class='text-center'>Nessun progetto trovato.</p>";
            }
            ?>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2025 Bostarter. Tutti i diritti riservati.</p>
        </div>
    </footer>
</body>
</html>
