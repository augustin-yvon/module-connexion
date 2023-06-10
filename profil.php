<?php
require_once 'utilisateurs.php';

session_start();

$conn = new PDO('mysql:host=localhost;dbname=moduleconnexion;port=3308','root', '');

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $userID = $user->getId();
}else {
    header("Location: connexion.php");
    exit();
}

// Traitement de la requête de modification du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées par le formulaire
    $newlogin = $_POST['login'];
    $newfirstname = $_POST["firstname"];
    $newlastname = $_POST["lastname"];
    $newpassword = $_POST['password'];

    // Vérifier si le mot de passe respecte les critères
    $passwordPattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if (!preg_match($passwordPattern, $newpassword)) {
        $error = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.";
    }

    // Mettre à jour les données dans la base de données
    $sql = "UPDATE user SET login = '$newlogin', firstname = '$newfirstname', lastname = '$newlastname', password = '$newpassword' WHERE id = $userID";
    if ($conn->query($sql) === TRUE) {
        echo "Profil mis à jour avec succès!";
    } else {
        echo "Erreur lors de la mise à jour du profil: " . $conn->error;
    }
    exit; // Arrêter l'exécution du script après la mise à jour du profil

    http_response_code(200);
    echo "Profil mis à jour avec succès";
    exit;
}

// Récupérer les informations de l'utilisateur actuel à partir de la base de données
$sql = "SELECT login, firstname, lastname, password FROM user WHERE id = $userID";

// Exécution de la requête
$stmt = $conn->query($sql);

// Récupération des résultats
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $currentlogin = $result['login'];
    $currentfirstname = $result["firstname"];
    $currentlastname = $result["lastname"];
    $currentpassword = $result['password'];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="icon" type="image/png" href="./assets/img/logo-MC.png" />
        <link rel="stylesheet" href="./assets/css/profil.css">

        <title>Module de Connexion | Profil</title>
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
                <h1>Modifier vos informations</h1>

                <form id="profile-form">
                    <label for="login">Login :</label>
                    <input type="text" id="login" name="login" value="<?php echo $currentlogin; ?>" required><br>

                    <label for="prenom">Prénom :</label>
                    <input type="text" id="firstname" name="prenom" value="<?php echo $currentfirstname; ?>" required>

                    <label for="nom">Nom :</label>
                    <input type="text" id="lastname" name="nom" value="<?php echo $currentlastname; ?>" required>

                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" value="<?php echo $currentpassword; ?>" required><br>
                    <button type="button" id="toggle-password">Afficher</button><br>

                    <input type="submit" value="Modifier">

                    <?php if (isset($error)) : ?>
                        <p class="error"><?php echo $error; ?></p>
                    <?php endif; ?>
                </form>
            </div>
        </section>

        <footer>
            <p>
                Tous droit réservé © Réalisé par 
                <a href="https://augustin-yvon.github.io/portfolio/" target="_blank" title="Mon Porfolio">Augustin Yvon</title></a>
            </p>
        </footer>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="profil.js"></script>
    </body>
</html>