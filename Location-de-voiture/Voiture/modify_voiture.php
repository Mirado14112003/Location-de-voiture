<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclure le fichier de connexion à la base de données
require_once '../database.php';

// Vérifier si l'immatriculation de la voiture à modifier est passée en paramètre
if (isset($_GET["immatr"])) {
    $immatr = $_GET["immatr"];

    // Récupérer les informations de la voiture à partir de la base de données
    $sql_select = "SELECT * FROM voiture WHERE immatr = :immatr";

    if ($stmt_select = $conn->prepare($sql_select)) {
        $stmt_select->bindParam(":immatr", $immatr, PDO::PARAM_STR);

        if ($stmt_select->execute()) {
            $voiture = $stmt_select->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "Erreur lors de la récupération des informations de la voiture.";
            exit;
        }

        unset($stmt_select);
    }

    // Vérifier si le formulaire de modification a été soumis
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Récupérer les nouvelles informations du formulaire
        $newMarque = $_POST["marque"];
        $newModele = $_POST["modele"];
        $newCouleur = $_POST["couleur"];
        $newDisponibilite = isset($_POST["disponibilite"]) ? 1 : 0;

        // Valider et traiter l'upload de l'image
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            $target_dir = "../image/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
                echo "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                exit;
            }

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file; // Mettez à jour avec le nouveau chemin de l'image
            } else {
                echo "Erreur lors du téléchargement de l'image.";
                exit;
            }
        }

        // Mettre à jour les informations de la voiture dans la base de données
        $sql_update_voiture = "UPDATE voiture SET marque = :marque, modele = :modele, couleur = :couleur, disponibilite = :disponibilite, image = :image WHERE immatr = :immatr";

        if ($stmt_update_voiture = $conn->prepare($sql_update_voiture)) {
            $stmt_update_voiture->bindParam(":marque", $newMarque, PDO::PARAM_STR);
            $stmt_update_voiture->bindParam(":modele", $newModele, PDO::PARAM_STR);
            $stmt_update_voiture->bindParam(":couleur", $newCouleur, PDO::PARAM_STR);
            $stmt_update_voiture->bindParam(":disponibilite", $newDisponibilite, PDO::PARAM_INT);
            $stmt_update_voiture->bindParam(":image", $image, PDO::PARAM_STR);
            $stmt_update_voiture->bindParam(":immatr", $immatr, PDO::PARAM_STR);

            if ($stmt_update_voiture->execute()) {
                header("Location: gestion_voiture.php");
                exit();
            } else {
                echo "Une erreur est survenue lors de la modification de la voiture. Veuillez réessayer plus tard.";
                exit();
            }

            unset($stmt_update_voiture);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="modify_voiture.css">
    <title>Modifier la Voiture</title>
</head>
<body>
    <div class="container">
        <h2>Modifier la Voiture</h2>
        <a href="../index.php">Retour à la page d'accueil</a>

        <!-- Formulaire de modification de la voiture -->
        <form method="post" action="" enctype="multipart/form-data">
            <label for="marque">Marque :</label>
            <input type="text" id="marque" name="marque" value="<?= $voiture['marque'] ?>" required>

            <label for="modele">Modèle :</label>
            <input type="text" id="modele" name="modele" value="<?= $voiture['modele'] ?>" required>

            <label for="couleur">Couleur :</label>
            <input type="text" id="couleur" name="couleur" value="<?= $voiture['couleur'] ?>" required>

            <label for="disponibilite">Disponibilité :</label>
            <input type="checkbox" id="disponibilite" name="disponibilite" <?= ($voiture['disponibilite'] == 1) ? 'checked' : '' ?>>

            <label for="image">Image :</label>
            <input type="file" id="image" name="image">

            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>
    </div>
</body>
</html>
