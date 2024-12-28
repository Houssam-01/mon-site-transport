<?php
// Paramètres de connexion à la base de données
$host = "localhost";
$dbname = "gestion_transport";
$username = "root";
$password = "";

try {
    // Connexion à la base de données avec PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si une requête POST est envoyée
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errors = [];

        // Identifier le formulaire soumis
        if (isset($_POST['id_vehicule'])) {
            // Traiter le formulaire de véhicule
            $idVehicule = $_POST['id_vehicule'];
            $marque = $_POST['marque'];
            $modele = $_POST['modele'];
            $numVehicule = $_POST['num_vehicule'];
            $anneeDeConstruction = $_POST['ANNEE_FABRIQ'];
            $nbPassagersMax = $_POST['nb_passagers_max'];

            // Validation des champs
            if (empty($idVehicule) || empty($marque) || empty($modele) || empty($numVehicule) || empty($anneeDeConstruction)) {
                $errors[] = "Tous les champs obligatoires doivent être remplis pour le véhicule.";
            }

            if (empty($errors)) {
                // Insérer dans la table véhicule
                $stmt = $conn->prepare("INSERT INTO vehicule (ID_VEHICULE, MARQUE, MODELE, NUM_VEHICULE, ANNEE_FABRIQ, NB_PASSAGERS_MAX)
                                        VALUES (:id_vehicule, :marque, :modele, :num_vehicule, :annee_fabriq, :nb_passagers_max)");
                $stmt->execute([
                    ':id_vehicule' => $idVehicule,
                    ':marque' => $marque,
                    ':modele' => $modele,
                    ':num_vehicule' => $numVehicule,
                    ':annee_fabriq' => $anneeDeConstruction,
                    ':nb_passagers_max' => $nbPassagersMax
                ]);
                header("Location: success.html");
                exit();
            }

        } elseif (isset($_POST['id_eleve'])) {
            // Traiter le formulaire d'élève
            $idEleve = $_POST['id_eleve'];
            $nom = $_POST['nom_eleve'];
            $prenom = $_POST['prenom_eleve'];
            $niveauScolaire = $_POST['niveau_sc_eleve'];

            // Validation des champs
            if (empty($idEleve) || empty($nom) || empty($prenom) || empty($niveauScolaire)) {
                $errors[] = "Tous les champs obligatoires doivent être remplis pour l'élève.";
            }

            if (empty($errors)) {
                // Insérer dans la table élève
                $stmt = $conn->prepare("INSERT INTO eleve (ID_ELEVE, NOM_ELEVE, PRENOM_ELEVE, NIVEAU_SC_ELEVE)
                                        VALUES (:id_eleve, :nom_eleve, :prenom_eleve, :niveau_sc_eleve)");
                $stmt->execute([
                    ':id_eleve' => $idEleve,
                    ':nom_eleve' => $nom,
                    ':prenom_eleve' => $prenom,
                    ':niveau_sc_eleve' => $niveauScolaire
                ]);
                header("Location: success.html");
                exit();
            }

        } elseif (isset($_POST['id_chauffeur'])) {
            // Traiter le formulaire de chauffeur
            $idChauffeur = $_POST['id_chauffeur'];
            $nom = $_POST['nom_chauffeur'];
            $prenom = $_POST['prenom_chauffeur'];
            $adresse = $_POST['adresse_chauffeur'];
            $telephone = $_POST['telephone_chauffeur'];
            $email = $_POST['email_chauffeur'];
            $numPermis = $_POST['num_permis'];

            // Validation des champs
            if (empty($idChauffeur) || empty($nom) || empty($prenom) || empty($adresse) || empty($telephone) || empty($email) || empty($numPermis)) {
                $errors[] = "Tous les champs obligatoires doivent être remplis pour le chauffeur.";
            }

            // Validation supplémentaire
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide.";
            }
            if (!preg_match('/^[0-9]{10}$/', $telephone)) {
                $errors[] = "Le numéro de téléphone doit contenir exactement 10 chiffres.";
            }

            if (empty($errors)) {
                // Insérer dans la table chauffeurs
                $stmt = $conn->prepare("INSERT INTO chauffeurs (id_chauffeur, nom_chauffeur, prenom_chauffeur, adresse_chauffeur, telephone_chauffeur, email_chauffeur, num_permis)
                                        VALUES (:id_chauffeur, :nom_chauffeur, :prenom_chauffeur, :adresse_chauffeur, :telephone_chauffeur, :email_chauffeur, :num_permis)");
                $stmt->execute([
                    ':id_chauffeur' => $idChauffeur,
                    ':nom_chauffeur' => $nom,
                    ':prenom_chauffeur' => $prenom,
                    ':adresse_chauffeur' => $adresse,
                    ':telephone_chauffeur' => $telephone,
                    ':email_chauffeur' => $email,
                    ':num_permis' => $numPermis
                ]);
                header("Location: success.html");
                exit();
            }

        } elseif (isset($_POST['id_assis'])) {
            // Traiter le formulaire d'assistante
            $idAssistante = $_POST['id_assis'];
            $nom = $_POST['nom_assis'];
            $prenom = $_POST['prenom_assis'];
            $adresse = $_POST['adresse_assis'];
            $telephone = $_POST['telephone_assis'];
            $email = $_POST['email_assis'];
        
            // Validation des champs
            if (empty($idAssistante) || empty($nom) || empty($prenom) || empty($adresse) || empty($telephone) || empty($email)) {
                $errors[] = "Tous les champs obligatoires doivent être remplis pour l'assistante.";
            }
        
            // Validation supplémentaire pour l'email et le téléphone
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide.";
            }
            if (!preg_match('/^[0-9]{10}$/', $telephone)) {
                $errors[] = "Le numéro de téléphone doit contenir exactement 10 chiffres.";
            }
        
            if (empty($errors)) {
                // Insérer dans la table `assistante`
                $stmt = $conn->prepare("INSERT INTO assistante (ID_ASSIS, NOM_ASSIS, PRENOM_ASSIS, ADRESSE_ASSIS, TELEPHONE_ASSIS, EMAIL_ASSIS)
                                        VALUES (:id_assis, :nom_assis, :prenom_assis, :adresse_assis, :telephone_assis, :email_assis)");
                $stmt->execute([
                    ':id_assis' => $idAssistante,
                    ':nom_assis' => $nom,
                    ':prenom_assis' => $prenom,
                    ':adresse_assis' => $adresse,
                    ':telephone_assis' => $telephone,
                    ':email_assis' => $email
                ]);
                header("Location: success.html");
                exit();
            }
        }
        

        // Affichage des erreurs s'il y en a
        if (!empty($errors)) {
            echo "<h2>Erreurs :</h2><ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
