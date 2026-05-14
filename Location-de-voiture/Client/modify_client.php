<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclure le fichier de connexion à la base de données
require_once '../database.php';

// Vérifier si l'ID du client à modifier est passé en paramètre
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Récupérer les informations du client à partir de la base de données
    $sql_select = "SELECT * FROM client WHERE Id_client = :id";

    if ($stmt_select = $conn->prepare($sql_select)) {
        $stmt_select->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt_select->execute()) {
            $client = $stmt_select->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "Erreur lors de la récupération des informations du client.";
            exit;
        }

        unset($stmt_select);
    }

    // Vérifier si le formulaire de modification a été soumis
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Récupérer les nouvelles informations du formulaire
        $newNom = $_POST["nom"];
        $newPrenom = $_POST["prenom"];
        $newEmail = $_POST["email"];
        $newTelephone = $_POST["telephone"];
        $newAdresse = $_POST["adresse"];

        // Mettre à jour les informations du client dans la base de données
        $sql_update = "UPDATE client SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone, adresse = :adresse WHERE Id_client = :id";

        if ($stmt_update = $conn->prepare($sql_update)) {
            $stmt_update->bindParam(":nom", $newNom, PDO::PARAM_STR);
            $stmt_update->bindParam(":prenom", $newPrenom, PDO::PARAM_STR);
            $stmt_update->bindParam(":email", $newEmail, PDO::PARAM_STR);
            $stmt_update->bindParam(":telephone", $newTelephone, PDO::PARAM_STR);
            $stmt_update->bindParam(":adresse", $newAdresse, PDO::PARAM_STR);
            $stmt_update->bindParam(":id", $id, PDO::PARAM_INT);

            if ($stmt_update->execute()) {
                header("location: gestion_client.php");
                exit;
            } else {
                echo "Erreur lors de la mise à jour des informations du client.";
            }

            unset($stmt_update);
        }
    }
} else {
    echo "ID du client non spécifié.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="modify_client.css">
    <title>Modifier le Client</title>
</head>
<body>
    <div class="container">
        <h2>Modifier le Client</h2>
        <a href="../index.php">Retour à la page d'accueil</a>

        <!-- Formulaire de modification du client -->
        <form method="post" action="">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?= $client['nom'] ?>" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?= $client['prenom'] ?>" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?= $client['email'] ?>" required>

            <label for="telephone">Téléphone :</label>
            <input type="tel" id="telephone" name="telephone" value="<?= $client['telephone'] ?>" required>

            <label for="adresse">Adresse :</label>
            <input type="text" id="adresse" name="adresse" value="<?= $client['adresse'] ?>" required>

            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>
    </div>
</body>
</html>
