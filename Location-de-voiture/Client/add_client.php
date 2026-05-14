<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
}

require_once '../database.php';

// Définir et initialiser les variables
$nom = $prenom = $email = $telephone = $adresse = "";
$nom_error = $prenom_error = $email_error = $telephone_error = $adresse_error = "";

// Traitement des données de formulaire lors de la soumission du formulaire.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider nom
    if (isset($_POST["nom"]) && !empty(trim($_POST["nom"]))) {
        $nom = trim($_POST["nom"]);
    } else {
        $nom_error = "Entrer le nom du client.";
    }

    // Valider prenom
    if (isset($_POST["prenom"]) && !empty(trim($_POST["prenom"]))) {
        $prenom = trim($_POST["prenom"]);
    } else {
        $prenom_error = "Entrer le prénom du client.";
    }

    // Valider email
    if (isset($_POST["email"]) && !empty(trim($_POST["email"]))) {
        $email = trim($_POST["email"]);
        
        // Vérifier si l'email existe déjà dans la base de données
        $sql_check_email = "SELECT ID_client FROM client WHERE email = :email";

        if ($stmt_check_email = $conn->prepare($sql_check_email)) {
            // Liaison des paramètres
            $stmt_check_email->bindParam(":email", $email, PDO::PARAM_STR);

            // Tentative d'exécution de la déclaration préparée
            if ($stmt_check_email->execute()) {
                // Vérifier s'il y a déjà un enregistrement avec le même email
                if ($stmt_check_email->rowCount() > 0) {
                    $email_error = "L'email existe déjà.";
                }
            } else {
                echo "Problème survenu lors de la vérification de l'email existant. Veuillez réessayer plus tard.";
            }

            // Fermer la déclaration
            unset($stmt_check_email);
        }
    } else {
        $email_error = "Entrer l'email du client.";
    }
     // Valider téléphone
     if (isset($_POST["telephone"]) && !empty(trim($_POST["telephone"]))) {
        $telephone = trim($_POST["telephone"]);
        
        // Vérifier si le telephone existe déjà dans la base de données
        $sql_check_telephone = "SELECT ID_client FROM client WHERE telephone = :telephone";

        if ($stmt_check_telephone = $conn->prepare($sql_check_telephone)) {
            // Liaison des paramètres
            $stmt_check_telephone->bindParam(":telephone", $telephone, PDO::PARAM_STR);

            // Tentative d'exécution de la déclaration préparée
            if ($stmt_check_telephone->execute()) {
                // Vérifier s'il y a déjà un enregistrement avec le même téléphone
                if ($stmt_check_telephone->rowCount() > 0) {
                    $telephone_error = "Le numéro de téléphone existe déjà.";
                }
            } else {
                echo "Problème survenu lors de la vérification de l'email existant. Veuillez réessayer plus tard.";
            }

            // Fermer la déclaration
            unset($stmt_check_telephone);
        }
    } else {
        $email_error = "Entrer l'email du client.";
    }
    // Valider adresse
    if (isset($_POST["adresse"]) && !empty(trim($_POST["adresse"]))) {
        $adresse = trim($_POST["adresse"]);
    } else {
        $adresse_error = "Entrer l'adresse du client.";
    }

    // Si le formulaire a été soumis et aucune erreur de validation n'est survenue, effectuer l'insertion dans la base de données
    if (isset($_POST["submit"]) && empty($nom_error) && empty($prenom_error) && empty($email_error) && empty($telephone_error) && empty($adresse_error)) {
        // Préparez et exécutez la requête d'insertion dans la base de données
        $sql_insert_client = "INSERT INTO client (nom, prenom, email, telephone, adresse) VALUES (:nom, :prenom, :email, :telephone, :adresse)";

        if ($stmt_insert_client = $conn->prepare($sql_insert_client)) {
            // Liaison des paramètres
            $stmt_insert_client->bindParam(":nom", $nom, PDO::PARAM_STR);
            $stmt_insert_client->bindParam(":prenom", $prenom, PDO::PARAM_STR);
            $stmt_insert_client->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt_insert_client->bindParam(":telephone", $telephone, PDO::PARAM_STR);
            $stmt_insert_client->bindParam(":adresse", $adresse, PDO::PARAM_STR);

            // Tentative d'exécution de la déclaration préparée
            if ($stmt_insert_client->execute()) {
                // Rediriger vers la page gestion  après l'ajout du client
                header("Location: gestion_client.php");
                exit();
            } else {
                echo "Une erreur est survenue lors de l'insertion du client. Veuillez réessayer plus tard.";
                exit();
            }

            // Fermer la déclaration 
            unset($stmt_insert_client);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Client</title>
    <link rel="stylesheet" href="../bootstrap.css">
    <link rel="stylesheet" href="add_client.css">
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
        <h2>Ajouter un Client</h2>
        <p>Veuillez remplir les informations du client.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($nom_error)) ? 'has-error' : ''; ?>">
                <label>Nom</label>
                <input type="text" name="nom" class="form-control" value="<?php echo $nom; ?>">
                <span class="error-message"><?php echo $nom_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($prenom_error)) ? 'has-error' : ''; ?>">
                <label>Prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?php echo $prenom; ?>">
                <span class="error-message"><?php echo $prenom_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_error)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="error-message"><?php echo $email_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($telephone_error)) ? 'has-error' : ''; ?>">
                <label>Téléphone</label>
                <input type="text" name="telephone" class="form-control" value="<?php echo $telephone; ?>">
                <span class="error-message"><?php echo $telephone_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($adresse_error)) ? 'has-error' : ''; ?>">
                <label>Adresse</label>
                <input type="text" name="adresse" class="form-control" value="<?php echo $adresse; ?>">
                <span class="error-message"><?php echo $adresse_error; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary" value="Ajouter">
                <a class="btn btn-default" href="gestion_client.php">Retour</a>
            </div>
        </form>
    </div>
</body>
</html>
