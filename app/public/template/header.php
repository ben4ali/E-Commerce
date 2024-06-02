<?php
require_once __DIR__ . '/../../CSP.php';
require_once __DIR__ . '/../../Exceptions/CSPNotInitialized.php';

use Exceptions\CSPNotInitialized;

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$CSP = new CSP();
$CSP->initialize();

try {
    $CSP->add(
        ['self', 'http://localhost', 'https://code.jquery.com', 'https://cdnjs.cloudflare.com', 'https://maxcdn.bootstrapcdn.com', 'https://fonts.gstatic.com'], // scripts
        ['self', 'https://cdn.jsdelivr.net', 'https://fonts.googleapis.com']                                      // styles
    );

} catch (CSPNotInitialized $e) {
    // erreur lors de la création du content-security-policy
    $_SESSION['error'] = $e->getMessage();
    header('Location: errorForm.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="eng">

<head>
    <title><?php echo $title; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Liens pour les styles -->
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Liens pour les scripts -->
    <script src="/javascript/script.js" defer></script>
    <script src="/javascript/validation.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
</head>

<header>
    <div id="container">
        <nav>
            <div class="navbar navbar-expand-lg navbar" id="header">
                <div class="container">
                    <img src="/images/icons/shopNestIconTransparent.png" alt="logo" class="logoBase">
                    <a class="navbar-brand navbar-custom" href="/index.php">SHOPNEST</a>
                    <button class="navbar-toggler" type="button">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse mx-xl" id="navbarNav">
                        <form class="form-inline" action="/recherche-produit.php" method="get">
                            <div class="input-group">
                                <label>
                                    <input type="text" class="form-control navbar-text-input" name="search_query"
                                           placeholder="Cherchez un produit...">
                                </label>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-outline-dark">Recherche</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <ul class="navbar-nav ml-auto">
                        <?php
                        if (!isset($_SESSION['logged_in'])) {
                            // We include auth.php & signIn.php actions if the user is not logged in.
                            echo "
                            <li class='nav-item'>
                                <a class='nav-link' href='/user/auth.php'>Connecter</a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link' href='/user/signIn.php'>S'inscrire</a>
                            </li>";
                        }
                        ?>
                        <li class="nav-item">
                            <a href="/user/profile.php" class="nav-link">Compte</a>
                        </li>
                        <?php
                            if (isset($_SESSION['logged_in'])) {
                                echo "
                                <li class='nav-item position-relative'>
                                    <a href='/user/panier.php' class='nav-link'>Panier</a>
                                </li>
                                <li class='nav-item'>
                                    <a class='nav-link' href='/index.php?action=logoutClient'>Déconnexion   </a>
                                </li>";
                            }
                        ?>
                        <li class="nav-item">
                            <img id="darkModeButton" class="darkModeBtn" src="..\images\icons\lightBuble.png" alt="" height="50vh" width="40vh">
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
</html>
