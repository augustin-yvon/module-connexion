<?php
require_once 'utilisateurs.php';

session_start();

$conn = new PDO('mysql:host=localhost:3306;dbname=augustin-yvon_moduleconnexion;','Admin', 'Admin@1394');
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="icon" type="image/png" href="./assets/img/logo-MC.png" />
        <link rel="stylesheet" href="./assets/css/admin.css">

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
            <?php
                // Préparation de la requête
                $query = "SELECT * FROM user";
                $stmt = $conn->prepare($query);

                // Exécution de la requête
                $stmt->execute();

                // Récupération des résultats
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Affichage des utilisateurs dans un tableau HTML
                echo '<table>';
                    echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<th>Login</th>';
                        echo '<th>Prénom</th>';
                        echo '<th>Nom</th>';
                        echo '<th>Mot de passe</th>';
                    echo '</tr>';

                foreach ($users as $user) {
                    echo '<tr>';
                        echo '<td>' . $user['id'] . '</td>';
                        echo '<td>' . $user['login'] . '</td>';
                        echo '<td>' . $user['firstname'] . '</td>';
                        echo '<td>' . $user['lastname'] . '</td>';
                        echo '<td>' . $user['password'] . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            ?>
        </section>

        <footer>
            <p>
                Tous droit réservé © Réalisé par 
                <a href="https://augustin-yvon.github.io/portfolio/" target="_blank" title="Mon Porfolio">Augustin Yvon</title></a>
            </p>
        </footer>
    </body>
</html>
