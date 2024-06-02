<?php

use model\User;

require_once __DIR__ . '/../../model/User.php';
$title = 'Account';

include_once __DIR__ . '/userIncludes.php';

if (!isset($_SESSION['logged_in'])) {
    // L'utilisateur n'est pas connecté, redirection à la page auth.php.
    header("Location: auth.php");
}
$userName = $_SESSION['logged_in'];
$user = null;
if ($_SESSION['user'] instanceof User) $user = $_SESSION['user'];

?>


    <head>
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Account</title>
    </head>

    <body>
    <div class="container mt-4">
        <div class="row justify-content-center m-2 text-center">
            <div class="col-md-12">
                <h3>Détails du compte</h3>
                <p>Ceci sont vos informations personnelles.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <img alt="profile" title="Profile picture"
                     class="img-circle img-thumbnail isTooltip profileInfo-img"
                     src="../<?php echo $user->getProfilePictureURL(); ?>">
            </div>
            <div class="col-md-6 font-weight-bolder">
                Information
                <div class="tableResponsive">
                    <table class="table table-user-information">
                        <tbody>
                        <tr>
                            <td class="font-weight-bolder">
                                Prénom
                            </td>
                            <td class="text-dark">
                                <?php echo $user->getFirstName(); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bolder">
                                Nom
                            </td>
                            <td class="text-dark">
                                <?php echo $user->getLastName(); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bolder">
                                Rôle
                            </td>
                            <td class="text-dark">
                                Utilisateur
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bolder">
                                Localisation :
                            </td>
                            <td class="text-dark">
                                Montréal, Québec
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bolder">
                                Date de naissance :
                            </td>
                            <td class="text-dark">
                                <?php
                                $birthDate = $user->getBirthDate()->format('Y-m-d');
                                echo $birthDate;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bolder">
                                Dernière connexion :
                            </td>
                            <td class="text-dark">
                                <?php
                                $lastLogin = $user->getLastLoginDate()->format('Y-m-d');
                                echo $lastLogin;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bolder">
                                Biographie :
                            </td>
                            <td class="text-dark">
                                <label>
                                    <textarea rows="4" cols="50"></textarea>
                                </label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <h3>Commentaires</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Produits</th>
                            <th>Évaluations</th>
                            <th>Commentaires</th>
                            <th>Dates</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Produit 1</td>
                            <td>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                            </td>
                            <td>Bon produit!</td>
                            <td>29 Octobre 2023</td>
                        </tr>
                        <tr>
                            <td>Produit 2</td>
                            <td>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star-o"></span>
                            </td>
                            <td>Bel article.</td>
                            <td>30 Octobre 2023</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </body>
<?php include_once("../template/footer.php"); ?>