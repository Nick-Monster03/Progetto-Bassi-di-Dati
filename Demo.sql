use BOSTARTER;

CALL Registrazione('utente1@example.com', 'u1', 'user1', 'Mario', 'Rossi', '1990-05-15', 'Roma');
CALL Registrazione('utente2@example.com', 'u2','user2', 'Luca', 'Bianchi', '1995-08-22', 'Milano');
CALL Registrazione('creatore@example.com', 'c1','creatorUser', 'Anna', 'Neri', '1988-07-20', 'Firenze');
CALL Registrazione('creatore2@example.com', 'c2', 'creatorUser2', 'Marco', 'Verdi', '2000-07-24', 'Bari');
CALL Registrazione('creatore3@example.com', 'c3', 'creatore3', 'Giovanni', 'Rossi', '1985-06-20', 'Napoli');
CALL Registrazione('creatore4@example.com', 'c4', 'creatore4', 'Aldo', 'Baglio', '1999-08-10', 'Palermo');

CALL RegistrazioneAmministratore('utente1@example.com', 'SECURECODE123');
CALL RegistrazioneCreatore('creatore@example.com');
CALL RegistrazioneCreatore('creatore2@example.com');
CALL RegistrazioneCreatore('creatore3@example.com');
CALL RegistrazioneCreatore('creatore4@example.com');

-- Creazione della tabella SKILL con alcune competenze
INSERT INTO SKILL (nome, emailAmministratore)
VALUES ('Programmazione Java', 'utente1@example.com'), ('Database Management', 'utente1@example.com'), ('Sicurezza Informatica', 'utente1@example.com'), ('Gestione Progetti', 'utente1@example.com'), ('E-commerce Development', 'utente1@example.com'),('Cybersecurity', 'utente1@example.com');

CALL InserisciSkillCurriculum('Programmazione Java', 'utente1@example.com', 4);
CALL InserisciSkillCurriculum('Database Management', 'utente2@example.com', 3);
CALL InserisciSkillCurriculum('Database Management', 'utente2@example.com', 5);

INSERT INTO PROGETTO (nome, descrizione, data_inserimento, budget, data_limite, stato, emailUtenteCreatore)
VALUES 
('Progetto AI', 'Sviluppo di un sistema AI per il riconoscimento immagini.', '2024-02-16 10:30:00', 50000.00, '2025-12-31 23:59:59', 'aperto', 'creatore@example.com'),
('E-commerce Platform', 'Creazione di una piattaforma di e-commerce scalabile.', '2024-02-16 11:00:00', 75000.00, '2025-06-30 23:59:59', 'aperto', 'creatore2@example.com'),
('Cybersecurity Audit', 'Analisi e miglioramento della sicurezza aziendale.', '2024-02-15 09:45:00', 30000.00, '2025-06-30 23:59:59', 'chiuso', 'creatore@example.com'),
('Interfaccia Gestionale', 'Sviluppo di un sistema di interfaccio per gestione di utenti', '2024-02-16 10:30:00', 50000.00, '2025-12-31 23:59:59', 'aperto', 'creatore3@example.com');


INSERT INTO progetto_software (nomeProgetto)
VALUES ('E-commerce Platform'), ('Cybersecurity Audit'),('Interfaccia Gestionale'),('Progetto AI');



CALL FinanziaProgetto('utente2@example.com', 'Progetto AI', 1000.00, 1);
CALL FinanziaProgetto('utente1@example.com', 'Progetto AI', 900.00, 3);
CALL FinanziaProgetto('utente1@example.com', 'E-commerce Platform', 500.00, 2);


CALL AggiungiCommento('Un altro commento interessante per il progetto.', 'Progetto AI', 'utente2@example.com');
CALL AggiungiCommento('Un commento negativo per questo progetto.', 'Progetto AI', 'creatore3@example.com');
CALL AggiungiProfilo('Sviluppatore Frontend', 'E-commerce Platform');
CALL AggiungiProfilo('Project Manager', 'E-commerce Platform');
CALL AggiungiProfilo('Specialista in Sicurezza Informatica', 'Cybersecurity Audit');
CALL AggiungiProfilo('Sviluppatore Backend', 'Cybersecurity Audit');


INSERT INTO PROFILO_SKILL (nomeProfilo, nomeProgettoSoftware, nomeSkill, livelloRichiesto)
VALUES
('Sviluppatore Frontend', 'E-commerce Platform', 'E-commerce Development', 4),
('Project Manager', 'E-commerce Platform', 'Gestione Progetti', 5);
INSERT INTO PROFILO_SKILL (nomeProfilo, nomeProgettoSoftware, nomeSkill, livelloRichiesto)
VALUES
('Specialista in Sicurezza Informatica', 'Cybersecurity Audit', 'Cybersecurity', 2),
('Sviluppatore Backend', 'Cybersecurity Audit', 'Database Management', 4);

CALL InserisciProgetto('Progetto Sistema Distribuito', 'Sviluppo di un sistema di comunicazione', 50000.00, '2024-12-31 23:59:59', 'creatore4@example.com', "sistemaDistribuito.png");
INSERT INTO progetto_hardware (nomeProgetto)
VALUES ('Progetto Sistema Distribuito');

CALL RispondiCommento(1, 'creatore@example.com', 'grazie per il tuo commento è stato molto utile');
CALL RispondiCommento(2, 'creatore@example.com', 'grazie per il tuo commento è stato molto utile');

INSERT INTO COMPONENTE (nome, descrizione, prezzo, quantita, nomeProgettoHardware)
VALUES 
('CPU Intel i7', 'Processore Intel Core i7 di ultima generazione', 300.00, 1, 'Progetto Sistema Distribuito'),
('GPU NVIDIA GTX 3080', 'Scheda grafica NVIDIA RTX 3080 con 10 GB di memoria', 750.00, 1, 'Progetto Sistema Distribuito'),
('RAM Corsair 16GB', 'Modulo RAM Corsair da 16 GB DDR4', 100.00, 3, 'Progetto Sistema Distribuito'),
('SSD Samsung 1TB', 'Disco SSD Samsung 1TB NVMe', 120.00, 2, 'Progetto Sistema Distribuito'),
('HDD Seagate 2TB', 'Hard disk Seagate da 2 TB per storage di massa', 60.00, 1, 'Progetto Sistema Distribuito');