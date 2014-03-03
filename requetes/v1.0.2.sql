CREATE UNIQUE INDEX uq_amis_utilisateurs ON amis (utilisateur_emetteur, utilisateur_destinataire);
CREATE UNIQUE INDEX uq_organisation_utilisateur_manifestation ON organisation (utilisateur, manifestation);
CREATE UNIQUE INDEX uq_participation_utilisateur_manifestation ON participation (utilisateur, manifestation);
