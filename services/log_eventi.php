<?php
    function addLog($tipoInserimento, $newInsert) { //bisogna passare a questa funzione che tipo di nuiovo ato è stato inseirto e un oggetto con i dati da inserire nel log
        require 'vendor/autoload.php';  
        try {
            $client = new MongoDB\Client("mongodb://localhost:27017");
            // Seleziona il database
            $db = $client->Movimenti;
            $timestamp = date("H:i d/m/Y");
            $descrizioneEvento = "";
            $document = [];
            $collectionName = "";

            switch ($tipoInserimento) {
                case "nuovo_utente":
                    $descrizioneEvento = "Un nuovo utente è stato registrato: " . $newInsert->email . " con ruolo " . $newInsert->ruolo;
                    $document = [
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "email" => $newInsert->email,
                        "ruolo" => $newInsert->ruolo
                    ];
                    $collectionName = "nuovo_utente";
                    break;
                case "nuovo_progetto":
                    $descrizioneEvento = "Un nuovo progetto è stato creato: " . $newInsert->nome;
                    $document = [
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nome" => $newInsert->nome
                    ];
                    $collectionName = "nuovo_progetto";
                    break;
                case "nuova_skill":
                    $descrizioneEvento = "Una nuova skill è stata aggiunta: " . $newInsert->nome;
                    $document = [
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nome" => $newInsert->nome
                    ];
                    $collectionName = "nuova_skill";
                    break;
                case "nuovo_finanziamento":
                    $descrizioneEvento = "E' stato aggiunto un nuovo finazniamento al progetto " . $newInsert->nomeProgetto . "\t" . "da parte dell'utente " . $newInsert->utente;
                    $document = [
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nome progetto" => $newInsert->nomeProgetto,
                        "utente" => $newInsert->utente
                    ];
                    $collectionName = "nuovo_finanziamento";
                    break;
                case "nuova_candidatura":
                    $descrizioneEvento = $newInsert->emailUtente . " ha inviato una candidatura: per la figura di " . $newInsert->nomeProfilo . " del progetto " . $newInsert->nomeProgettoSoftware;
                    $document = [
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nomeProfilo" => $newInsert->nomeProfilo,
                        "nomeProgettoSoftware" => $newInsert->nomeProgettoSoftware
                    ];
                    $collectionName = "nuova_candidatura";
                    break;
                case "nuovo_commento":
                    $descrizioneEvento = "Un nuovo commento è stato aggiunto da parte di ". $newInsert->getEmailUtente() . " al progetto " . $newInsert->getNomeProgetto();
                    $document = [
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "emailUtente" => $newInsert->getEmailUtente(),
                        "nomeProgetto" => $newInsert->getNomeProgetto()
                    ];
                    $collectionName = "nuovo_commento";
                    break;
                case "nuova_risposta":
                    $descrizioneEvento = $newInsert->getEmailUtenteCreatore() . " ha risposto al commento di ID=" . $newInsert->getIdCommento();
                    $document = [
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "emailCreatore" => $newInsert->getEmailUtenteCreatore(),
                        "idCommento" => $newInsert->getIdCommento()
                    ];
                    $collectionName = "nuova_risposta";
                    break;
                case "nuovo_esitoCandidatura":
                    $descrizioneEvento = "La candidatura per il profilo " . $newInsert->nomeProfilo . " del progetto " . $newInsert->nomeProgetto . " ha ricevuto un esito: " . $newInsert->esito;
                    $document = [
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nomeProfilo" => $newInsert->nomeProfilo,
                        "nomeProgetto" => $newInsert->nomeProgetto,
                        "esito" => $newInsert->esito
                    ];
                    $collectionName = "nuovo_esitoCandidatura";
                    break;
                case "nuova_Competenza":
                    $descrizioneEvento = "L' amministratore ha aggiunto la competenza:  " . $newInsert->nuovaCompetenza;
                    $document = [
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nuovaCompetenza" => $newInsert->nuovaCompetenza
                    ];
                    $collectionName = "nuova_Competenza";
                    break;
                case "nuovo_reward":
                    $descrizioneEvento = "E' stato aggiunto un nuovo reward al progetto " . $newInsert->nomeProgetto ;
                    $document = [
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp,
                        "nomeProgetto" => $newInsert->nomeProgetto                    ];
                    $collectionName = "nuovo_reward";
                    break;
                default:
                    $descrizioneEvento = "Evento non riconosciuto.";
                    $document = [
                        "descrizioneEvento" => $descrizioneEvento,
                        "timestamp" => $timestamp
                    ];
                    $collectionName = "log_eventi";
            }

            $collection = $db->$collectionName;
            $result = $collection->insertOne($document);
            if ($result->getInsertedCount() == 0) {
               throw new Exception("Errore nell'inserimento del log.");
            }
        } catch (Exception $e) {
            echo "[ERRORE] Operazione non riuscita. Errore: " . $e->getMessage();
        }
    }
?>