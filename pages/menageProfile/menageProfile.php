<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Profilo</title>
</head>
<body>
    <?php
        // Recupera le variabili dall'URL
        $nome = isset($_GET['nome']) ? htmlspecialchars($_GET['nome']) : 'Nome non specificato';
        $nomeProgettoSoftware = isset($_GET['nomeProgettoSoftware']) ? htmlspecialchars($_GET['nomeProgettoSoftware']) : 'Progetto non specificato';

        // Imposta i valori nei cookie
        setcookie('nome', $nome, time() + 3600, '/');
        setcookie('nomeProgettoSoftware', $nomeProgettoSoftware, time() + 3600, '/');
    ?>
    <a href="../newProject/projectSoftware/newProjectSoftware.php"
   style="position: absolute; top: 10px; right: 10px; font-size: 16px; background-color: #4CAF50; color: white; padding: 10px; text-decoration: none; border-radius: 5px;">
   Torna alla pagina precedente
    </a>
    <h1>Gestione Profilo</h1>
    <div style="display: flex; gap: 40px; align-items: center; font-size: 1.5em;">
        <p><strong>Nome:</strong> <?php echo $nome; ?></p>
        <p><strong>Nome Progetto Software:</strong> <?php echo $nomeProgettoSoftware; ?></p>
    </div>

    <?php
   try {
    $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo("[ERRORE] Connessione al DB non riuscita. Errore: " . $e->getMessage());
    exit();
}

    $query = 'SELECT nome FROM SKILL';
    $res=$pdo->prepare($query);
    $res->execute();
    $competenze= $res->fetchAll(PDO::FETCH_ASSOC);
    ?>
        <h2>Competenze Disponibili</h2>
<ul>
    <form action="menageProfileController.php" method="POST" style="display: flex; flex-direction: column; gap: 10px;  width: 150px;">
        <div>
            <label for="competenze">Seleziona Skill:</label>
            <select name="competenze" id="competenze">
            <?php foreach ($competenze as $competenza): ?>
                <option value="<?php echo htmlspecialchars($competenza['nome']); ?>">
                <?php echo htmlspecialchars($competenza['nome']); ?>
                </option>
            <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="livello">Inserisci Livello:</label>
            <input type="number" id="livello" name="livello" min="0" max="5">
        </div>
        <button type="submit">Invia</button>
    </form>
 
</ul>
    <?php
    // Query per selezionare le righe corrispondenti
    $sql = "SELECT nomeSkill, livelloRichiesto FROM PROFILO_SKILL WHERE nomeProfilo = :nome AND nomeProgettoSoftware = :progetto";
    $res=$pdo->prepare($sql);
    $res->bindValue(":nome",$nome);
    $res->bindValue(":progetto",$nomeProgettoSoftware);
    $res->execute();
    $skills = $res->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h2>Skills</h2>
    <ul>
        <?php foreach ($skills as $skill): ?>
            <li>
                <strong>Skill:</strong> <?php echo htmlspecialchars($skill['nomeSkill']); ?>, 
                <strong>Livello Richiesto:</strong> <?php echo htmlspecialchars($skill['livelloRichiesto']); ?>
            </li>
        <?php endforeach; ?>
    </ul>

</body>
</html>