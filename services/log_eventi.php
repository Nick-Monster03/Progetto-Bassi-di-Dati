<?php
    function addLog($tipoInserimento, $newInsert) { //bisogna passare a questa funzione che tipo di nuiovo ato è stato inseirto e un oggetto con i dati da inserire nel log
        require 'vendor/autoload.php';  
        try {
            $client = new MongoDB\Client("mongodb://localhost:27017");
            // Seleziona il database(se non esiste sarà creato nel momento della prima insert)
            $db = $client->Movimenti;
            date_default_timezone_set("Europe/Rome");
            $timestamp = date("H:i d/m/Y");
            $descrizioneEvento = "";
            $document = [];
            

            switch ($tipoInserimento) {
                case "nuovo_utente":
                    $descrizioneEvento = "Un nuovo utente è stato registrato: " . $newInsert->email . " con ruolo " . $newInsert->ruolo;
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "email" => $newInsert->email,
                        "ruolo" => $newInsert->ruolo
                    ];
                    break;
                case "nuovo_progetto":
                    $descrizioneEvento = "Un nuovo progetto è stato creato: " . $newInsert->nome;
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nome" => $newInsert->nome
                    ];
                    break;
                case "nuova_skill":
                    $descrizioneEvento = "L' amministratore " . $newInsert->amministratore . " ha aggiunto la competenza:  " . $newInsert->nome;
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nome" => $newInsert->nome,
                        "amministratore" => $newInsert->amministratore
                    ];
                    break;
                case "nuovo_finanziamento":
                    $descrizioneEvento = "E' stato aggiunto un nuovo finazniamento al progetto " . $newInsert->nomeProgetto . "\t" . "da parte dell'utente " . $newInsert->utente;
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nome progetto" => $newInsert->nomeProgetto,
                        "utente" => $newInsert->utente
                    ];
                    break;
                case "nuovo_profilo":
                    $descrizioneEvento = "E' stato aggiunto un nuovo profilo " . $newInsert->nomeProfilo . "\t" . "al progetto " . $newInsert->nomeProgettoSoftware;
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nomeProfilo" => $newInsert->nomeProfilo,
                        "nomeProgettoSoftware" => $newInsert->nomeProgettoSoftware
                    ];
                    break;
                case "nuova_candidatura":
                    $descrizioneEvento = $newInsert->emailUtente . " ha inviato una candidatura: per la figura di " . $newInsert->nomeProfilo . " del progetto " . $newInsert->nomeProgettoSoftware;
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nomeProfilo" => $newInsert->nomeProfilo,
                        "nomeProgettoSoftware" => $newInsert->nomeProgettoSoftware
                    ];
                    break;
                case "nuovo_commento":
                    $descrizioneEvento = "Un nuovo commento è stato aggiunto da parte di ". $newInsert->getEmailUtente() . " al progetto " . $newInsert->getNomeProgetto();
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "emailUtente" => $newInsert->getEmailUtente(),
                        "nomeProgetto" => $newInsert->getNomeProgetto()
                    ];
                    break;
                case "nuova_risposta":
                    $descrizioneEvento = $newInsert->getEmailUtenteCreatore() . " ha risposto al commento di ID=" . $newInsert->getIdCommento();
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "emailCreatore" => $newInsert->getEmailUtenteCreatore(),
                        "idCommento" => $newInsert->getIdCommento()
                    ];
                    break;
                case "nuovo_esitoCandidatura":
                    $descrizioneEvento = "La candidatura per il profilo " . $newInsert->nomeProfilo . " del progetto " . $newInsert->nomeProgetto . " ha ricevuto un esito: " . $newInsert->esito;
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nomeProfilo" => $newInsert->nomeProfilo,
                        "nomeProgetto" => $newInsert->nomeProgetto,
                        "esito" => $newInsert->esito
                    ];
                    break;
                case "nuova_competenza":
                    $descrizioneEvento = "L' utente " . $newInsert->emailUtente . " ha aggiunto una nuova competenza al suo profilo " . $newInsert->nuovaCompetenza;
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nuovaCompetenza" => $newInsert->nuovaCompetenza,
                        "emailUtente" => $newInsert->emailUtente
                    ];
                    break;
                case "nuovo_reward":
                    $descrizioneEvento = "E' stato aggiunto un nuovo reward al progetto " . $newInsert->nomeProgetto;
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nomeProgetto" => $newInsert->nomeProgetto
                    ];
                    break;
                case "nuovo_componente":
                    $descrizioneEvento = "E' stato aggiunto un nuovo coponente " . $newInsert->nomeComponente . " al progetto " . $newInsert->nomeProgetto;
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nomeComponente" => $newInsert->nomeComponente,
                        "nomeProgetto" => $newInsert->nomeProgetto
                    ];
                    break;
                default:
                    $descrizioneEvento = "Evento non riconosciuto.";
                    $document = [
                        "tipo_azione" => $tipoInserimento,
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp
                    ];
            }

            $collection = $db->log_eventi;
            $result = $collection->insertOne($document);
            if ($result->getInsertedCount() == 0) {
               throw new Exception("Errore nell'inserimento del log.");
            }
        } catch (Exception $e) {
            echo "[ERRORE] Operazione non riuscita. Errore: " . $e->getMessage();
        }
    }
?>