<?php
// Inclure le fichier contenant la classe Utilisateur
require_once 'utilisateurs.php'; // Les inclusion de fichier doivent se faire avant de lancer la session

session_start();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="icon" type="image/png" href="./assets/img/logo-MC.png" />
        <link rel="stylesheet" href="./assets/css/index.css">

        <title>Module de Connexion | Accueil</title>
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

        <section class="hero">
            <h1>Bienvenue</h1>

            <p>Vous êtes sur le module de connexion, vous pouvez vous inscrire, vous connecter et modifier votre profil.</p>
            
            <p>Bonne visite !</p>
        </section>

        <section class="content">
            <div class="container">
                <div class="form">
                    <div onclick="location.href='inscription.php';" class="button">Inscription</div>
                    <div onclick="location.href='connexion.php';" class="button">Connexion</div>
                </div>
                <p>Voir le <a href="https://github.com/augustin-yvon/module-connexion" target="_blank" title="repo github">code</a></p>
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