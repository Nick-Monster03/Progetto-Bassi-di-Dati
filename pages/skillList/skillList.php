<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Competenze</title>
    <link href="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./skillList.css">
</head>
<body>
    <div class="container mt-3 w-50">
        <a href="../home/home.php" class="btn-home">Torna alla Home</a>
        <div class="header m-4">
            <img src="/logo/bostarter_trasparente.png" alt="Logo" class="logo">
            <h2 class="mt-2"><strong>Gestione Competenze</strong></h2>
        </div>

        <form action="skillListController.php" method="POST">
            <div class="form-group">
                <label for="competenzeDisponibili">Seleziona Competenza</label>
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
                <div class="form-group">
                    <label>Livello</label>
                    <div class="btn-group" role="group" aria-label="Livello">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <button type="button" class="btn-level" data-value="<?php echo $i; ?>"><?php echo $i; ?></button>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="level" id="selectedLevel" value="">
                </div>
                <button type="submit" class="btn-submit">Aggiungi</button>

        </form>

        <div class="skills-list">
            <h2>Le tue Competenze</h2>
            <div class="row">
                <?php
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    $email = $_SESSION['email'];
                    $usersSkills = getSkillsUser($email);
                    if (empty($usersSkills)) {
                        echo "<div class='alert'>Nessuna competenza trovata.</div>";
                    } else {
                        foreach($usersSkills as $skill) {
                            echo "<div class='card'>";
                            echo "<h5 class='card-title'>" . htmlspecialchars($skill['nomeskill']) . "</h5>";
                            echo "<p class='card-text'>Livello: " . htmlspecialchars($skill['livello']) . "</p>";
                            echo "</div>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</body>
<script>
    document.querySelectorAll('.btn-level').forEach(button => {
        button.addEventListener('click', function () {
            document.querySelectorAll('.btn-level').forEach(btn => btn.classList.remove('selected'));
            this.classList.add('selected');
            document.getElementById('selectedLevel').value = this.getAttribute('data-value');
        });
    });
</script>
</html>