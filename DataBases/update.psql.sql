
-- ajout concernant la table client
-- Ajout de la colonne profil_img
ALTER TABLE client
    ADD COLUMN profil_img VARCHAR(255);
ALTER TABLE client
    ADD COLUMN telephone INTEGER NOT NULL

--ajout un role pour le client (admin ou user)
ALTER TABLE client
    ADD COLUMN role VARCHAR(10) NOT NULL CHECK ( role IN ('admin', 'user')) DEFAULT 'user';

--plusieurs images pour un produit
ALTER TABLE produit
    ADD COLUMN image_url2 VARCHAR(255),
    ADD COLUMN image_url3 VARCHAR(255),
    ADD COLUMN image_url4 VARCHAR(255),
    ADD COLUMN image_url5 VARCHAR(255);

select * from produit;
