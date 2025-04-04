<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Profili per il progetto <?= htmlspecialchars($nomeProgetto) ?></title>
    <style>
        table {
            border-collapse: collapse;
            width: 70%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <?php
        include "richiestaCandidaturaController.php";
    ?>
    <h2 style="text-align: center;">Profili richiesti per il progetto "<?= htmlspecialchars($nomeProgetto) ?>"</h2>

    <?php if (count($profili) > 0): ?>
    <?php
    // Raggruppa le skill per nomeProfilo
    $gruppoProfili = [];
    foreach ($profili as $p) { 
        $gruppoProfili[$p['nomeProfilo']][] = new Competenza($p['nomeSkill'], $p['livelloRichiesto']);
    }
   

    ?>
   
            
    <tbody>
    <?php
    // Converto le competenze dell'utente in oggetti Competenza
    $competenzeUtente = [];
    foreach ($skill_utente as $su) {
        $competenzeUtente[] = new Competenza($su['nomeskill'], $su['livello']);
    }
    ?>

     <!-- PER DEBUG 
    <script>
        skillRichieste = <?= json_encode($gruppoProfili) ?>;
        competenzeUtente = <?= json_encode($competenzeUtente) ?>;

        console.log("Skill richieste:", skillRichieste);
        console.log("Skill utente:", competenzeUtente);
    </script> -->


    <!-- Mi vado a prendere l' array delle competenze associste per quel profilo -->
    <?php foreach ($gruppoProfili as $nomeProfilo => $competenze): ?>
        <?php
        // Verifica compatibilità, prende per ogni competenza richiesta da quel profilo 
        //e la confronta con quelle  possedute dal mio utente se c' è corrispondenza e il 
        //livello è maggiore o uguale allora risulterà passato e si passerà alla skill successiva
        $utenteCompatibile = true;
        foreach ($competenze as $cRichiesta) {
            $pass = false;
            foreach ($competenzeUtente as $cPosseduta) {
                if ($cRichiesta->equalOrUpper($cPosseduta)) {
                    $pass = true;
                    break;
                }
            }
            if (!$pass) {
                $utenteCompatibile = false;
                break;
            }
        }
        ?>
        <tr>
            <td><?= htmlspecialchars($nomeProfilo) ?></td>
            <td>
                <ul style="margin: 0; padding-left: 20px; list-style-type: disc;">
                    <?php foreach ($competenze as $c): ?>
                        <li><?= htmlspecialchars($c->getSkill()) ?> (livello: <?= $c->getLivello() ?>)</li>
                    <?php endforeach; ?>
                </ul>
            </td>
            <td style="vertical-align: middle;">
            <?php 
            session_start();
            $esito=checkCandidatura($nomeProfilo, $_SESSION["email"], $nomeProgetto);
            if ($utenteCompatibile): ?>
                <?php if ($esito === null): ?>
                    <!-- Nessuna candidatura trovata: mostra il form -->
                    <form action="richiestaCandidaturaController.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="nomeProfilo" value="<?= htmlspecialchars($nomeProfilo) ?>">
                        <input type="hidden" name="nomeProgettoSoftware" value="<?= htmlspecialchars($nomeProgetto) ?>">
                        <button type="submit">Candidati</button>
                    </form>
                    <?php else: ?>
                        <?php
                            // Imposta colore in base all'esito
                            switch ($esito) {
                                case 'accettata':
                                    $colore = 'green';
                                    break;
                                case 'rifiutata':
                                    $colore = 'red';
                                    break;
                                default: //quindi nonVista
                                    $colore = 'blue'; 
                            }
                        ?>

                        <label style="font-weight: bold; color: <?= $colore ?>;">
                            Esito candidatura: <?= htmlspecialchars($esito) ?>
                        </label>
                        <br>
                    <?php endif; ?>

            <?php else: ?>
                <span style="color: red; font-weight: bold;">Non idoneo</span>
            <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
    <?php else: ?>
        <p style="text-align: center;">Nessun profilo definito per questo progetto.</p>
    <?php endif; ?>
<!-- 
    PER DEBUG 
    <?php if (count($skill_utente) > 0): ?>
        <h3>Le tue skill</h3>
        <ul>
            <?php foreach ($skill_utente as $skill): ?>
                <li>
                    <?= htmlspecialchars($skill['nomeskill']) ?> – livello: <?= htmlspecialchars($skill['livello']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Non hai ancora aggiunto skill al tuo curriculum.</p>
    <?php endif; ?> -->
    
</body>
</html>