<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserisci Profili</title>
    <link rel="stylesheet" href="newProjectSoftware.css">
    <style>
        .top-right {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
<div class="top-right">
    <a href="../../home/home.php">
        <button>Home</button>
    </a>
</div>
<div class="container">
    <h2>Profili Skill per il progetto: <?php echo htmlspecialchars($nomeProgettoSoftware); ?></h2>
    <?php 
    require_once 'newProjectSoftwareController.php';
    $profili = Init();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    if (!empty($profili)) {
        echo '<table border="1">';
        echo '<tr><th>Nome Profilo</th><th>Nome Progetto Software</th></tr>';
        foreach ($profili as $profilo) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($profilo['nome']) . '</td>';
            echo '<td>' . htmlspecialchars($profilo['nomeProgettoSoftware']) . '</td>';
            echo '<td><a href="../../menageProfile/menageProfile.php?nome=' . urlencode($profilo['nome']) . '&nomeProgettoSoftware=' . urlencode($profilo['nomeProgettoSoftware']) . '">+ Gestione Profilo</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>Nessun profilo trovato.</p>';
    }
    ?>
</div>
<div class="container">
    <h1>Inserisci un nuovo profilo</h1>
    <form action="newProjectSoftwareController.php" method="post">
        <label for="profileName">Nome Profilo:</label>
        <input type="text" id="profileName" name="profileName" required><br><br>

        <!-- <label for="skillName">Nome Skill:</label>
        <input type="text" id="skillName" name="skillName" required><br><br>

        <label for="skillLevel">Livello Richiesto:</label>
        <input type="number" id="skillLevel" name="skillLevel" min="0" max="5" required><br><br> -->

        <button type="submit">Invia</button>
    </form>
</div>
</body>
</html>