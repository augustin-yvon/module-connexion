<?php
require_once 'utilisateurs.php';

session_start();

$conn = new PDO('mysql:host=localhost:3306;dbname=augustin-yvon_moduleconnexion;','Admin', 'Admin@1394');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $login = $_POST["login"];
    $password = $_POST["password"];

    // Vérifier les informations de connexion dans la base de données
    $query = "SELECT id FROM user WHERE login = :login AND password = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Créer l'objet utilisateur
        $user = new Utilisateur($login, $password);

        // Récupérer l'id à partir du login
        $selectIdQuery = "SELECT `id` FROM `user` WHERE `login` = :login";
        $stmt = $conn->prepare($selectIdQuery);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        $id = $stmt->fetchColumn();

        // Ajouter l'id à l'objet utilisateur
        if ($id !== false) {
            $user->setId($id);
        } else {
            print("Aucun résultat trouvé.");
        }

        // Mettre l'utilisateur à l'état connecté
        $user->login();
        
        $_SESSION["user"] = $user;

        if ($user->getLogin() == 'admin') {
            header("Location: admin.php");
            exit();
        }else {
            header("Location: profil.php");
            exit();
        }
    } else {
        $error = "Login ou mot de passe incorrect.";
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
        <link rel="stylesheet" href="./assets/css/connexion.css">

        <title>Module de Connexion | Connexion</title>
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
                <h1>Connexion</h1>

                <form method="post" action="">
                    <label for="login">Login :</label>
                    <input type="text" name="login" required>

                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" required>

                    <input type="submit" value="Se connecter">

                    <?php if (isset($error)) : ?>
                        <p class="error"><?php echo $error; ?></p>
                    <?php endif; ?>

                    <p>Pas encore inscrit ? <a href="inscription.php">Inscrivez-vous</a></p>
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
