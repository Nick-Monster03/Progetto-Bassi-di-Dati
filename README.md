# BoStarter - Crowdfunding Platform

![License](https://img.shields.io/badge/license-MIT-blue.svg)

Progetto per l'esame di Basi di Dati - Piattaforma di crowdfunding per progetti Hardware e Software.

## 📋 Descrizione

BoStarter è una piattaforma di crowdfunding che permette di creare, finanziare e partecipare a progetti innovativi. La piattaforma supporta tre tipi di utenti:

- **Utenti**: Possono finanziare progetti e ricevere reward
- **Creatori**: Possono creare e gestire progetti, gestire candidature per profili di progetto
- **Amministratori**: Gestiscono le skill e supervisionano la piattaforma

### Caratteristiche Principali

- 🚀 **Gestione Progetti**: Creazione di progetti Hardware e Software
- 💰 **Sistema di Finanziamento**: Gli utenti possono sostenere i progetti e ricevere reward
- 👥 **Sistema di Candidature**: I creatori possono richiedere profili specifici per i loro progetti
- 🎯 **Sistema di Competenze**: Gestione curriculum con skill e livelli di competenza
- 📊 **Logging Avanzato**: Tracciamento eventi con MongoDB
- 💬 **Sistema di Commenti**: Interazione tra utenti e creatori

## 🛠️ Tecnologie Utilizzate

- **Backend**: PHP
- **Database Relazionale**: MySQL (MariaDB)
- **Database NoSQL**: MongoDB (per logging eventi)
- **Frontend**: HTML, CSS, Bootstrap 5.3.3
- **Gestione Dipendenze**: Composer

## 📁 Struttura del Progetto

```
.
├── BOSTARTER.sql              # Schema del database MySQL principale
├── Demo.sql                   # Dati di demo per testing
├── Demo.php                   # Script per popolare il database con dati demo
├── testMongo.php              # Script di test per MongoDB
├── composer.json              # Dipendenze PHP
├── pages/                     # Pagine dell'applicazione
│   ├── home/                  # Homepage
│   ├── login/                 # Sistema di login
│   ├── register/              # Registrazione utenti
│   ├── newProject/            # Creazione nuovi progetti
│   ├── item/                  # Visualizzazione dettaglio progetto
│   ├── finanziamento/         # Sistema di finanziamento
│   ├── reward/                # Gestione reward
│   ├── candidature/           # Gestione candidature
│   ├── richiestaCandidatura/  # Richiesta di candidatura
│   ├── commenti/              # Sistema commenti
│   ├── competenze/            # Gestione competenze
│   ├── skillList/             # Lista delle skill
│   ├── menageProfile/         # Gestione profilo
│   └── profiloUtente/         # Visualizzazione profilo
├── services/                  # Servizi backend
│   ├── log_eventi.php         # Logging eventi MongoDB
│   ├── mostraErrore.php       # Gestione errori
│   ├── uploads/               # Upload file (foto progetti/reward)
│   └── vendor/                # Dipendenze Composer
├── bootstrap-5.3.3-dist/      # Framework CSS
└── logo/                      # Logo e risorse grafiche
```

## 🚀 Installazione

### Prerequisiti

- PHP 7.4 o superiore
- MySQL/MariaDB
- MongoDB
- Composer
- Web Server (Apache/Nginx)

### Setup del Database MySQL

1. Creare il database eseguendo lo script principale:
```bash
mysql -u root -p < BOSTARTER.sql
```

2. (Opzionale) Popolare il database con dati demo:
```bash
mysql -u root -p BOSTARTER < Demo.sql
```

### Setup MongoDB

MongoDB viene utilizzato per il logging degli eventi. Assicurarsi che MongoDB sia in esecuzione sulla porta 27017:

```bash
mongod --port 27017
```

### Installazione Dipendenze PHP

```bash
composer install
```

### Configurazione Database

Modificare i parametri di connessione nei file PHP se necessario:

**MySQL:**
```php
$pdo = new PDO('mysql:host=localhost;dbname=BOSTARTER', 'username', 'password');
```
> **Nota**: Sostituire `username` e `password` con le proprie credenziali del database

**MongoDB:**
```php
$client = new MongoDB\Client("mongodb://localhost:27017");
```

### Popolamento Database con Demo

Per inizializzare il database con dati di esempio:

```bash
php Demo.php
```

Questo script caricherà:
- Foto progetti
- Reward
- Finanziamenti di esempio

## 💻 Utilizzo

### Avvio del Server

Se si utilizza il server PHP integrato:

```bash
php -S localhost:8000
```

Quindi visitare: `http://localhost:8000/pages/home/home.php`

### Tipologie di Utenti

#### Utente Standard
- Registrazione tramite form
- Login con email e password
- Finanziamento progetti con selezione reward
- Gestione curriculum con skill
- Invio commenti sui progetti

#### Creatore
- Tutte le funzionalità dell'utente standard
- Creazione progetti (Hardware o Software)
- Definizione budget e data limite
- Creazione profili per il progetto con skill richieste
- Gestione candidature
- Risposta ai commenti

#### Amministratore
- Accesso con codice di sicurezza
- Gestione skill (creazione/modifica)
- Supervisione piattaforma

## 📊 Schema del Database

Il database include le seguenti entità principali:

- **UTENTE**: Informazioni base utenti
- **CREATORE**: Estensione utente per creatori
- **AMMINISTRATORE**: Estensione utente per amministratori
- **PROGETTO**: Progetti di crowdfunding
- **SKILL**: Competenze tecniche
- **CURRICULUM**: Competenze degli utenti
- **PROFILO**: Profili richiesti per progetti
- **FINANZIAMENTO**: Finanziamenti utenti
- **REWARD**: Ricompense per i finanziatori
- **CANDIDATURA**: Candidature per profili
- **COMMENTO**: Commenti sui progetti

Vedere `BOSTARTER.sql` per lo schema completo e `progetto.er` per il diagramma ER.

## 📝 Note di Sviluppo

### Stored Procedures

Il database include diverse stored procedure per operazioni complesse:
- `InserisciReward`: Inserimento reward con foto
- `FinanziaProgetto`: Gestione finanziamenti
- `ValidaCandidatura`: Validazione skill candidato vs profilo
- E altre...

### Sistema di Logging

Gli eventi della piattaforma vengono tracciati in MongoDB nel database `Movimenti`, collection `log_eventi`:
- Nuovi utenti
- Nuovi progetti
- Finanziamenti
- Candidature
- Altri eventi rilevanti

## 📄 Documentazione

Per maggiori dettagli consultare:
- `Relazione Basi Di Dati.pdf`: Documentazione completa del progetto
- `TASKS.txt`: Lista delle funzionalità implementate
- `progetto.er`: Diagramma Entity-Relationship

## 🤝 Contributi

Progetto sviluppato per l'esame di Basi di Dati.

## 📧 Contatti

Per domande o suggerimenti, aprire una issue su GitHub.

## 📜 Licenza

Progetto educativo - Basi di Dati
