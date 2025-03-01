CREATE DATABASE IF NOT EXISTS module6;

USE module6;

CREATE TABLE IF NOT EXISTS etudiants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cv TEXT DEFAULT 'CV de l\'étudiant',
    dt_naissance DATE,
    isAdmin BOOLEAN DEFAULT false,
    dt_mis_a_jour TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO etudiants (prenom, nom, email, cv, dt_naissance, isAdmin, dt_mis_a_jour) VALUES
('Marie', 'Dupont', 'marie.dupont@email.com', 'CV de Marie Dupont avec expérience en marketing', '1998-05-15', false, NOW()),
('Thomas', 'Martin', 'thomas.martin@email.com', 'CV de Thomas Martin, spécialiste en développement web', '1995-11-23', true, NOW()),
('Sophie', 'Bernard', 'sophie.bernard@email.com', 'CV de Sophie Bernard, passionnée de design', '1999-03-10', false, NOW()),
('Lucas', 'Petit', 'lucas.petit@email.com', 'CV de Lucas Petit avec compétences en réseaux', '1997-07-08', false, NOW()),
('Emma', 'Leroy', 'emma.leroy@email.com', 'CV d\'Emma Leroy, experte en cybersécurité', '1996-01-30', false),
('Hugo', 'Moreau', 'hugo.moreau@email.com', 'CV de Hugo Moreau, développeur mobile', '1994-09-12', false),
('Chloé', 'Dubois', 'chloe.dubois@email.com', 'CV de Chloé Dubois, data scientist', '1998-12-05', false),
('Noah', 'Richard', 'noah.richard@email.com', 'CV de Noah Richard, administrateur système', '1993-04-18', true, ),
('Léa', 'Simon', 'lea.simon@email.com', 'CV de Léa Simon, spécialiste en intelligence artificielle', '1997-08-25', false),
('Paul', 'Laurent', 'paul.laurent@email.com', 'CV de Paul Laurent, expert en bases de données', '1995-06-14', false);