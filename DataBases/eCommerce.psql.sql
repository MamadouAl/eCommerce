--CREATION DES Tables
DROP TABLE IF EXISTS avis;
DROP TABLE IF EXISTS produit_panier;
DROP TABLE IF EXISTS panier;
DROP TABLE IF EXISTS produit_commande;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS produit;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS client;


-- Table des clients
CREATE TABLE client (
    clientID serial PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    passwd VARCHAR(15) NOT NULL,
    adresseLivraison VARCHAR(255)
);

-- Table des catégories
CREATE TABLE categorie (
    categorieID serial PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
  --  description TEXT
);

-- Table des Produits
CREATE TABLE produit (
    produitID serial PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255), -- Colonne pour le lien de l'image
    categorieID INT,
    FOREIGN KEY (categorieID) REFERENCES categorie(categorieID)
);


-- Table des commandes
CREATE TABLE commande (
    commandeID serial PRIMARY KEY,
    clientID INT,
    dateCommande DATE NOT NULL,
    statut VARCHAR(50) NOT NULL CHECK (statut IN ('En cours de traitement', 'Expédiée', 'Livraison en attente', 'Annulée')),
    FOREIGN KEY (clientID) REFERENCES client(clientID)
);

-- Table des produits inclus dans une commande (table association)
CREATE TABLE produit_commande (
    commandeID INT,
    produitID INT,
    quantite INT NOT NULL,
    PRIMARY KEY (commandeID, produitID),
    FOREIGN KEY (commandeID) REFERENCES commande(commandeID),
    FOREIGN KEY (produitID) REFERENCES produit(produitID)
);

-- Table des paniers d'achats temporaires
CREATE TABLE panier (
    panierID serial PRIMARY KEY,
    clientID INT,
    DateCreation DATE NOT NULL,
    FOREIGN KEY (clientID) REFERENCES client(clientID)
);

-- Table des produits inclus dans un panier (table associative)
CREATE TABLE produit_panier (
    panierID INT,
    produitID INT,
    quantite INT NOT NULL,
    PRIMARY KEY (panierID, produitID),
    FOREIGN KEY (panierID) REFERENCES panier(panierID),
    FOREIGN KEY (produitID) REFERENCES produit(produitID)
);


