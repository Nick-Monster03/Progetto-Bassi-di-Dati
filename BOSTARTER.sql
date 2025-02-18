drop database if exists BOSTARTER;
create database if not exists BOSTARTER;
use BOSTARTER;

create table UTENTE(
	email varchar(40) primary key,
    nickname varchar(20),
    nome varchar(20),
    cognome varchar (20),
    annoNascita datetime,
    luogoNascita varchar(30)
)ENGINE="INNODB";

create table SKILL(
	nome  varchar(25) primary key
)ENGINE="INNODB";

create table CURRICULUM(
    nomeskill varchar(25),
    emailutente varchar(40), 
    livello int check (livello between 0 and 5),
    foreign key (nomeskill) references SKILL(nome) on delete cascade,
    foreign key (emailutente) references UTENTE(email) on delete cascade
)ENGINE="INNODB";

create table AMMINISTRATORE(
    emailUtente varchar(40) primary key,
    codice_sicurezza varchar(20),
    foreign key (emailUtente) references UTENTE(email) on delete cascade
) ENGINE="INNODB";

create table CREATORE(
    emailUtente varchar(40) primary key,
    nr_progetti int default 0,
    affidabilità decimal(3,2) default 0,
    foreign key (emailUtente) references UTENTE(email) on delete cascade
) ENGINE="INNODB";

create table PROGETTO(
    nome varchar(30) primary key,
    descrizione varchar(300),
    data_inserimento datetime,
    budget decimal(10,2) not null check (budget > 0),
    data_limite datetime,
    stato enum('aperto', 'chiuso') default 'aperto', 
    emailUtenteCreatore varchar(40),
    foreign key (emailUtenteCreatore) references CREATORE(emailUtente)
) ENGINE="INNODB";

create table FOTO(
	foto varchar(40) primary key,
    nomeProgetto varchar(30),
    foreign key (nomeProgetto) references PROGETTO(nome) on delete cascade
) ENGINE="INNODB";

create table REWARD(
	codice int auto_increment primary key,
    descrizione varchar(300),
    nomeProgetto varchar(30),
    foto varchar(40),
    foreign key(nomeProgetto) references PROGETTO(nome) on delete cascade
) ENGINE="INNODB";

create table PROGETTO_HARDWARE(
	nomeProgetto varchar(30) primary key,
    foreign key (nomeProgetto) references PROGETTO(nome) on delete cascade
) ENGINE="INNODB";

create table PROGETTO_SOFTWARE(
	nomeProgetto varchar(30) primary key,
	foreign key (nomeProgetto) references PROGETTO(nome) on delete cascade
) ENGINE="INNODB";

create table COMPONENTE(
	nome varchar(20) primary key,
    descrizione varchar(300),
    prezzo decimal(4,2)
) ENGINE="INNODB";

create table COMPONENTI_PROGETTO(
	nomeProgettoHardware varchar(30),
    nomeComponente varchar(20),
    quantità int check (quantità > 0),
    foreign key (nomeProgettoHardware) references PROGETTO_HARDWARE(nomeProgetto) on delete cascade,
    foreign key (nomeComponente) references COMPONENTE(nome) on delete cascade,
    primary key(nomeProgettoHardware, nomeComponente)
) ENGINE="INNODB";

create table PROFILO(
	nome varchar(40),
    nomeProgettoSoftware varchar(30),
    foreign key (nomeProgettoSoftware) references PROGETTO_SOFTWARE(nomeProgetto) on delete cascade,
    primary key(nome, nomeProgettoSoftware)
) ENGINE="INNODB";

create table PROFILO_SKILL(
	nomeProfilo varchar(40),
    nomeProgettoSoftware varchar(30),
    nomeSkill varchar(25),
    livelloRichiesto int,
    foreign key (nomeProfilo, nomeProgettoSoftware) references PROFILO(nome, nomeProgettoSoftware) on delete cascade,
    foreign key (nomeSkill) references SKILL(nome) on delete cascade,
    primary key (nomeProfilo, nomeProgettoSoftware)
) ENGINE="INNODB";

create table  FINANZIAMENTO(
	emailUtente varchar(40) not null,
    dataVersamento dateTime,
    nomeProgetto varchar(30),
    idReward int,
    importo decimal(10,2),
    foreign key (emailUtente) references UTENTE(email) on delete cascade,
    foreign key (nomeProgetto) references PROGETTO(nome) on delete cascade,
    foreign key (idReward) references REWARD(codice) on delete cascade,
    primary key(emailUtente, dataVersamento, nomeProgetto)
) ENGINE="INNODB";

create table COMMENTO(
	id int auto_increment primary key,
    testo varchar(400),
    dataCommento datetime,
    nomeProgetto varchar(30),
    emailUtente varchar(40),
    foreign key (emailUtente) references UTENTE(email) on delete cascade,
    foreign key (nomeProgetto) references PROGETTO(nome) on delete cascade
) ENGINE="INNODB";

create table RISPOSTA(
	idCommento int auto_increment primary key,
    emailUtenteCreatore varchar(40),
	risposta varchar(400),
    foreign key (emailUtenteCreatore) references CREATORE(emailUtente) on delete cascade,
    foreign key (idCommento) references COMMENTO(id) on delete cascade
) ENGINE="INNODB";

create table CANDIDATURA(
	nomeProfilo varchar(20),
    nomeProgettoSoftware varchar(30),
    emailUtente varchar(40),
    esito enum('nonVista', 'accettata', 'rifiutata') default 'nonVista',
	foreign key (emailUtente) references UTENTE(email) on delete cascade,
	foreign key (nomeProfilo, nomeProgettoSoftware) references PROFILO(nome, nomeProgettoSoftware) on delete cascade,
    primary key(nomeProfilo, nomeProgettoSoftware, emailUtente) 
) ENGINE="INNODB";

DELIMITER $
create trigger SetAffidabilitàFinanziamento
after insert on FINANZIAMENTO
for each row
begin

	declare num_progetti_finanziati int;
    declare num_progetti_totali int;
    declare nuova_affidabilità decimal(3,2);
    declare creatore_progetto varchar(40);
		
        
        SELECT p.emailUtenteCreatore INTO creatore_progetto
		FROM PROGETTO p
		JOIN FINANZIAMENTO f ON f.nomeProgetto = p.nome
		WHERE f.nomeProgetto = NEW.nomeProgetto
        LIMIT 1;
        
    #Trovo il numero di progetti che hanno ricevuto almeno un finazniamento,
    #Non uso un cursore dato che il valore è solo un numero e non ci possono essere più valori
    #o addirittura più colonne
    SELECT count(distinct p.nome) INTO num_progetti_finanziati
    FROM PROGETTO as p JOIN FINANZIAMENTO as f on p.nome = f.nomeProgetto
    WHERE p.emailUtenteCreatore = creatore_progetto;
    
    #trovo il numero di progetti totali realizzati
    SELECT COUNT(*) INTO num_progetti_totali
    FROM PROGETTO p
    WHERE p.emailUtenteCreatore = creatore_progetto;
    
     if num_progetti_totali > 0 then
        set nuova_affidabilità = num_progetti_finanziati / num_progetti_totali;
	else
		set nuova_affidabilità=0;
	end if;
    
	UPDATE CREATORE
    SET affidabilità = nuova_affidabilità
    WHERE emailUtente = creatore_progetto;
end
$ 
DELIMITER ;

DELIMITER $
create trigger SetAffidabilitàProgetto
after insert on PROGETTO
for each row
begin

	declare num_progetti_finanziati int;
    declare num_progetti_totali int;
    declare nuova_affidabilità decimal(3,2);
    
    #Trovo il numero di progetti che hanno ricevuto almeno un finazniamento,
    #Non uso un cursore dato che il valore è solo un numero e non ci possono essere più valori
    #o addirittura più colonne
    SELECT count(distinct p.nome) INTO num_progetti_finanziati
    FROM PROGETTO as p JOIN FINANZIAMENTO as f on p.nome = f.nomeProgetto
    WHERE p.emailUtenteCreatore = NEW.emailUtenteCreatore;
    
    #trovo il numero di progetti totali realizzati
    SELECT COUNT(*) INTO num_progetti_totali
    FROM PROGETTO p
    WHERE p.emailUtenteCreatore = NEW.emailUtenteCreatore;
    
     if num_progetti_totali > 0 then
        set nuova_affidabilità = num_progetti_finanziati / num_progetti_totali;
	else
		set nuova_affidabilità=0;
	end if;
    
	UPDATE CREATORE
    SET affidabilità = nuova_affidabilità
    WHERE emailUtente = NEW.emailUtenteCreatore;
end
$ 
DELIMITER ;

DELIMITER $
create trigger SetProjectStatus
after insert on FINANZIAMENTO
for each row
begin
	
    declare totale_finanziamenti decimal(10,2) default 0;
    declare is_open int default 0;
    
    set is_open = (select count(*) from Progetto as p where new.nomeProgetto=p.nome and p.stato='aperto');
    set totale_finanziamenti = (select sum(importo) as totale_finanziamenti from finanziamento where nomeProgetto = new.nomeProgetto );
	
    if(is_open>0 and totale_finanziamenti>=(select budget from Progetto where new.nomeProgetto=nome )) then
		UPDATE PROGETTO
		SET stato = 'chiuso'
		WHERE nome = new.nomeProgetto;

   end if;
   
	
end
$ 
DELIMITER ;

DELIMITER $
create trigger SetNumeroProgetti
after insert on PROGETTO
for each row
begin
	declare new_numero int;
    
    set new_numero = (select nr_progetti from Creatore as c where new.emailUtenteCreatore=c.emailUtente);
    
    UPDATE CREATORE
	SET nr_progetti = new_numero+1
	WHERE NEW.emailUtenteCreatore=emailUtente;
    
end
$ 
DELIMITER ;

DELIMITER $
CREATE EVENT event_name
ON SCHEDULE
EVERY 1 DAY 
DO
begin
	
    declare progetto_nome varchar(30);
    declare progetto_data_limite datetime;
    declare progetto_stato enum('aperto', 'chiuso');
    declare done int default 0;

    declare cur cursor for 
        select nome, data_limite, stato from progetto;
    declare continue handler for not found set done = 1;

    open cur;
		fetch cur into progetto_nome, progetto_data_limite, progetto_stato;
		while done = 0 do
			if progetto_data_limite < now() and progetto_stato = 'aperto' then
				UPDATE progetto 
				SET stato = 'chiuso' 
				WHERE nome = progetto_nome;
			end if;
			fetch cur into progetto_nome, progetto_data_limite, progetto_stato;
		end while;
    close cur;
end
$ 
DELIMITER ;

DELIMITER $
create procedure Registrazione(IN email varchar(40), IN nickname varchar(20), IN nome varchar(20), 
							   IN cognome varchar (20), IN annoNascita datetime, IN luogoNascita varchar(30))
begin
	
    declare is_ok int default 0;
    
	if (email is null or email = '' or nickname is null or nickname = '' or nome is null or nome = '' or 
		cognome is null or cognome = '' or annoNascita is null or luogoNascita is null or luogoNascita = '' or annoNascita >= CURDATE()) then
        set is_ok = 0;
	else
		set is_ok = 1;
    END IF;
    
    if(is_ok = 1) then
		INSERT INTO utente (email, nickname, nome, cognome, annoNascita, luogoNascita) 
		VALUES (email, nickname, nome, cognome, annoNascita, luogoNascita);
	end if;
end $
 DELIMITER ;

DELIMITER $
create procedure RegistrazioneAmministratore(IN email varchar(40), IN codice_sicurezza varchar(20))
begin
	declare is_ok int default 0;
    set is_ok = (select count(*) from utente as u where u.email=email);
		
    if (is_ok > 0) then
		INSERT INTO amministratore(emailUtente, codice_sicurezza) 
		VALUES (email, codice_sicurezza);
    end if;
end $
 DELIMITER ;
 
 DELIMITER $
create procedure RegistrazioneCreatore(email varchar(40))
begin
	declare is_ok int default 0;
    set is_ok = (select count(*) from utente as u where u.email=email);
		
    if (is_ok > 0) then
		INSERT INTO creatore(emailUtente) 
		VALUES (email);
    end if;
end $
 DELIMITER ;
 
  DELIMITER $
create procedure InserisciSkillCurriculum(nomeskill varchar(25), emailUtente varchar(40), livello int)
begin
	declare is_ok_email int default 0;
	declare is_ok_skill int default 0;
    declare existing_level int default -1;
    
    set is_ok_email = (select count(*) from utente as u where u.email=emailUtente);
    set is_ok_skill = (select count(*) from skill as s where s.nome=nomeskill);
    set existing_level = (select c.livello from curriculum as c where c.emailUtente=emailUtente and c.nomeSkill=nomeSkill);
    
    if(is_ok_email > 0 and is_ok_skill > 0 and livello between 0 and 5) then
		 if existing_level is not null and existing_level <> livello then
            UPDATE curriculum as c
            SET c.livello = livello 
            WHERE c.nomeskill = nomeskill AND c.emailutente = emailutente;
        elseif existing_level is null then #cioè non esiste nessun record in curriculum con quella email e quealla skill
			INSERT INTO curriculum(nomeskill, emailutente, livello) 
			VALUES (nomeskill, emailutente, livello);
		end if;
    end if;
 end $
 DELIMITER ;
 
 DELIMITER $
create procedure VisualizzaProgettiDispsonibili()
begin

	SELECT *
    FROM progetto
    WHERE stato='aperto';
    
 end $
 DELIMITER ;
 
DELIMITER $
create procedure FinanziaProgetto(emailUtente varchar(40), nomeProgetto varchar(30), importo decimal(10,2), reward_id int)
begin
	declare is_ok_email int default 0;
    declare is_ok_progetto int default 0;
    declare is_ok_reward int default 0;
    declare date_now datetime;
    
    set date_now = now();
    set is_ok_email = (select count(*) from utente as u where u.email = emailUtente);
    set is_ok_progetto = (select count(*) from progetto as p where p.nome=nomeProgetto and p.stato='aperto');
    set is_ok_reward = (select count(*) from reward as r where reward_id = r.codice and nomeProgetto=r.nomeProgetto);
     
	if(is_ok_email > 0 and is_ok_progetto > 0 and is_ok_reward > 0) then
		INSERT INTO finanziamento(emailUtente, dataVersamento, nomeProgetto, idReward, importo)
        VALUES (emailUtente, date_now, nomeProgetto, reward_id, importo);

    end if;
 end $
 DELIMITER ;
 
 DELIMITER $
create procedure AggiungiCommento(testo varchar(400), nomeProgetto varchar(30), emailUtente varchar(40))
begin

	declare dataCommento datetime;
    
    set dataCommento = now();
    
	INSERT INTO COMMENTO(testo, dataCommento, nomeProgetto, emailUtente)
    VALUES (testo, dataCommento, nomeProgetto, emailUtente);
 end $
 DELIMITER ;
 
 DELIMITER $
create procedure Candidati(nomeProfilo varchar(20), nomeProgettoSoftware varchar(30), emailUtente varchar(40))
begin
	declare is_ok_email int default 0;
	declare is_ok_progetto int default 0;
	declare is_ok_profilo int default 0;

	set is_ok_email = (select count(*) from utente where email = emailUtente);
	set is_ok_progetto = (select count(*) from progetto where nome = nomeprogettosoftware);
	set is_ok_profilo = (select count(*) from profilo as p where p.nome=nomeProfilo and p.nomeProgettoSoftware=nomeProgettoSoftware);
    
	if (is_ok_profilo > 0 and is_ok_progetto > 0) then
		INSERT INTO Candidatura(nomeProfilo, nomeProgettoSoftware, emailUtente)
        VALUES (nomeProfilo, nomeProgettoSoftware, emailUtente);
    end if;
 end $
 DELIMITER ;
 
  DELIMITER $
create procedure InserisciCompetenza(nuovaCompetenza varchar(25))
begin
	declare exist int default 0;
    
    set exist = (select count(*) from skill where nome=nuovaCompetenza);
    
    if(exist = 0) then
		INSERT INTO skill(nome)
        VALUES (nuovaCompetenza);
    end if;
 end $
 DELIMITER ;
 
  DELIMITER $
create procedure InserisciProgetto(nome varchar(30), descrizione varchar(300), budget decimal(10,2), data_limite datetime, emailUtenteCreatore varchar(40))
begin
	declare date_now datetime;
    DECLARE is_ok_creatore int default 0;
    
    set date_now = now();
	set is_ok_creatore = (select count(*) from CREATORE where emailUtente = emailUtenteCreatore);
    
    if(is_ok_creatore > 0) then
		INSERT INTO PROGETTO (nome, descrizione, data_inserimento, budget, data_limite, emailUtenteCreatore)
		VALUES(nome, descrizione, date_now, budget, data_limite, emailUtenteCreatore);
    end if;
 end $
 DELIMITER ;
 
DELIMITER $
create procedure InserisciReward(descrizione varchar(300), foto varchar(40), nomeProgetto varchar(30))
begin
    INSERT INTO REWARD (descrizione, foto, nomeProgetto)
    VALUES (descrizione, foto, nomeProgetto);
end $
DELIMITER ;

DELIMITER $
create procedure RispondiCommento(idCommento int, emailCreatore varchar(40), risposta varchar(400))
begin
    declare is_ok_commento int default 0;
    declare email_creatore_progetto varchar(40);
    
    set is_ok_commento = (select count(*) from COMMENTO where id = idCommento);
    
    if is_ok_commento > 0 then
        select p.emailUtenteCreatore into email_creatore_progetto
        from COMMENTO c
        join PROGETTO p on c.nomeProgetto = p.nome
        where c.id = idCommento;
        
        if (email_creatore_progetto = emailCreatore) then
            INSERT INTO RISPOSTA (idCommento, emailUtenteCreatore, risposta)
            VALUES (idCommento, emailCreatore, risposta);
        end if;
    end if;
end $
DELIMITER ;

DELIMITER $
create procedure AggiungiProfilo(nomeProfilo varchar(40), nomeProgettoSoftware varchar(30))
begin
	declare is_ok_project int default 0;
    
    set is_ok_project = (select count(*) from Progetto_Software as ps where ps.nomeProgetto=nomeProgettoSoftware);
    
    if(is_ok_project > 0) then
		INSERT INTO Profilo(nome, nomeProgettoSoftware)
        VALUES (nomeProfilo, nomeProgettoSoftware);
    end if;
end $
DELIMITER ;

DELIMITER $
create procedure AccettaRichiesta(nomeCandidato varchar(40), nomeProgetto varchar(30), profilo varchar(20), accettazione int) #se accettazione=1 allora sarà accettata altrimenti se è 0 sarà rifutata
begin
	declare candidatura_esiste int default 0;

    set candidatura_esiste = (select COUNT(*)  from Candidatura as c
    where c.emailUtente = nomeCandidato and c.nomeProgettoSoftware = nomeProgetto and c.nomeProfilo = profilo and c.esito = 'nonVista');

    if (candidatura_esiste > 0 and accettazione = 1) then
        UPDATE Candidatura
        SET esito = 'accettata'
        WHERE emailUtente = nomeCandidato AND nomeProgettoSoftware = nomeProgetto AND nomeProfilo = profilo AND esito = 'nonVista';
	elseif (accettazione = 0) then
		UPDATE Candidatura
        SET esito = 'rifiutata'
        WHERE emailUtente = nomeCandidato AND nomeProgettoSoftware = nomeProgetto AND nomeProfilo = profilo AND esito = 'nonVista';
    end if;
end $
DELIMITER ;

create view Top3Creatori(email) as 
	select nickname
    from utente
    where email in(
	select emailUtente
    from Creatore
    order by affidabilità desc)
    limit 3;
    
create view ProgettiFinanziati(nome, totale) as
	select nomeProgetto as nome, sum(importo) as totale
	from finanziamento as f
    where nomeProgetto in (select nome from progetto where stato='aperto')
	group by nomeProgetto;
create view ProgettiScadenza(nome, rimanenza) as
	select pf.nome as nome, (p.budget-pf.totale) as rimanenza
    from ProgettiFinanziati as pf join progetto as p on p.nome=pf.nome;
create view Top3ProgettiVicinoScadenza(nome) as
	select ps.nome
    from ProgettiScadenza as ps
    order by rimanenza desc
    limit 3;

create view ClassificaFinanziatori (email, totale) as
	select emailUtente as email, sum(importo) as totale
	from finanziamento as f
	group by emailUtente;
create view Top3Finanziatori(nickname) as
	select nickname
    from utente
    where email in (select email
					from ClassificaFinanziatori as cf
                    order by totale)
	limit 3;

 
 -- Registrazione del primo utente
CALL Registrazione('utente1@example.com', 'user1', 'Mario', 'Rossi', '1990-05-15', 'Roma');
CALL Registrazione('utente2@example.com', 'user2', 'Luca', 'Bianchi', '1995-08-22', 'Milano');
CALL Registrazione('creatore@example.com', 'creatorUser', 'Anna', 'Neri', '1988-07-20', 'Firenze');
CALL Registrazione('creatore2@example.com', 'creatorUser2', 'Marco', 'Verdi', '2000-07-24', 'Bari');
CALL Registrazione('creatore3@example.com', 'creatore3', 'Giovanni', 'Rossi', '1985-06-20', 'Napoli');
CALL Registrazione('creatore4@example.com', 'creatore4', 'Aldo', 'Baglio', '1999-08-10', 'Palermo');

CALL RegistrazioneAmministratore('utente1@example.com', 'SECURECODE123');
CALL RegistrazioneCreatore('creatore@example.com');
CALL RegistrazioneCreatore('creatore2@example.com');
CALL RegistrazioneCreatore('creatore3@example.com');
CALL RegistrazioneCreatore('creatore4@example.com');

-- Creazione della tabella SKILL con alcune competenze
INSERT INTO SKILL (nome)
VALUES ('Programmazione Java'), ('Database Management'), ('Sicurezza Informatica'), ('Gestione Progetti'), ('E-commerce Development'),('Cybersecurity');

CALL InserisciSkillCurriculum('Programmazione Java', 'utente1@example.com', 4);
CALL InserisciSkillCurriculum('Database Management', 'utente2@example.com', 3);
CALL InserisciSkillCurriculum('Database Management', 'utente2@example.com', 5);
CALL InserisciSkillCurriculum('Programmazione Java', 'utente1@example.com', 6);

INSERT INTO PROGETTO (nome, descrizione, data_inserimento, budget, data_limite, stato, emailUtenteCreatore)
VALUES 
('Progetto AI', 'Sviluppo di un sistema AI per il riconoscimento immagini.', '2024-02-16 10:30:00', 50000.00, '2024-12-31 23:59:59', 'aperto', 'creatore@example.com'),
('E-commerce Platform', 'Creazione di una piattaforma di e-commerce scalabile.', '2024-02-16 11:00:00', 75000.00, '2024-11-30 23:59:59', 'aperto', 'creatore2@example.com'),
('Cybersecurity Audit', 'Analisi e miglioramento della sicurezza aziendale.', '2024-02-15 09:45:00', 30000.00, '2024-06-30 23:59:59', 'chiuso', 'creatore@example.com'),
('Interfaccia Gestionale', 'Sviluppo di un sistema di interfaccio per gestione di utenti', '2024-02-16 10:30:00', 50000.00, '2024-12-31 23:59:59', 'aperto', 'creatore3@example.com');

INSERT INTO progetto_software (nomeProgetto)
VALUES ('E-commerce Platform'), ('Cybersecurity Audit');

CALL InserisciReward('Accesso anticipato alla beta', 'reward_beta.jpg', 'Progetto AI');
CALL InserisciReward('Certificato di partecipazione', 'certificato.jpg', 'E-commerce Platform');
CALL InserisciReward('2% delle quote', 'quote.jpg', 'Progetto AI');

CALL FinanziaProgetto('utente2@example.com', 'Progetto AI', 1000.00, 1);
CALL FinanziaProgetto('utente1@example.com', 'Progetto AI', 49000.00, 3);
CALL FinanziaProgetto('utente1@example.com', 'E-commerce Platform', 500.00, 2);


CALL AggiungiCommento('Un altro commento interessante per il progetto.', 'Progetto AI', 'utente2@example.com');
/*
INSERT INTO PROFILO (nome, nomeProgettoSoftware)
VALUES 
('Sviluppatore Frontend', 'E-commerce Platform'),
('Project Manager', 'E-commerce Platform');
INSERT INTO PROFILO (nome, nomeProgettoSoftware)
VALUES
('Specialista in Sicurezza Informatica', 'Cybersecurity Audit'),
('Sviluppatore Backend', 'Cybersecurity Audit');*/
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

CALL InserisciProgetto('Progetto Sistema Distribuito', 'Sviluppo di un sistema di comunicazione', 50000.00, '2024-12-31 23:59:59', 'creatore4@example.com');

CALL RispondiCommento(1, 'creatore@example.com', 'grazie per il tuo commento è stato molto utile');
CALL RispondiCommento(1, 'creatore2@example.com', 'grazie per il tuo commento è stato molto utile');
CALL RispondiCommento(2, 'creatore@example.com', 'grazie per il tuo commento è stato molto utile');

