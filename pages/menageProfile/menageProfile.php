<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Profilo</title>
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../skillList/skillList.css">
</head>
<body>
    <div class="container mt-3 w-50">
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
        <div class="text-center m-2">
            <img src="/logo/bostarter_trasparente.png" alt="Logo" class="logo">
        </div>
        <h2><strong>Gestione Profilo</strong></h2>
        <div class="w-75 p-0 bg-white border rounded shadow-sm mb-3">
            <ul class="list-group list-group-flush rounded">
                <li class="list-group-item"><p class="m-0"><strong>Nome:</strong> <?php echo $nomeProfilo; ?></p></li>
                <li class="list-group-item"><p class="m-0"><strong>Nome Progetto Software:</strong> <?php echo $nomeProgettoSoftware; ?></p></li>
            </ul>
        </div>
        <a class="btn" href="../newProject/projectSoftware/newProjectSoftware.php" style="color: white;">Indietro</a>
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
    <div class="container mt-3 w-50">
        <div class="text-center m-2">
            <img src="/logo/bostarter_trasparente.png" alt="Logo" class="logo">
        </div>
        <h2><strong>Competenze Disponibili</strong></h2>
        <div class="w-75 p-2 bg-white border rounded shadow-sm">
            <form action="menageProfileController.php" method="POST" style="display: flex; flex-direction: column; gap: 10px;  width: 150px;">
                <div class="form-group m-0">
                    <label class="form-label" for="competenze">Seleziona Skill:</label>
                    <select class="form-select m-0" name="competenze" id="competenze">
                    <?php foreach ($competenze as $competenza): ?>
                        <option value="<?php echo htmlspecialchars($competenza['nome']); ?>">
                        <?php echo htmlspecialchars($competenza['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="livello">Inserisci Livello:</label>
                    <input class="form-control" type="number" id="livello" name="livello" min="0" max="5">
                </div>
                <button class="btn" type="submit" style="color: white;">Invia</button>
            </form>
        </div>
    </div>
    <div class="container mt-3 pb-0 w-50">
        <div class="text-center m-2">
            <img src="/logo/bostarter_trasparente.png" alt="Logo" class="logo">
        </div>
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
    <h2><strong>Skills</strong></h2>
            <?php foreach ($skills as $skill): ?>
                <div class="w-75 p-0 bg-white border rounded shadow-sm mb-3">
                    <ul class="list-group list-group-flush rounded">
                        <li class="list-group-item">
                            <strong>Skill:</strong> <?php echo htmlspecialchars($skill['nomeSkill']); ?> 
                        </li>
                        <li class="list-group-item">
                            <strong>Livello Richiesto:</strong> <?php echo htmlspecialchars($skill['livelloRichiesto']); ?>
                        </li>
                    </ul>
                </div>
            <?php endforeach; ?>
</body>
</html>