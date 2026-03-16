📚 BiblioTech
BiblioTech è un’applicazione web sviluppata per simulare la digitalizzazione del registro cartaceo dei prestiti librari di un istituto scolastico.
L’obiettivo del progetto è quello di sostituire un sistema manuale con una piattaforma informatica in grado di gestire in modo automatico libri, utenti e prestiti.
Il sistema permette di monitorare in tempo reale la disponibilità dei libri, distinguere le operazioni in base al ruolo dell’utente e gestire eventuali ritardi nella restituzione attraverso un sistema di multe simulato.

⚙️ Come funziona il progetto
All’interno del sistema esistono due tipi di utenti: studenti e bibliotecari.
Lo studente può consultare il catalogo dei libri disponibili, prendere in prestito un libro se è presente almeno una copia disponibile (fino a un massimo di due prestiti contemporanei) e visualizzare lo storico dei propri prestiti. Se un libro non è disponibile, può inviare una richiesta e verrà notificato (in modo simulato) quando tornerà disponibile.
Il bibliotecario, invece, ha una visione completa del sistema: può visualizzare tutti i prestiti attivi e registrare la restituzione dei libri. Al momento della restituzione, il sistema calcola automaticamente eventuali multe nel caso in cui il libro venga riconsegnato in ritardo.
Per rendere il progetto facilmente testabile, il tempo di restituzione è stato ridotto a pochi minuti invece di un mese. Se il libro viene restituito dopo 5 minuti viene applicata una piccola multa, che aumenta se il ritardo è maggiore. L’importo viene detratto automaticamente dal portafoglio virtuale dello studente.

🏗 Struttura tecnica
Il progetto è stato sviluppato utilizzando:
PHP per la parte di logica lato server
MySQL o MariaDB per la gestione del database
HTML, CSS e Bootstrap per l’interfaccia grafica
La sicurezza è gestita tramite sessioni PHP e controllo dei ruoli. Le password vengono salvate in forma criptata utilizzando le funzioni di hashing di PHP e l’accesso alle pagine riservate è protetto da controlli lato server.
Nel progetto sono inoltre presenti un diagramma E-R e un diagramma UML che descrivono rispettivamente la struttura del database e l’organizzazione logica del sistema.

💻Requisiti per eseguire il progetto
Per avviare il progetto è necessario avere installato:
XAMPP (oppure un altro ambiente con Apache e MySQL)
PHP versione 8 o superiore
Un browser moderno

🚀Come avviare il progetto
Per prima cosa bisogna copiare la cartella del progetto all’interno della directory htdocs di XAMPP.
Successivamente si avviano Apache e MySQL dal pannello di controllo di XAMPP.
Aprendo il browser e digitando http://localhost/phpmyadmin, bisogna creare un nuovo database chiamato bibliotech e importare il file SQL presente nel progetto (se fornito). Questo passaggio serve per creare automaticamente tutte le tabelle necessarie.
Dopo aver configurato correttamente il file di connessione al database (modificando nome utente, password e nome del database se necessario), il progetto può essere avviato semplicemente aprendo nel browser l’indirizzo:

http://localhost/bibliotech

In alternativa, è possibile avviare il server di sviluppo di PHP aprendo il terminale nella cartella del progetto e digitando:
php -S localhost:8000
e poi visitando l’indirizzo:

http://localhost:8000

🧪Come testare il sistema

Una volta avviato il progetto è possibile registrare un nuovo studente oppure utilizzare eventuali account già presenti nel database.
Si può provare a prendere in prestito due libri e verificare che il sistema blocchi il terzo prestito. Successivamente si può attendere qualche minuto per simulare un ritardo e registrare la restituzione come bibliotecario per controllare il calcolo automatico della multa.

📄 Documentazione

All’interno della cartella docs sono presenti:
Il diagramma E-R, che rappresenta la struttura del database
Il diagramma UML delle classi, che descrive l’organizzazione logica del sistema
Questi diagrammi aiutano a comprendere la progettazione prima dell’implementazione pratica.

🎓 Conclusione

BiblioTech rappresenta un esempio di applicazione web completa che integra gestione database, autenticazione utenti, controllo accessi e logica di business.
Il progetto dimostra come un problema reale, come la gestione dei prestiti librari in una scuola, possa essere risolto attraverso strumenti di sviluppo web studiati durante il quinto anno di informatica.