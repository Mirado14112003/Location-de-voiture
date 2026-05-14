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

// Gestion de la suppression de client
if (isset($_GET["delete_id"])) {
    $delete_id = $_GET["delete_id"];

    $sql_delete = "DELETE FROM client WHERE ID_client = :delete_id";

    // Préparer la requête de suppression
    if ($stmt_delete = $conn->prepare($sql_delete)) {
        $stmt_delete->bindParam(":delete_id", $delete_id, PDO::PARAM_INT);

        // Exécuter la suppression et rediriger vers la page des clients
        if ($stmt_delete->execute()) {
            header("location: gestion_client.php");
            exit();
        } else {
            echo "Erreur lors de la suppression du client.";
        }

        unset($stmt_delete);
    }
}

// Lire les informations des clients depuis la base de données
$sql_read = "SELECT Id_client, nom, prenom, email, telephone, adresse FROM client";
$result = $conn->query($sql_read);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="gestion_client.css">
    <title>Gestion des Clients</title>
</head>
<body>
    <div class="container">
        <h2>Gestion des Clients</h2>
        <a href="../index.php">Retour à la page d'accueil</a>

        <!-- Affichage des clients dans un tableau -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= $row["Id_client"] ?></td>
                        <td><?= $row["nom"] ?></td>
                        <td><?= $row["prenom"] ?></td>
                        <td><?= $row["email"] ?></td>
                        <td><?= $row["telephone"] ?></td>
                        <td><?= $row["adresse"] ?></td>
                        <td>
                            <!-- Liens pour modifier ou supprimer chaque client -->
                            <a href="modify_client.php?id=<?= $row["Id_client"] ?>">Modifier</a>
                            <a href="gestion_client.php?delete_id=<?= $row["Id_client"] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Lien pour ajouter un nouveau client -->
        <a href="add_client.php">Ajouter un nouveau client</a>
    </div>
</body>
</html>
