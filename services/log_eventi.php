<?php
    function addLog($tipoInserimento, $newInsert) { //bisogna passare a questa funzione che tipo di nuiovo ato è stato inseirto e un oggetto con i dati da inserire nel log
        require 'vendor/autoload.php';  
        try {
            $client = new MongoDB\Client("mongodb://localhost:27017");
            // Seleziona il database
            $db = $client->Movimenti;
            $collection = $db->log_eventi;
            $timestamp = date("H:i d/m/Y");
            $descrizioneEvento = "";

            switch ($tipoInserimento) {
                case "nuovo_utente":
                    $descrizioneEvento = "Un nuovo utente è stato registrato: " . $newInsert->email . " con ruolo " . $newInsert->ruolo;
                    break;
                case "nuovo_progetto":
                    $descrizioneEvento = "Un nuovo progetto è stato creato: " . $newInsert->nome;
                    break;
                case "nuova_skill":
                    $descrizioneEvento = "Una nuova skill è stata aggiunta: " . $newInsert->nome;
                    break;
                case "nuovo_finanziamento":
                    $descrizioneEvento = "è stato aggiunto un nuovo progetto " . $newInsert->tipo . "\t" . $newInsert->nome . " dell'utente " . $newInsert->creatore;
                    break;
                case "nuova_candidatura":
                    $descrizioneEvento = "Un utente ha inviato una candidatura: per la figura di " . $newInsert->nomeProfilo . " del progetto " . $newInsert->nomeProgettoSoftware;
                    break;
                case "nuovo_commento":
                    $descrizioneEvento = "Un nuovo commento è stato aggiunto";
                    break;
                case "nuova_risposta":
                    $descrizioneEvento = "Una risposta a un commento è stata pubblicata: al commento " . $newInsert->id;
                    break;
                default:
                    $descrizioneEvento = "Evento non riconosciuto.";
            }
            $result = $collection->insertOne([
                "tipoInserimento" => $tipoInserimento,
                "descrizioneEvento" => $descrizioneEvento,
                "timestamp" => $timestamp
            ]);
            if ($result->getInsertedCount() == 0) {
               throw new Exception("Errore nell'inserimento del log.");
            }
        } catch (Exception $e) {
            echo "[ERRORE] Operazione non riuscita. Errore: " . $e->getMessage();
        }
    }
?>