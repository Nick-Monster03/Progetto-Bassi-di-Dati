<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina di Candidatura</title>
    <style>
        .top-right {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
<?php
require 'candidaturaController.php';
$candidaturesByProject = getCandidaturesByProject();

foreach ($candidaturesByProject as $projectName => $candidatures) {
    echo "<h2>$projectName</h2>";
    
    if (!empty($candidatures)) {
        echo "<table border='1'>";
        echo "<tr><th>Nome Profilo</th><th>Email Utente</th><th>Esito</th><th>Azione</th></tr>";
        
        foreach ($candidatures as $candidature) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($candidature['nomeProfilo']) . "</td>";
            echo "<td>" . htmlspecialchars($candidature['emailUtente']) . "</td>";
            
            // Verifica lo stato dell'esito
            $esito = $candidature['esito'];
            
            // Se l'esito è 'nonVista', mostra i radio button, altrimenti mostra una label colorata
            if ($esito == 'nonVista') {
                // Se esito è 'nonVista', mostra i radio buttons e usa gli input nascosti per mandare una POST
                echo "<form action='./candidaturaController.php' method='POST'>";
                echo "<input type='hidden' name='emailUtente' value='" . htmlspecialchars($candidature['emailUtente']) . "'>";
                echo "<input type='hidden' name='nomeProgetto' value='" . htmlspecialchars($candidature['nomeProgettoSoftware']) . "'>";
                echo "<input type='hidden' name='nomeProfilo' value='" . htmlspecialchars($candidature['nomeProfilo']) . "'>";
                
                echo "<td>";
                echo "<input type='radio' name='esito' value='accettata' " . ($esito == 'accettata' ? 'checked' : '') . "> Accettata ";
                echo "<input type='radio' name='esito' value='rifiutata' " . ($esito == 'rifiutata' ? 'checked' : '') . "> Rifiutata";
                echo "</td>";
                
                // Pulsante per inviare il form
                echo "<td><button type='submit'>Aggiorna Esito</button></td>";
                echo "</form>";
            } else {
                // Se esito non è 'nonVista', mostra una label colorata
                if ($esito == 'accettata') {
                    echo "<td><label style='color: green;'>Accettata</label></td>";
                } elseif ($esito == 'rifiutata') {
                    echo "<td><label style='color: red;'>Rifiutata</label></td>";
                }
            }
            
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Non ci sono candidature per questo progetto.</p>";
    }
}
?>
<div class="top-right">
    <a href="../../pages/home/home.php">
        <button>Home</button>
    </a>
</div>
    
</body>
</html>