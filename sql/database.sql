CREATE DATABASE IF NOT EXISTS biblioteca;
USE biblioteca;

CREATE TABLE utenti(
    idU INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    cogn VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    pass VARCHAR(100),
    ruolo ENUM('studente', 'bibliotecario'),
    portafoglio DECIMAL(10, 2) DEFAULT 100.00
);

CREATE TABLE libri(
    idL INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(100),
    autore VARCHAR(100),
    copieTot INT,
    copieDisp INT 
);

CREATE TABLE prestiti(
    idPr INT AUTO_INCREMENT PRIMARY KEY,
    idU INT,
    idL INT,
    dataPres TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    dataRest TIMESTAMP NULL,
    FOREIGN KEY (idU) REFERENCES utenti(idU),
    FOREIGN KEY (idL) REFERENCES libri(idL)
);

CREATE TABLE richiede(
    idU INT,
    idL INT,
    PRIMARY KEY (idU, idL),
    FOREIGN KEY (idU) REFERENCES utenti(idU),
    FOREIGN KEY (idL) REFERENCES libri(idL)
);

------------------------------------------------------------

INSERT INTO utenti (nome, cogn, email, pass, ruolo) VALUES
('Mario', 'Rossi', 'mario@gmail.com', 'MarioR660', 'studente'),
('Luigi', 'Bianchi', 'lubianchi@ciao.it', 'Luigino123', 'bibliotecario'),
('Anna', 'Verdi', 'annaver@panetti.it', 'CapelliVerdi00', 'studente'),
('Giulia', 'Neri', 'corvina@nero.it', 'GiuliaNeri99', 'studente'),
('Samuel', 'Genchi', 'genchis@gmail.com', 'Samu2007', 'bibliotecario');

INSERT INTO libri (titolo, autore, copieTot, copieDisp) VALUES
('Il Signore degli Anelli', 'J.R.R. Tolkien', 5, 5),
('Harry Potter e la Pietra Filosofale', 'J.K. Rowling', 3, 3),
('Il Codice Da Vinci', 'Dan Brown', 4, 4),
('1984', 'George Orwell', 6, 6),
('Il Piccolo Principe', 'Antoine de Saint-Exupéry', 7, 7),
('Il Diario di Anna Frank', 'Anna Frank', 5, 5),
('Il Mago di Oz', 'L. Frank Baum', 6, 6);


