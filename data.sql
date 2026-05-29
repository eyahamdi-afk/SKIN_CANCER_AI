CREATE TABLE users (
    id_user SERIAL PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL CHECK (role IN ('patient', 'medecin'))
);

CREATE TABLE patients (
    id_patient SERIAL PRIMARY KEY,
    id_user INT REFERENCES users(id_user) ON DELETE CASCADE,
    age INT,
    sexe VARCHAR(20),
    telephone VARCHAR(30),
    adresse TEXT
);

CREATE TABLE images_peau (
    id_image SERIAL PRIMARY KEY,
    id_patient INT REFERENCES patients(id_patient) ON DELETE CASCADE,
    image_path TEXT NOT NULL,
    date_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE questionnaires (
    id_questionnaire SERIAL PRIMARY KEY,
    id_patient INT REFERENCES patients(id_patient) ON DELETE CASCADE,
    changement_couleur BOOLEAN,
    augmentation_taille BOOLEAN,
    forme_irreguliere BOOLEAN,
    saignement BOOLEAN,
    demangeaison BOOLEAN,
    douleur_locale BOOLEAN,
    date_questionnaire TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE resultats_ia (
    id_resultat SERIAL PRIMARY KEY,
    id_patient INT REFERENCES patients(id_patient) ON DELETE CASCADE,
    id_image INT REFERENCES images_peau(id_image) ON DELETE CASCADE,
    maladie_predite VARCHAR(150),
    probabilite NUMERIC(5,2),
    commentaire TEXT,
    date_resultat TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);