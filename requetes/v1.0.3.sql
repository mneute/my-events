CREATE TABLE type_marqueur (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, UNIQUE INDEX uq_type_marqueur (libelle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE marqueur ADD type_marqueur INT NOT NULL, DROP type;
ALTER TABLE marqueur ADD CONSTRAINT FK_7FEB8C21B214ABDD FOREIGN KEY (type_marqueur) REFERENCES type_marqueur (id);
CREATE INDEX IDX_7FEB8C21B214ABDD ON marqueur (type_marqueur);
ALTER TABLE marqueur DROP adresse, CHANGE date_debut date_debut DATETIME DEFAULT NULL, CHANGE dete_fin dete_fin DATETIME DEFAULT NULL;
ALTER TABLE marqueur CHANGE dete_fin date_fin DATETIME NULL;