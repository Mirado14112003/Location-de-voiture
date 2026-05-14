<?php
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
        header("location:index.php");
        exit;
    }
    require_once 'database.php';

    // Définir les variables et initialiser à null
    $username = $password = "";
    $username_error = $password_error = "";

    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Vérifier si username est vide
        if (empty($_POST['username'])) {
            $username_error = "Entrer le nom d'utilisateur";
        } else {
            $username = trim($_POST['username']);
        }

        // Vérifier si password est vide
        if (empty($_POST['password'])) {
            $password_error = "Entrer le mot de passe";
        } else {
            $password = trim($_POST['password']);
        }

        // Valider les informations d'identification seulement si les champs ne sont pas vides
        if (empty($username_error) && empty($password_error)) {
            // Préparer une déclaration SELECT
            $sql = "SELECT id, username, password FROM admins WHERE username = :username";

            if ($stmt = $conn->prepare($sql)) {
                // Lier les paramètres à la déclaration préparée
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

                // Définir les paramètres
                $param_username = $username;

                // Essayer d'exécuter la déclaration préparée
                if ($stmt->execute()) {
                    // Vérifier si le nom d'utilisateur existe
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Si le nom d'utilisateur existe
                    if ($result) {
                        // Extraire le mot de passe haché de la base de données
                        $hased_password = $result['password'];

                        // Comparer le mot de passe haché avec celui saisi
                        if (password_verify($password, $hased_password)) {
                            // Le mot de passe est correct, démarrer une nouvelle session
                            //store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION["username"] = $username;

                            // Rediriger l'utilisateur vers la page d'accueil
                            header("Location: index.php");
                            exit();
                        } else {
                            // Le mot de passe est incorrect
                            $password_error = "Mot de passe incorrect";
                        }
                    } else {
                        // Le nom d'utilisateur est incorrect
                        $username_error = "Le nom d'utilisateur n'existe pas";
                    }
                }
            }
        }
    }  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="wrapper">
        <h2>LOGIN</h2>
        <form action="login.php" method="POST">
            <div class="form-group <?php echo (!empty($username_error)) ? 'has-error' : ''; ?>">
                <label for="exampleInputusername ">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" id="username" placeholder="Username">
                <span class="error-message"><?php echo $username_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_error)) ? 'has-error' : ''; ?>">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                <span class="error-message"><?php echo $password_error; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Connexion</button>
            <p>Pas de compte ? <a href="register.php">Créer un compte ici</a>.</p>
        </form>
    </div>
</body>
</html>
