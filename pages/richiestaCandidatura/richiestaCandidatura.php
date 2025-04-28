<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Profili per il progetto <?= htmlspecialchars($nomeProgetto) ?></title>
    <style>
    body {
      font-family: 'Cinzel', serif;
      background: linear-gradient(135deg, #e0f7fa, #b3eafb);
      margin: 0;
      padding: 40px;
      text-align: center;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h2 {
      color: #0a899a;
      font-size: 2.5rem;
      margin-bottom: 30px;
    }

    .profile-list {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 600px;
      text-align: left;
    }

    .profile-item {
      margin-bottom: 20px;
      font-size: 1.1rem;
      color: #333;
    }

    .not-suitable {
      color: red;
      font-weight: bold;
    }

    .outcome {
      margin-top: 20px;
      font-size: 1.3rem;
      font-weight: bold;
    }

    .accepted {
      color: #0a899a;
    }

    .rejected {
      color: red;
    }

    button {
      background-color: #0a899a;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      cursor: pointer;
      margin-top: 20px;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #1aa9b2;
    }

    ul {
      list-style-type: disc;
      padding-left: 20px;
      margin-top: 5px;
    }

    @font-face {
      font-family: 'Cinzel';
      src: url('./font/Cinzel-Regular.ttf') format('truetype');
    }

    @media (max-width: 500px) {
      .profile-list {
        padding: 20px;
      }

      h2 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
    <?php
        include "richiestaCandidaturaController.php";
    ?>
    <h2 style="text-align: center;">Profili richiesti per il progetto "<?= htmlspecialchars($nomeProgetto) ?>"</h2>
    <div style="position: absolute; top: 20px; right: 20px;">
        <button onclick="window.location.href='../item/item.php?nome=<?= urlencode($nomeProgetto) ?>';" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Torna Indietro</button>
    </div>
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
            <!-- Se l'utente è compatibile e non ha già fatto richiesta per quel profilo allora può candidarsi
            in caso contrario gli verrà mostrato l' esito della candidatura (accettata, rifiutata o non vista) -->
                <?php if ($esito == null || $esito ==''): ?>
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