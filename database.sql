DROP TABLE IF EXISTS dog;

CREATE TABLE dog (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    breed VARCHAR(255) NOT NULL,
    birthdate DATE NOT NULL
);

INSERT INTO dog (name,breed,birthdate) VALUES 
('Fido', 'Corgi', '2023-03-23'),
('Rex', 'Corgi', '2020-10-13'),
('Moumouche', 'Poodle', '2016-01-03'),
('Harry', 'Daschund', '2023-04-26');