<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="index.css">
    <title>Page d'accueil</title>
</head>
<body>
    <h2>Bienvenue Mr/Mme <?= $_SESSION['username']?> sur la page Car Rental by Mirado</h2>
    <div class="menu-container">
        <div class="menu-item"><a href="Client/gestion_client.php" class="client">Clients</a></div>
        <div class="menu-item"><a href="voiture/gestion_voiture.php" class="voiture">Voitures</a></div>
        <div class="menu-item"><a href="reservation/gestion_reservation.php" class="reservation">Réservations</a></div>
    </div>
    <img src="image/voiture_bleue.jpg" alt="Location de voiture Image">
    <form action="logout.php" method="post">
        <button type="submit" class="btn btn-primary">Déconnexion</button>
    </form>
    <a href="https://www.facebook.com/mirado.razafimandimby/?locale=fr_FR" class="contact">Contact us</a>
    <img src="image/contact.jpg" alt="Contact" class = "contact_image">
</body>
</html>
