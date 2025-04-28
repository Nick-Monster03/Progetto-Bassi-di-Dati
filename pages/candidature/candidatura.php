<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina di Candidatura</title>
    <link rel="stylesheet" href="../home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../home/home.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>
<body class="bg-light">
    <div class="container d-flex flex-column align-items-center py-3">
        <img src="/logo/bostarter_trasparente.png" alt="Bostarter Logo" class="logo">
    </div>
    <div class="container bg-white w-75 border border-3 rounded-3 shadow-sm p-4">
        <?php
        require 'candidaturaController.php';
        $candidaturesByProject = getCandidaturesByProject();

        foreach ($candidaturesByProject as $projectName => $candidatures) {
            echo "<h2 class='titoli mb-4 text-center'>$projectName</h2>";
            echo "<hr class='custom-line'>";

            if (!empty($candidatures)) {
                echo "<table class='table-rounded text-center mx-auto w-75'>";
                echo "<thead class='table-secondary'><tr><th>Nome Profilo</th><th>Email Utente</th><th>Esito</th><th>Azione</th></tr></thead>";
                echo "<tbody>";

                foreach ($candidatures as $candidature) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($candidature['nomeProfilo']) . "</td>";
                    echo "<td>" . htmlspecialchars($candidature['emailUtente']) . "</td>";

                    $esito = $candidature['esito'];

                    if ($esito == 'nonVista') {
                        echo "<form action='./candidaturaController.php' method='POST'>";
                        echo "<input type='hidden' name='emailUtente' value='" . htmlspecialchars($candidature['emailUtente']) . "'>";
                        echo "<input type='hidden' name='nomeProgetto' value='" . htmlspecialchars($candidature['nomeProgettoSoftware']) . "'>";
                        echo "<input type='hidden' name='nomeProfilo' value='" . htmlspecialchars($candidature['nomeProfilo']) . "'>";

                        echo "<td>";
                        echo "<input type='radio' name='esito' value='accettata' " . ($esito == 'accettata' ? 'checked' : '') . "> Accettata ";
                        echo "<input type='radio' name='esito' value='rifiutata' " . ($esito == 'rifiutata' ? 'checked' : '') . "> Rifiutata";
                        echo "</td>";

                        echo "<td><button type='submit' class='btn btn-primary'>Aggiorna Esito</button></td>";
                        echo "</form>";
                    } else {
                        if ($esito == 'accettata') {
                            echo "<td><label class='text-success'>Accettata</label></td>";
                        } elseif ($esito == 'rifiutata') {
                            echo "<td><label class='text-danger'>Rifiutata</label></td>";
                        }
                    }

                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>Non ci sono candidature per questo progetto.</p>";
            }
        }
        ?>
        <div class="text-center mt-4">
            <a href="../../pages/home/home.php" class="btn btn-secondary">Home</a>
        </div>
    </div>

    <footer class="py-4 mt-5">
        <div class="container text-center">
            <img src="/logo/bostarter_trasparente.png" alt="Bostarter Logo" class="logo pb-3 pt-3">
            <p>&copy; 2025 Bostarter. Tutti i diritti riservati.</p>
        </div>
    </footer>
</body>
</html>