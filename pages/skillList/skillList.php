<!DOCTYPE html>
<html>
    <head>
        <title>Skills</title>
    </head>
    <body>
        <div>
            <h1>Dati Profilo </h1>
        </div>
        <div>
            <h2>Competenze</h2>
            <form action="skillListController.php" method="POST">
                <select name="competenzeDisponibili" id="competenzeDisponibili">
                    <?php
                        require 'skillListController.php';
                        $skills = getSkills();
                        foreach ($skills as $skill) {
                            echo "<option value='" . htmlspecialchars($skill['nome']) . "'>" . htmlspecialchars($skill['nome']) . "</option>";
                        }
                    ?>
                </select>
                <input type="number" min="0" max="5" name="level" id="level">
                <button type="submit">Aggiungi</button>
            </form>
        </div>
        <div>
            <ul>
            <?php
                session_start();
                $email = $_SESSION['email'];
                $usersSkills = getSkillsUser($email);
                if (empty($usersSkills)) {
                echo "[INFO] Nessuna skill trovata per questo amministratore.";
                }
                foreach($usersSkills as $skill) {
                echo "<li>" . htmlspecialchars($skill['nomeskill']) . " - " . htmlspecialchars($skill['livello']) . "</li>";
                }  
            ?>
            </ul>
        </div>
        <p><a href="../home/home.php">Torna alla Home</a></p>
    </body>
</html>