<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclure le fichier de connexion à la base de données
require_once '../database.php';

// Récupérer la liste des clients pour afficher dans la liste déroulante
$sql_clients = "SELECT ID_client, nom, prenom FROM client";
$stmt_clients = $conn->prepare($sql_clients);
$stmt_clients->execute();
$clients = $stmt_clients->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des voitures pour afficher dans la liste déroulante
$sql_voitures = "SELECT immatr, marque, modele FROM voiture WHERE disponibilite = 1";
$stmt_voitures = $conn->prepare($sql_voitures);
$stmt_voitures->execute();
$voitures = $stmt_voitures->fetchAll(PDO::FETCH_ASSOC);

// Gestion de l'ajout de réservation
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $client_id = $_POST["client_id"];
    $immatr = $_POST["immatr"];
    $date_debut = $_POST["date_debut"];
    $date_fin = $_POST["date_fin"];

    // Mettez à jour la disponibilité de la voiture dans la table voiture
    $update_disponibilite = "UPDATE voiture SET disponibilite = 0 WHERE immatr = :immatr";

    if ($stmt_update_disponibilite = $conn->prepare($update_disponibilite)) {
        $stmt_update_disponibilite->bindParam(":immatr", $immatr, PDO::PARAM_STR);

        if ($stmt_update_disponibilite->execute()) {
            // La disponibilité de la voiture a été mise à jour avec succès
        } else {
            echo "Erreur lors de la mise à jour de la disponibilité de la voiture.";
            exit;
        }

        unset($stmt_update_disponibilite);
    }

    // Valider les données de réservation (ajoutez vos propres règles de validation)

    // Insérer la réservation dans la base de données
    $sql_insert_reservation = "INSERT INTO reservation (client_id, immatr, date_debut, date_fin) VALUES (:client_id, :immatr, :date_debut, :date_fin)";
    $stmt_insert_reservation = $conn->prepare($sql_insert_reservation);
    $stmt_insert_reservation->bindParam(":client_id", $client_id, PDO::PARAM_INT);
    $stmt_insert_reservation->bindParam(":immatr", $immatr, PDO::PARAM_STR);
    $stmt_insert_reservation->bindParam(":date_debut", $date_debut, PDO::PARAM_STR);
    $stmt_insert_reservation->bindParam(":date_fin", $date_fin, PDO::PARAM_STR);

    if ($stmt_insert_reservation->execute()) {
        header("Location: gestion_reservation.php");
        exit();
    } else {
        echo "Une erreur est survenue lors de l'ajout de la réservation. Veuillez réessayer plus tard.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="add_reservation.css">
    <title>Ajouter une Réservation</title>
</head>
<body>
    <div class="container">
        <h2>Ajouter une Réservation</h2>
        <a href="../index.php">Retour à la page d'accueil</a>

        <!-- Formulaire d'ajout de réservation -->
        <form method="post" action="">
            <div class="form-group">
                <label for="client_id">Client :</label>
                <select name="client_id" id="client_id" class="form-control">
                    <?php foreach ($clients as $client) : ?>
                        <option value="<?= $client['ID_client'] ?>"><?= $client['nom'] ?> <?= $client['prenom'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="immatr">Voiture :</label>
                <select name="immatr" id="immatr" class="form-control">
                    <?php foreach ($voitures as $voiture) : ?>
                        <option value="<?= $voiture['immatr'] ?>"><?= $voiture['marque'] ?> <?= $voiture['modele'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date_debut">Date de début :</label>
                <input type="date" name="date_debut" id="date_debut" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="date_fin">Date de fin :</label>
                <input type="date" name="date_fin" id="date_fin" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Ajouter la Réservation</button>
        </form>
    </div>
</body>
</html>
