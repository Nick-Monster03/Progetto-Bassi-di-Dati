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

        .btn {
            background-color: #0a899a !important;
            border: none !important;
        }
        .btn:hover {
            background-color: #1aa9b2 !important;
        }

        footer {
            width: 100%;
            background: linear-gradient(135deg, #e0f7fa, #b3eafb);
            color: #0a899a;
            padding: 20px 0;
            text-align: center;
            font-weight: bold;
            position: relative;
        }
    </style>
</head>
<body>
    <?php
        include "richiestaCandidaturaController.php";
    ?>
    <h2 style="text-align: center;">Profili richiesti per il progetto "<?= htmlspecialchars($nomeProgetto) ?>"</h2>
    <div style="position: absolute; top: 20px; right: 20px;">
        <button class="btn btn-primary" onclick="window.location.href='../item/item.php?nome=<?= urlencode($nomeProgetto) ?>';" style="padding: 10px; color: white; cursor: pointer; border-radius: 5px;">Torna Indietro</button>
    </div>
    <?php if (count($profili) > 0): ?>
        <?php
        // Raggruppa le skill per nomeProfilo
        $gruppoProfili = [];
        foreach ($profili as $p) { 
            $gruppoProfili[$p['nomeProfilo']][] = new Competenza($p['nomeSkill'], $p['livelloRichiesto']);
        }
    ?>

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
        </script>
    -->


    <!-- Mi vado a prendere l' array delle competenze associste per quel profilo -->
    <?php foreach ($gruppoProfili as $nomeProfilo => $competenze): ?>
        <?php
            //Verifica compatibilità, prende per ogni competenza richiesta da quel profilo 
            //e la confronta con quelle  possedute dal mio utente se c' è corrispondenza e il 
            //livello è maggiore o uguale allora risulterà passato e si passerà alla skill successiva.
            //il flag $utenteCompatibile è un flag che ci dice che l' utente è compatibile per il profilo
            //indicato da $nomeProfilo
            $utenteCompatibile = true;
            foreach ($competenze as $cRichiesta) {
                $pass = false;
                foreach ($competenzeUtente as $cPosseduta) {
                    //cRichiesta e cPosseduta sono oggetti Competenza e per confrontarli
                    //userò la funzione equalOrUpper che ho definito nella classe Competenza
                    if ($cRichiesta->equalOrUpper($cPosseduta)) {
                        $pass = true;
                        break;
                    }
                }
                //Se una sola delle skill di quel profilo non è posseduta allora l' utente non è compatibile
                //per quel profilo e con il break esciuma dal secondo ciclo per for-each
                //e andremo a selezionare un' altro profilo e verificheremo la compatibilità 
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
                <?php if ($esito == null || $esito ==''): ?>
                    <!-- Nessuna candidatura trovata: mostra il form -->
                    <form action="richiestaCandidaturaController.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="nomeProfilo" id="nomeProfilo" value="<?= htmlspecialchars($nomeProfilo) ?>">
                        <input type="hidden" name="nomeProgettoSoftware" id="nomeProgetto" value="<?= htmlspecialchars($nomeProgetto) ?>">
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

    <footer class="py-4 mt-5">
        <div class="container text-center">
            <img src="/logo/bostarter_trasparente.png" alt="Bostarter Logo" class="logo pb-3 pt-3">
            <p>&copy; 2025 Bostarter. Tutti i diritti riservati.</p>
        </div>
    </footer>
    
</body>
</html>