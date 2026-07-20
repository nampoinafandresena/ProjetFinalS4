-- Table des users
CREATE TABLE user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero TEXT UNIQUE NOT NULL,
    solde REAL DEFAULT 0.0,
    role TEXT DEFAULT 'client' CHECK (role IN ('admin', 'client'))
);

-- Table des types d'opération
CREATE TABLE type_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    label TEXT UNIQUE NOT NULL
);

-- Table du barème des frais
CREATE TABLE bareme_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    min REAL NOT NULL,
    max REAL NOT NULL,
    frais REAL NOT NULL,
    CHECK (min >= 0),
    CHECK (max >= min)
);

-- Table des historiques de transactions (CORRIGÉE)
CREATE TABLE historiques (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user1 INTEGER NOT NULL, -- envoyeur
    user2 INTEGER, -- receveur (peut être NULL pour dépôt/retrait)
    type_mvt INTEGER NOT NULL,
    montant REAL NOT NULL,
    frais_appliques REAL DEFAULT 0.0, -- Ajout du champ pour les frais
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user1) REFERENCES user(id),        -- CORRIGÉ : id_operateur → user1
    FOREIGN KEY (user2) REFERENCES user(id),        -- CORRIGÉ : ajout de la clé étrangère pour user2
    FOREIGN KEY (type_mvt) REFERENCES type_operation(id)
);

-- Insertion des types d'opération par défaut
INSERT INTO type_operation (label) VALUES 
    ('dépôt'),
    ('retrait'),
    ('transfert');

-- Insertion dans le barème des frais
INSERT INTO bareme_frais (min, max, frais) VALUES 
    (100, 1000, 50),
    (1001, 5000, 50),
    (5001, 10000, 100),
    (10001, 25000, 200),
    (25001, 50000, 400),
    (50001, 100000, 800),
    (100001, 250000, 1500),
    (250001, 500000, 1500),
    (500001, 1000000, 2500),
    (1000001, 2000000, 3000);

-- Création d'index pour améliorer les performances
CREATE INDEX idx_historiques_user1 ON historiques(user1);
CREATE INDEX idx_historiques_user2 ON historiques(user2);
CREATE INDEX idx_historiques_type ON historiques(type_mvt);
CREATE INDEX idx_historiques_date ON historiques(date_transaction);