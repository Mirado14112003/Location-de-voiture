<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclure le fichier de connexion à la base de données
require_once '../database.php';

// Fonction pour mettre à jour la disponibilité d'une voiture
function updateCarAvailability($immatr, $availability) {
    global $conn;

    $sql_update_disponibilite = "UPDATE voiture SET disponibilite = :availability WHERE immatr = :immatr";

    if ($stmt_update_disponibilite = $conn->prepare($sql_update_disponibilite)) {
        $stmt_update_disponibilite->bindParam(":immatr", $immatr, PDO::PARAM_STR);
        $stmt_update_disponibilite->bindParam(":availability", $availability, PDO::PARAM_INT);

        if (!$stmt_update_disponibilite->execute()) {
            echo "Erreur lors de la mise à jour de la disponibilité de la voiture.";
            exit;
        }

        unset($stmt_update_disponibilite);
    }
}

// Récupérer l'historique des réservations depuis la base de données
$sql_reservations = "SELECT r.reservation_id, c.nom, c.prenom, v.marque, v.modele, r.date_debut, r.date_fin, v.immatr
                    FROM reservation r
                    JOIN client c ON r.client_id = c.ID_client
                    JOIN voiture v ON r.immatr = v.immatr
                    ORDER BY r.date_debut DESC";
$stmt_reservations = $conn->prepare($sql_reservations);
$stmt_reservations->execute();
$reservations = $stmt_reservations->fetchAll(PDO::FETCH_ASSOC);

// Traitement de la suppression d'une réservation
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Récupérer l'immatriculation de la voiture associée à la réservation
    $sql_get_immatriculation = "SELECT immatr FROM reservation WHERE reservation_id = :delete_id";
    $stmt_get_immatriculation = $conn->prepare($sql_get_immatriculation);
    $stmt_get_immatriculation->bindParam(":delete_id", $delete_id, PDO::PARAM_INT);
    $stmt_get_immatriculation->execute();
    $immatriculation = $stmt_get_immatriculation->fetchColumn();

    // Supprimer la réservation de la base de données
    $sql_delete_reservation = "DELETE FROM reservation WHERE reservation_id = :delete_id";

    if ($stmt_delete_reservation = $conn->prepare($sql_delete_reservation)) {
        $stmt_delete_reservation->bindParam(":delete_id", $delete_id, PDO::PARAM_INT);

        if ($stmt_delete_reservation->execute()) {
            // La réservation a été supprimée avec succès
            updateCarAvailability($immatriculation, 1); // Mettre à jour la disponibilité de la voiture
            header("Location: gestion_reservation.php");
            exit();
        } else {
            echo "Erreur lors de la suppression de la réservation. Veuillez réessayer plus tard.";
            exit();
        }

        unset($stmt_delete_reservation);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="gestion_reservation.css">
    <title>Gestion des Réservation</title>
</head>
<body>
    <div class="container">
        <h2>Gestion des Réservations</h2>
        <a href="../index.php">Retour à la page d'accueil</a>

        <!-- Tableau d'historique des réservations -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Clients</th>
                    <th>Voitures</th>
                    <th>Immatriculations</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation) : ?>
                    <tr>
                        <td><?= $reservation['reservation_id'] ?></td>
                        <td><?= $reservation['nom'] ?> <?= $reservation['prenom'] ?></td>
                        <td><?= $reservation['marque'] ?> <?= $reservation['modele'] ?></td>
                        <td><?= $reservation['immatr'] ?></td>
                        <td><?= $reservation['date_debut'] ?></td>
                        <td><?= $reservation['date_fin'] ?></td>
                        <td>
                            <!-- Liens pour modifier ou supprimer chaque réservation -->
                            <a href="modify_reservation.php?reservation_id=<?= $reservation['reservation_id'] ?>">Modifier</a>
                            <a href="gestion_reservation.php?delete_id=<?= $reservation['reservation_id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Liens pour Ajouter, Modifier et Supprimer une Réservation -->
        <div class="mb-3">
            <a href="add_reservation.php" class="btn btn-primary">Ajouter une Réservation</a>
        </div>
    </div>
</body>
</html>
