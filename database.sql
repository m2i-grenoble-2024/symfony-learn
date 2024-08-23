-- Active: 1718008404978@@127.0.0.1@3306@dam_symfony
DROP TABLE IF EXISTS dog_person;
DROP TABLE IF EXISTS dog;
DROP TABLE IF EXISTS person;


CREATE TABLE person(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL  
);

CREATE TABLE dog (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    breed VARCHAR(255) NOT NULL,
    birthdate DATE NOT NULL
);

CREATE TABLE dog_person(
    id_person INT,
    id_dog INT,
    PRIMARY KEY (id_person,id_dog),
    Foreign Key (id_person) REFERENCES person(id),
    Foreign Key (id_dog) REFERENCES dog(id)
);

INSERT INTO person (name) VALUES
('Rei'),('Habibi'),('Pierre');


INSERT INTO dog (name,breed,birthdate) VALUES 
('Fido', 'Corgi', '2023-03-23'),
('Rex', 'Corgi', '2020-10-13'),
('Moumouche', 'Poodle', '2016-01-03'),
('Harry', 'Daschund', '2023-04-26');

INSERT INTO dog_person VALUES
(1,1),
(1,2),
(2,3),
(3,1),
(3,3);