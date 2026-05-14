<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
}

require_once '../database.php';

// Définir et initialiser les variables
$immatriculation = $marque = $modele = $couleur = $disponibilite = $image =  "";
$immatriculation_error = $marque_error = $modele_error = $couleur_error = $disponibilite_error = $image_error = "";

// Traitement des données de formulaire lors de la soumission du formulaire.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider l'immatriculation
    if (isset($_POST["immatriculation"]) && !empty(trim($_POST["immatriculation"]))) {
        $immatriculation = trim($_POST["immatriculation"]);
        
        // Vérifier si l'immatriculation existe déjà dans la base de données
        $sql_check_immatriculation = "SELECT immatr FROM voiture WHERE immatr = :immatriculation";

        if ($stmt_check_immatriculation = $conn->prepare($sql_check_immatriculation)) {
            // Liaison des paramètres
            $stmt_check_immatriculation->bindParam(":immatriculation", $immatriculation, PDO::PARAM_STR);

            // Tentative d'exécution de la déclaration préparée
            if ($stmt_check_immatriculation->execute()) {
                // Vérifier s'il y a déjà un enregistrement avec la même immatriculation
                if ($stmt_check_immatriculation->rowCount() > 0) {
                    $immatriculation_error = "L'immatriculation existe déjà.";
                }
            } else {
                echo "Problème survenu lors de la vérification de l'immatriculation existante. Veuillez réessayer plus tard.";
            }

            // Fermer la déclaration
            unset($stmt_check_immatriculation);
        }
    } else {
        $immatriculation_error = "Entrer l'immatriculation du véhicule.";
    }

    // Valider la marque
    if (isset($_POST["marque"]) && !empty(trim($_POST["marque"]))) {
        $marque = trim($_POST["marque"]);
    } else {
        $marque_error = "Entrer la marque du véhicule.";
    }

    // Valider le modèle
    if (isset($_POST["modele"]) && !empty(trim($_POST["modele"]))) {
        $modele = trim($_POST["modele"]);
    } else {
        $modele_error = "Entrer le modèle du véhicule.";
    }

    // Valider la couleur
    if (isset($_POST["couleur"]) && !empty(trim($_POST["couleur"]))) {
        $couleur = trim($_POST["couleur"]);
    } else {
        $couleur_error = "Entrer la couleur du véhicule.";
    }

    // Valider la disponibilité
    if (isset($_POST["disponibilite"])) {
        $disponibilite = $_POST["disponibilite"];
    } else {
        $disponibilite_error = "Sélectionner la disponibilité du véhicule.";
    }

    // Valider et traiter l'upload de l'image
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_dir = "../image/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            $image_error = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["image"]["name"]); // Mettez à jour avec le nom du fichier
            } else {
                $image_error = "Erreur lors du téléchargement de l'image.";
            }
        }
    }

    if (empty($immatriculation_error) && empty($marque_error) && empty($modele_error) && empty($couleur_error) && empty($disponibilite_error) && empty($image_error)) {
        $sql_insert_voiture = "INSERT INTO voiture (immatr, marque, modele, couleur, disponibilite, image) VALUES (:immatriculation, :marque, :modele, :couleur, :disponibilite, :image)";

        if ($stmt_insert_voiture = $conn->prepare($sql_insert_voiture)) {
            $stmt_insert_voiture->bindParam(":immatriculation", $immatriculation, PDO::PARAM_STR);
            $stmt_insert_voiture->bindParam(":marque", $marque, PDO::PARAM_STR);
            $stmt_insert_voiture->bindParam(":modele", $modele, PDO::PARAM_STR);
            $stmt_insert_voiture->bindParam(":couleur", $couleur, PDO::PARAM_STR);
            $stmt_insert_voiture->bindParam(":disponibilite", $disponibilite, PDO::PARAM_INT);
            $stmt_insert_voiture->bindParam(":image", $image, PDO::PARAM_STR);

            if ($stmt_insert_voiture->execute()) {
                header("Location: gestion_voiture.php");
                exit();
            } else {
                echo "Une erreur est survenue lors de l'insertion de la voiture. Veuillez réessayer plus tard.";
                exit();
            }

            unset($stmt_insert_voiture);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Voiture</title>
    <link rel="stylesheet" href="../bootstrap.css">
    <link rel="stylesheet" href="add_voiture.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        .has-error {
            border-color: #f00;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Ajouter une Voiture</h2>
        <p>Veuillez remplir les informations de la voiture.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group <?php echo (!empty($immatriculation_error)) ? 'has-error' : ''; ?>">
                <label>Immatriculation</label>
                <input type="text" name="immatriculation" class="form-control" value="<?php echo $immatriculation; ?>">
                <span class="error-message"><?php echo $immatriculation_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($marque_error)) ? 'has-error' : ''; ?>">
                <label>Marque</label>
                <input type="text" name="marque" class="form-control" value="<?php echo $marque; ?>">
                <span class="error-message"><?php echo $marque_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($modele_error)) ? 'has-error' : ''; ?>">
                <label>Modèle</label>
                <input type="text" name="modele" class="form-control" value="<?php echo $modele; ?>">
                <span class="error-message"><?php echo $modele_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($couleur_error)) ? 'has-error' : ''; ?>">
                <label>Couleur</label>
                <input type="text" name="couleur" class="form-control" value="<?php echo $couleur; ?>">
                <span class="error-message"><?php echo $couleur_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($disponibilite_error)) ? 'has-error' : ''; ?>">
                <label>Disponibilité</label>
                <select name="disponibilite" class="form-control">
                    <option value="1" <?php echo ($disponibilite == 1) ? 'selected' : ''; ?>>Disponible</option>
                    <option value="0" <?php echo ($disponibilite == 0) ? 'selected' : ''; ?>>En location</option>
                </select>
                <span class="error-message"><?php echo $disponibilite_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($image_error)) ? 'has-error' : ''; ?>">
                <label for="image">Image :</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <span class="error-message"><?php echo $image_error; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary" value="Ajouter">
                <a class="btn btn-default" href="gestion_voiture.php">Retour</a>
            </div>
        </form>
    </div>
</body>
</html>
