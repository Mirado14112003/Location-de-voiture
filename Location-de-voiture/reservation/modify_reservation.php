<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclure le fichier de connexion à la base de données
require_once '../database.php';

// Récupérer l'id de la réservation à modifier depuis les paramètres GET
if (isset($_GET["reservation_id"])) {
    $reservation_id = $_GET["reservation_id"];

    // Récupérer les informations de la réservation depuis la base de données
    $sql_select_reservation = "SELECT r.reservation_id, c.ID_client, c.nom, c.prenom, v.immatr, v.marque, v.modele, r.date_debut, r.date_fin
                              FROM reservation r
                              JOIN client c ON r.client_id = c.ID_client
                              JOIN voiture v ON r.immatr = v.immatr
                              WHERE r.reservation_id = :reservation_id";

    if ($stmt_select_reservation = $conn->prepare($sql_select_reservation)) {
        $stmt_select_reservation->bindParam(":reservation_id", $reservation_id, PDO::PARAM_STR);

        if ($stmt_select_reservation->execute()) {
            $reservation = $stmt_select_reservation->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "Erreur lors de la récupération des informations de la réservation.";
            exit;
        }

        unset($stmt_select_reservation);
    }

    // Vérifier si le formulaire de modification a été soumis
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Récupérer les nouvelles informations du formulaire
        $newNomClient = $_POST["nom"];
        $newPrenomClient = $_POST["prenom"];
        $newMarqueVoiture = $_POST["marque"];
        $newImmatriculation = $_POST["immatriculation"];
        $newDateDebut = $_POST["date_debut"];
        $newDateFin = $_POST["date_fin"];

        // Mettre à jour les informations de la réservation dans la base de données
        $sql_update_reservation = "UPDATE reservation
                                  SET client_id = (SELECT ID_client FROM client WHERE nom = :nom AND prenom = :prenom),
                                      immatr = :immatriculation,
                                      date_debut = :date_debut,
                                      date_fin = :date_fin
                                  WHERE reservation_id = :reservation_id";

        if ($stmt_update_reservation = $conn->prepare($sql_update_reservation)) {
            $stmt_update_reservation->bindParam(":nom", $newNomClient, PDO::PARAM_STR);
            $stmt_update_reservation->bindParam(":prenom", $newPrenomClient, PDO::PARAM_STR);
            $stmt_update_reservation->bindParam(":immatriculation", $newImmatriculation, PDO::PARAM_STR);
            $stmt_update_reservation->bindParam(":date_debut", $newDateDebut, PDO::PARAM_STR);
            $stmt_update_reservation->bindParam(":date_fin", $newDateFin, PDO::PARAM_STR);
            $stmt_update_reservation->bindParam(":reservation_id", $reservation_id, PDO::PARAM_STR);

            if ($stmt_update_reservation->execute()) {
                header("Location: gestion_reservation.php");
                exit();
            } else {
                echo "Une erreur est survenue lors de la modification de la réservation. Veuillez réessayer plus tard.";
                exit();
            }

            unset($stmt_update_reservation);
        }
    }
} else {
    // Rediriger si l'id de réservation n'est pas fourni dans les paramètres GET
    header("Location: gestion_reservation.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="modify_reservation.css">
    <title>Modifier la Réservation</title>
</head>
<body>
    <div class="container">
        <h2>Modifier la Réservation</h2>
        <a href="../index.php">Retour à la page d'accueil</a>

        <!-- Formulaire de modification de la réservation -->
        <form method="post" action="">
            <label for="nom">Nom du client :</label>
            <input type="text" id="nom" name="nom" value="<?= $reservation['nom'] ?>" required>

            <label for="prenom">Prénom du client :</label>
            <input type="text" id="prenom" name="prenom" value="<?= $reservation['prenom'] ?>" required>

            <label for="marque">Marque de la voiture :</label>
            <input type="text" id="marque" name="marque" value="<?= $reservation['marque'] ?>" required>

            <label for="immatriculation">Immatriculation :</label>
            <input type="text" id="immatriculation" name="immatriculation" value="<?= $reservation['immatr'] ?>" required>

            <label for="date_debut">Date de début :</label>
            <input type="date" id="date_debut" name="date_debut" value="<?= $reservation['date_debut'] ?>" required>

            <label for="date_fin">Date de fin :</label>
            <input type="date" id="date_fin" name="date_fin" value="<?= $reservation['date_fin'] ?>" required>

            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>
    </div>
</body>
</html>
