<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "location_voiture";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

// Définir et initialiser les variables
$username = $email = $password = $confirm_password = "";
$username_error = $email_error = $password_error = $confirm_password_error = "";

// Traitement des données de formulaire lors de la soumission du formulaire.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Valider username
  if (empty(trim($_POST["username"]))) {
    $username_error = "Entrer le nom d'utilisateur.";
  } else {
    // préparer un SELECT statement
    $sql = "SELECT id FROM admins WHERE username = ?";

    if ($requete = $conn->prepare($sql)) {
      $param_username = trim($_POST["username"]);
      $requete->bindParam(1, $param_username, PDO::PARAM_STR); // Correction ici

      // attempt to execute the prepared statement
      if ($requete->execute()) {
        // Store result
        $requete->FetchAll();

        if ($requete->rowCount() == 1) {
          $username_error = "Le nom d'utilisateur existe déjà.";
        } else {
          $username = trim($_POST["username"]);
        }
      } else {
        echo "Problème survenu lors de l'inscription. Veuillez réessayer plus tard.";
      }

      // close statement
      unset($requete);
    }
  }

  // Valider email
  if (empty(trim($_POST["email"]))) {
    $email_error = "Entrer votre email.";
  } else {
    $email = trim($_POST["email"]);
  }

  // Valider le mot de passe
  if (empty(trim($_POST["password"]))) {
    $password_error = "Entrer un mot de passe.";
  } elseif (strlen(trim($_POST["password"])) < 8) {
    $password_error = "Le mot de passe doit avoir au moins 8 caractères.";
  } else {
    $password = trim($_POST["password"]);
  }

  // Valider la confirmation du mot de passe
  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_error = "Veuillez confirmer le mot de passe.";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($password_error) && ($password != $confirm_password)) {
      $confirm_password_error = "Le mot de passe est différent.";
    }
  }

  // Check input errors before inserting in the database
  if (empty($username_error) && empty($password_error) && empty($confirm_password_error) && empty($email_error)) {
    // prepare an insert statement
    $sql = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";

    if ($requete = $conn->prepare($sql)) {
      $requete->bindParam(1, $param_username, PDO::PARAM_STR);
      $requete->bindParam(2, $param_email, PDO::PARAM_STR);
      $requete->bindParam(3, $param_password, PDO::PARAM_STR);

      // set parameters
      $param_username = $username;
      $param_email = $email;
      $param_password = password_hash($password, PASSWORD_DEFAULT);

      if ($requete->execute()) {
        header("location: login.php");
      } else {
        echo "Un problème est survenu. Veuillez réessayer plus tard.";
      }

      // Close statement
      unset($requete);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription</title>
  <link rel="stylesheet" href="bootstrap.css">
  <link rel = "stylesheet" href="register.css">

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
  <h2>Inscription</h2>
  <p>Veuillez remplir ce formulaire pour créer un compte.</p>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="form-group <?php echo (!empty($username_error)) ? 'has-error' : ''; ?>">
      <label>Nom d'utilisateur</label>
      <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
      <span><?php echo $username_error; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($email_error)) ? 'has-error' : ''; ?>">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
      <span><?php echo $email_error; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($password_error)) ? 'has-error' : ''; ?>">
      <label>Mot de passe</label>
      <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
      <span><?php echo $password_error; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($confirm_password_error)) ? 'has-error' : ''; ?>">
      <label>Confirmer le mot de passe</label>
      <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
      <span><?php echo $confirm_password_error; ?></span>
    </div>

    <div class="form-group">
      <input type="submit" class="btn btn-primary" value="S'inscrire">
      <input type="reset" class="btn btn-default" value="Réinitialiser">
    </div>
    <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
  </form>
</div>
</body>
</html>
