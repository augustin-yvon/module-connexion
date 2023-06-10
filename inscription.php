<?php
require_once 'utilisateurs.php';

session_start();

$conn = new PDO('mysql:host=localhost;dbname=moduleconnexion;port=3308','root', '');

$errors = array();

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $login = $_POST["login"];
    $firstname = $_POST["prenom"];
    $lastname = $_POST["nom"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Vérifier si le mot de passe respecte les critères
    $passwordPattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if (!preg_match($passwordPattern, $password)) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.";
    }

    // Vérifier si les mots de passe correspondent
    if ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Vérifier si le login est déjà utilisé
    $checkLoginQuery = "SELECT id FROM user WHERE login = :login";
    $stmt = $conn->prepare($checkLoginQuery);
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $errors[] = "Ce login est déjà utilisé. Veuillez en choisir un autre.";
    }

    // Insérer les données dans la base de données si aucune erreur n'est survenue
    if (empty($errors)) {
        $insertQuery = "INSERT INTO user (login, firstname, lastname, password) VALUES (:login, :firstname, :lastname, :password)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            header("Location: connexion.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="icon" type="image/png" href="./assets/img/logo-MC.png" />
        <link rel="stylesheet" href="./assets/css/inscription.css">

        <title>Module de Connexion | Inscription</title>
    </head>

    <body>
        <header>
            <div class="header-logo">
                <img onclick="location.href='index.php';" src="./assets/img/logo-module-connexion.png" alt="logo module de connexion" title="logo module de connexion">
            </div>
            
            <div class="header-profil">
                <div class="header-profil-container">
                    <?php if (isset($_SESSION['user'])) : ?>
                        <?php $user = $_SESSION['user'];

                        if ($user instanceof Utilisateur && $user->getLogState() == true) :?>
                            <?php if ($user->getLogin() !== 'admin') : ?>
                                <img onclick="location.href='profil.php';" src="./assets/img/icone-profil-blanc.png" alt="icone profil" title="Profil">
                            <?php else : ?>
                                <img onclick="location.href='admin.php';" src="./assets/img/icone-profil-blanc.png" alt="icone profil" title="Profil">
                            <?php endif; ?>

                        <?php else : ?>
                            <img onclick="location.href='connexion.php';" src="./assets/img/icone-profil-blanc.png" alt="icone profil" title="Profil">
                        <?php endif; ?>

                    <?php else : ?>
                        <img onclick="location.href='connexion.php';" src="./assets/img/icone-profil-blanc.png" alt="icone profil" title="Profil">
                    <?php endif; ?>
    
                    <?php if (isset($_SESSION['user'])) : ?>
                        <?php $user = $_SESSION['user'];

                        if ($user->getLogState() == true) :?>
                            <a href="deconnexion.php" class="logout-button">Déconnexion</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <section class="main-container">
            <div class="container">
                <h1>Inscription</h1>

                <form method="post" action="">
                    <label for="login">Login :</label>
                    <input type="text" name="login" required>

                    <label for="prenom">Prénom :</label>
                    <input type="text" name="prenom" required>

                    <label for="nom">Nom :</label>
                    <input type="text" name="nom" required>

                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" required>

                    <label for="confirm_password">Confirmez le mot de passe :</label>
                    <input type="password" name="confirm_password" required>

                    <input type="submit" value="S'inscrire">

                    <?php if (!empty($errors)) : ?>
                        <div class="error">
                            <?php foreach ($errors as $error) : ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <p>Déjà inscrit ? <a href="connexion.php">Connectez-vous</a></p>
                </form>
            </div>
        </section>

        <footer>
            <p>
                Tous droit réservé © Réalisé par 
                <a href="https://augustin-yvon.github.io/portfolio/" target="_blank" title="Mon Porfolio">Augustin Yvon</title></a>
            </p>
        </footer>
    </body>
</html>