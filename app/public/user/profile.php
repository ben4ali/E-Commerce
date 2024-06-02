<?php

use model\User;

require_once __DIR__ . '/../../model/User.php';
$title = 'Profil Utilisateur';

include_once __DIR__ . '/userIncludes.php';

if (!isset($_SESSION['logged_in'])) {
    // L'utilisateur n'est pas connecté, redirection à la page auth.php.
    header("Location: auth.php");
}
$user = null;
if ($_SESSION['user'] instanceof User) $user = $_SESSION['user'];
?>

    <body>

    <div class="container mt-4">
        <div class="row justify-content-center m-2 text-center">
            <div class="col-md-12">
                <h3>Votre compte - <?php echo $user->getFirstName() . ' ' . $user->getLastName(); ?></h3>
                <p>Vous pouvez visualiser ou changer les informations de votre compte ici.</p>
            </div>
            <div class="btn-light mt-4 mx-2 col-md-4 rounded-lg profile-btn">
                <a href="commandes.php" class="text-decoration-none container d-flex align-items-center">
                    <img src="../images/icons/ordersIconTransparent.png" height="100px" width="100px" alt="Orders">
                    <div class="text-dark">
                        <h5>Commandes</h5>
                        <p>Voir vos commandes précédentes</p>
                    </div>
                </a>
            </div>
            <div class="btn-light mt-4 mx-2 col-md-4 rounded-lg profile-btn">
                <a href="payments.php" class="text-decoration-none container d-flex align-items-center">
                    <img class="card-img-right" src="../images/icons/yourPaymentsIconTransparent.png" height="100px"
                         width="100px" alt="Vos Paiements">
                    <div class="text-dark">
                        <h5>Paiements</h5>
                        <p>Voir vos informations de paiements</p>
                    </div>
                </a>
            </div>
            <div class="btn-light mt-4 mx-2 col-md-4 rounded-lg profile-btn">
                <a href="accountInfo.php" class="text-decoration-none container d-flex align-items-center">
                    <img class="card-img-right" src="../images/icons/personalInformationIconTransparent.png"
                         height="100px"
                         width="100px" alt="Informations Personnelles">
                    <div class="text-dark">
                        <h5>Informations Compte</h5>
                        <p>Voir vos informations personnelles</p>
                    </div>
                </a>
            </div>
            <div class="btn-light mt-4 mx-2 col-md-4 profile-btn">
                <a href="authentificationSecu.php" class="text-decoration-none container d-flex align-items-center">
                    <img class="card-img-right" src="../images/icons/loginAndSecurityIconTransparent.png" height="100px"
                         width="100px" alt="Connexion & Sécurité">
                    <div class="text-dark">
                        <h5>Authentication & Sécurité</h5>
                        <p>Authentification et sécurité du compte</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    </div>
    </body>

<?php include("../template/footer.php"); ?>