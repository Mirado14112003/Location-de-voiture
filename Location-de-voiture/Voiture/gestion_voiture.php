<?php
// Démarrer la session
session_start();

// Vérifier l'état de connexion de l'utilisateur
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclure le fichier de connexion à la base de données
require_once '../database.php';

// Gestion de la suppression de voiture
if (isset($_GET["delete_id"])) {
    $delete_id = $_GET["delete_id"];

    $sql_delete = "DELETE FROM voiture WHERE immatr = :delete_id";

    // Préparer la requête de suppression
    if ($stmt_delete = $conn->prepare($sql_delete)) {
        $stmt_delete->bindParam(":delete_id", $delete_id, PDO::PARAM_STR);

        // Exécuter la suppression et rediriger vers la page des voitures
        if ($stmt_delete->execute()) {
            header("location: gestion_voiture.php");
            exit();
        } else {
            echo "Erreur lors de la suppression de la voiture.";
        }

        unset($stmt_delete);
    }
}

// Lire les informations des voitures depuis la base de données
$sql_read = "SELECT immatr, marque, modele, couleur, disponibilite, image FROM voiture";
$result = $conn->query($sql_read);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="gestion_voiture.css">
    <title>Gestion des Voitures</title>
</head>
<body>
    <div class="container">
        <h2>Gestion des Voitures</h2>
        <a href="../index.php">Retour à la page d'accueil</a>

        <!-- Affichage des voitures dans un tableau -->
        <table class="table">
            <thead>
                <tr>
                    <th>Immatriculation</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Couleur</th>
                    <th>Disponibilité</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= $row["immatr"] ?></td>
                        <td><?= $row["marque"] ?></td>
                        <td><?= $row["modele"] ?></td>
                        <td><?= $row["couleur"] ?></td>
                        <td><?= ($row["disponibilite"] == 1) ? 'Disponible' : 'En location'; ?></td>
                        <td><img src="../image/<?= $row["image"] ?>" alt="Image du véhicule" style="width: 180px; height: auto;"></td>
                        <td>
                            <!-- Liens pour modifier ou supprimer chaque voiture -->
                            <a href="modify_voiture.php?immatr=<?= $row["immatr"] ?>">Modifier</a>
                            <a href="gestion_voiture.php?delete_id=<?= $row["immatr"] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Lien pour ajouter une nouvelle voiture -->
        <a href="add_voiture.php">Ajouter une nouvelle voiture</a>
    </div>
</body>
</html>
