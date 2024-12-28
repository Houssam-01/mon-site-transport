<?php
// Paramètres de connexion à la base de données
$host = "localhost";
$dbname = "gestion_transport";
$username = "root";
$password = "";

try {
    // Création de la connexion PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errors = [];

        // Traitement du formulaire véhicule
        if (isset($_POST['id_vehicule']) && !isset($_POST['id_chauffeur'])) {
            // Récupération des champs
            $idVehicule = $_POST['id_vehicule'];
            $marque = $_POST['marque'];
            $modele = $_POST['modele'];
            $numVehicule = $_POST['num_vehicule'];
            $idDoc = $_POST['ID_DONC'];
            $anneeDeConstruction = $_POST['ANNEE_FABRIQ'];
            $nbPassagersMax = $_POST['nb_passagers_max'] ?? null;

            // Validation des champs
            if (empty($idVehicule) || empty($marque) || empty($modele) || empty($numVehicule) || empty($idDoc) || empty($anneeDeConstruction)) {
                $errors[] = "Tous les champs obligatoires doivent être remplis.";
            }

            if (!is_numeric($idVehicule)) {
                $errors[] = "L'ID du véhicule doit être un nombre.";
            }

            if (!is_numeric($anneeDeConstruction) || $anneeDeConstruction < 1900 || $anneeDeConstruction > date("Y")) {
                $errors[] = "L'année de construction doit être comprise entre 1900 et l'année actuelle.";
            }

            if (!is_numeric($nbPassagersMax) || $nbPassagersMax <= 0) {
                $errors[] = "Le nombre maximal de passagers doit être un entier positif.";
            }

            // Si aucune erreur de validation
            if (empty($errors)) {
                // Vérifier si le document existe dans la table document
                $checkDoc = $conn->prepare("SELECT COUNT(*) FROM document WHERE ID_DOC = ?");
                $checkDoc->execute([$idDoc]);
                if ($checkDoc->fetchColumn() == 0) {
                    $errors[] = "Erreur : L'ID du document n'existe pas.";
                } else {
                    // Vérifier si l'ID du véhicule existe déjà
                    $check = $conn->prepare("SELECT COUNT(*) FROM vehicule WHERE id_vehicule = ?");
                    $check->execute([$idVehicule]);
                    if ($check->fetchColumn() > 0) {
                        $errors[] = "Erreur : L'ID du véhicule existe déjà.";
                    } else {
                        // Insérer les données dans la table vehicule
                        $stmt = $conn->prepare("INSERT INTO vehicule (id_vehicule, marque, modele, num_vehicule, ANNEE_FABRIQ, nb_passagers_max, ID_DOC) 
                                              VALUES (:id_vehicule, :marque, :modele, :num_vehicule, :ANNEE_FABRIQ, :nb_passagers_max, :id_doc)");
                        $stmt->execute([
                            ':id_vehicule' => $idVehicule,
                            ':marque' => $marque,
                            ':modele' => $modele,
                            ':num_vehicule' => $numVehicule,
                            ':ANNEE_FABRIQ' => $anneeDeConstruction,
                            ':nb_passagers_max' => $nbPassagersMax,
                            ':id_doc' => $idDoc
                        ]);

                        echo "<h2>Véhicule ajouté avec succès !</h2>";
                        echo "<a href='index.html'>Retour à l'accueil</a>";
                        exit();
                    }
                }
            }
        }

        // Affichage des erreurs
        if (!empty($errors)) {
            echo "<h2>Erreurs :</h2>";
            echo "<ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
            echo "<a href='index.html'>Retour au formulaire</a>";
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    echo "<br><a href='index.html'>Retour à l'accueil</a>";
}

$conn = null;
?>
