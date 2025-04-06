<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills</title>
    <link rel="stylesheet" href="../home.css">
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
        <a href="../home/home.php" class="btn btn-secondary">Torna alla Home</a>
        
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <h2>Competenze</h2>
                <form action="skillListController.php" method="POST">
                    <div class="mb-3">
                        <label for="competenzeDisponibili" class="form-label">Seleziona Competenza</label>
                        <select name="competenzeDisponibili" id="competenzeDisponibili" class="form-select">
                            <?php
                                require 'skillListController.php';
                                $skills = getSkills();
                                foreach ($skills as $skill) {
                                    echo "<option value='" . htmlspecialchars($skill['nome']) . "'>" . htmlspecialchars($skill['nome']) . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Livello</label>
                        <div class="btn-group" role="group" aria-label="Livello">
                            <?php for ($i = 0; $i <= 5; $i++): ?>
                                <input type="radio" class="btn-check" name="level" id="level<?php echo $i; ?>" value="<?php echo $i; ?>" autocomplete="off">
                                <label class="btn btn-outline-primary" for="level<?php echo $i; ?>"><?php echo $i; ?></label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Aggiungi</button>
                </form>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <h2>Le tue Competenze</h2>
                <div class="row">
                    <?php
                        if (session_status() == PHP_SESSION_NONE) {
                            session_start();
                        }
                        $email = $_SESSION['email'];
                        $usersSkills = getSkillsUser($email);
                        if (empty($usersSkills)) {
                            echo "<div class='col-12'><div class='alert alert-info'>[INFO] Nessuna skill trovata per questo amministratore.</div></div>";
                        } else {
                            foreach($usersSkills as $skill) {
                                echo "<div class='col-12 col-md-6 col-lg-4 mb-4'>";
                                echo "<div class='card'>";
                                echo "<div class='card-body'>";
                                echo "<h5 class='card-title'>" . htmlspecialchars($skill['nomeskill']) . "</h5>";
                                echo "<p class='card-text'>Livello: " . htmlspecialchars($skill['livello']) . "</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2025 Bostarter. Tutti i diritti riservati.</p>
        </div>
    </footer>
</body>
</html>