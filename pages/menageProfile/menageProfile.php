<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Profilo</title>
</head>
<body>
    <?php
    include_once './menageProfileController.php';
        // Recupera le variabili dall'URL
        try {
            if (!isset($_GET['nome']) || !isset($_GET['nomeProgettoSoftware'])) {
                throw new Exception("C'è stato un errore con il profilo selezionato.", 44444);
            }
            $nomeProfilo = htmlspecialchars($_GET['nome']);
            $nomeProgettoSoftware = htmlspecialchars($_GET['nomeProgettoSoftware']);
            
            if(existed($nomeProfilo, $nomeProgettoSoftware) == false){
                throw new Exception("DATI NON TROVATI", 15666);
            }
        } catch (Exception $e) {
            $title = "ERRORE ". $e->getCode();
            mostraErrore($title, $e->getMessage(), "../home/home.php");
            exit();
        }
        //questi coockie servono per passare le variabili al controller
        setcookie('nomeProfilo', $nomeProfilo, time() + 3600, '/');
        setcookie('nomeProgettoSoftware', $nomeProgettoSoftware, time() + 3600, '/');

    ?>
    <a href="../newProject/projectSoftware/newProjectSoftware.php"
   style="position: absolute; top: 10px; right: 10px; font-size: 16px; background-color: #4CAF50; color: white; padding: 10px; text-decoration: none; border-radius: 5px;">
   Torna alla pagina precedente
    </a>
    <h1>Gestione Profilo</h1>
    <div style="display: flex; gap: 40px; align-items: center; font-size: 1.5em;">
        <p><strong>Nome:</strong> <?php echo $nomeProfilo; ?></p>
        <p><strong>Nome Progetto Software:</strong> <?php echo $nomeProgettoSoftware; ?></p>
    </div>

    <?php
        try {
                $pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'root', 'changeme');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $query = 'SELECT nome FROM SKILL';
                $res=$pdo->prepare($query);
                $res->execute();
                $competenze= $res->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $title =  "[ERRORE] Database non accessibile: " . $e->getCode();
                mostraErrore($title, $e->getMessage(), '../home/home.php');
                exit();
            }
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
        try {
            $sql = "SELECT nomeSkill, livelloRichiesto FROM PROFILO_SKILL WHERE nomeProfilo = :nome AND nomeProgettoSoftware = :progetto";
            $res=$pdo->prepare($sql);
            $res->bindValue(":nome",$nomeProfilo);
            $res->bindValue(":progetto",$nomeProgettoSoftware);
            $res->execute();
            $skills = $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $title =  "[ERRORE] Database non accessibile: " . $e->getCode();
            mostraErrore($title, $e->getMessage(), '../home/home.php');
            exit();
        }
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