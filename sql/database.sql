CREATE DATABASE IF NOT EXISTS bibliotech;
USE bibliotech;

CREATE TABLE utenti (
    idU INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cogn VARCHAR(100) NOT NULL,  
    email VARCHAR(100) UNIQUE NOT NULL,
    pass VARCHAR(255) NOT NULL,  
    ruolo ENUM('studente', 'bibliotecario') NOT NULL,
    portafoglio DECIMAL(10,2) DEFAULT 0.00,  
    data_registrazione DATE DEFAULT (CURDATE())
);

CREATE TABLE libri (
    idL INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(255) NOT NULL,
    autore VARCHAR(255) NOT NULL,
    isbn VARCHAR(20) UNIQUE NOT NULL,  
    copieTot INT NOT NULL DEFAULT 0,  
    copieDis INT NOT NULL DEFAULT 0   
);

CREATE TABLE prestiti (
    idPr INT AUTO_INCREMENT PRIMARY KEY,
    idU INT NOT NULL,  
    idL INT NOT NULL,  
    idU_bibliotecario INT NULL,  
    dataPres DATE NOT NULL,  
    dataRest DATE NULL,  
    dataScad DATETIME NOT NULL,  
    multa DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (idU) REFERENCES utenti(idU) ON DELETE CASCADE,
    FOREIGN KEY (idL) REFERENCES libri(idL) ON DELETE CASCADE,
    FOREIGN KEY (idU_bibliotecario) REFERENCES utenti(idU) ON DELETE SET NULL
);

CREATE TABLE richieste (
    idR INT AUTO_INCREMENT PRIMARY KEY,
    idU INT NOT NULL,  
    idL INT NOT NULL,  
    dataRichiesta DATE DEFAULT (CURDATE()),  
    stato ENUM('pendente', 'notificato') DEFAULT 'pendente',  
    FOREIGN KEY (idU) REFERENCES utenti(idU) ON DELETE CASCADE,
    FOREIGN KEY (idL) REFERENCES libri(idL) ON DELETE CASCADE
);

CREATE TABLE sessioni (
    idSess INT AUTO_INCREMENT PRIMARY KEY,
    idU INT NOT NULL,  
    dataInizio DATETIME NOT NULL,  
    dataFine DATETIME NULL,  
    stato ENUM('attiva', 'chiusa') DEFAULT 'attiva',
    FOREIGN KEY (idU) REFERENCES utenti(idU) ON DELETE CASCADE
);

INSERT INTO utenti (nome, cogn, email, pass, ruolo, portafoglio) VALUES
('Mario', 'Rossi', 'studente1@example.com', '$2y$10$xuK/udyd.heq4bJAOn4djeC7EtZOd5tbDaP5vq3bDszM0eUcVMcai', 'studente', 50.00),
('Luca', 'Bianchi', 'studente2@example.com', '$2y$10$XmzYVewJ5BCnB1hvqQbihOQHYn3dTOyvvkLtJbhqYkldYECE5k616', 'studente', 20.00),
('Lucia', 'Venere', 'diva@gmail.com', '$2y$10$rrLlSdfR43DSNtahNJzhDejNgxExzHgXzDJOXKkIXilKrgpKGKmxi', 'studente', 0.00),
('Samuel', 'Genchi', 'genchi@gmail.com', '$2y$10$mn8wVi2w64EwWUIdyNQite0yIslz0qVd5WI9pyGQ5lausZAc/vK/2', 'bibliotecario', 0.00),
('Anna', 'Verdi', 'biblio@hotmail.com', '$2y$10$8Eyg54BWaxbFlI6bRyWuh.mJLldbjAEnvgrcHSnY34BOccu5TwLNC', 'bibliotecario', 0.00);

INSERT INTO libri (titolo, autore, isbn, copieTot, copieDis) VALUES
('1984', 'George Orwell', '1234567890', 8, 8),  
('Il Signore degli Anelli', 'J.R.R. Tolkien', '0987654321', 5, 5),  
('Harry Potter e la Pietra Filosofale', 'J.K. Rowling', '1122334455', 4, 4),
('Il Piccolo Principe', 'Antoine de Saint-Exupéry', '5566778899', 3, 3),
('Dune', 'Frank Herbert', '0011223344', 6, 6);