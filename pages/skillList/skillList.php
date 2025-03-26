<?php
    session_start();

    if (!isset($_SESSION['skills'])) {
        die("[ERRORE] Nessuna skill trovata.");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Skill Amministratore</title>
    </head>
    <body>
        <div>
            <h1>Dati Profilo Amministratore</h1>
        </div>
        <div>
            <h2>Competenze</h2>
        <table>
            <tr>
                <th>Skill</th>
                <th>Livello</th>
            </tr>
            <?php foreach ($_SESSION['skills'] as $skill): ?>
                <tr>
                    <td><?php echo htmlspecialchars($skill['nomeskill']); ?></td>
                    <td><?php echo htmlspecialchars($skill['livello']); ?></td>
                </tr>
            <?php endforeach; ?>
    </table>
        </div>
    </body>
</html>