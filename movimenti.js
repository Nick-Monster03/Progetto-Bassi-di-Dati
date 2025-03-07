use Movimenti;
db.dropDatabase();
db.createCollection("log_eventi");

function getFormattedTimestamp() {
    let now = new Date();
    return String(now.getDate()).padStart(2, '0') + "-" +
        String(now.getMonth() + 1).padStart(2, '0') + "-" +
        now.getFullYear() + " " +
        String(now.getHours()).padStart(2, '0') + ":" +
        String(now.getMinutes()).padStart(2, '0');
}

function logInsert(tipoInserimento, oggetto) {
    let descrizioneEvento = "";

    switch (tipoInserimento) {
        case "nuovo_utente":
            descrizioneEvento = "Un nuovo utente è stato registrato: " + oggetto.email;
            break;
        case "nuovo_progetto":
            descrizioneEvento = "Un nuovo progetto è stato creato: " + oggetto.nome;
            break;
        case "nuovo_finanziamento":
            descrizioneEvento = "Un finanziamento è stato effettuato: per il progetto "+oggetto.nomeProgetto+" dall' utente "+oggetto.emailUtente ;
            break;
        case "nuova_candidatura":
            descrizioneEvento = "Un utente ha inviato una candidatura: per la figura di "+ oggetto.nomeProfilo+" del progetto "+oggetto.nomeProgettoSoftware+" dall' utente "+oggetto.emailUtente;
            break;
        case "nuovo_commento":
            descrizioneEvento = "Un nuovo commento è stato aggiunto";
            break;
        case "nuova_risposta":
            descrizioneEvento = "Una risposta a un commento è stata pubblicata: al commento "+oggetto.id;
            break;
        default:
            descrizioneEvento = "Evento non riconosciuto.";
    }
    const timestamp = getFormattedTimestamp();

    // Inserire l'evento nella collezione MongoDB
    db.log_eventi.insertOne({
        tipoInserimento: tipoInserimento,
        descrizioneEvento: descrizioneEvento,
        timestamp: timestamp
    });
}

logInsert("nuovo_utente", { email: "mario.rossi@example.com" });
logInsert("nuovo_progetto", { nome: "Sito Web Aziendale" });
logInsert("nuovo_finanziamento", { nomeProgetto: "Sito Web Aziendale", emailUtente: "mario.rossi@example.com" });
logInsert("nuova_candidatura", { nomeProfilo: "Sviluppatore Front-End", nomeProgettoSoftware: "Sito Web Aziendale", emailUtente: "mario.rossi@example.com" });
logInsert("nuovo_commento", {});
logInsert("nuova_risposta", { id: 12345 });

db.log_eventi.find();