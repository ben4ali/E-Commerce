<?php

use model\Address;
use model\Order;
use model\Review;
use model\Transaction;
use model\User;

include_once("../../model/Address.php");
include_once("../../model/Transaction.php");
include_once("../../model/Order.php");
include_once("../../model/Review.php");
include_once("../../model/User.php");

session_start();
// init
$user = null;
$transactions = null;
$orders = null;
$reviews = null;
$shippingAddresses = [];
$billingAddresses = [];
$deliveryAddresses = [];
$userData = null;

if (isset($_SESSION['searchFullUserInfo'])) {
    // Push the data to the variables.
    $userData = $_SESSION['searchFullUserInfo'];

    if (isset($userData['user']) && $userData['user'] instanceof User) {
        $user = $userData['user'];
    }

    $transactions = $userData['transactions'] ?? null;
    $orders = $userData['orders'] ?? null;
    $reviews = $userData['reviews'] ?? null;
    $shippingAddresses = $userData['shippingAddresses'] ?? [];
    $billingAddresses = $userData['billingAddresses'] ?? [];
    $deliveryAddresses = $userData['deliveryAddresses'] ?? [];

    unset($_SESSION['searchFullUserInfo']);
} else if (isset($_SESSION['message'])) {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Utilisateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../javascript/adminScripts.js"></script>
    <script src="../javascript/components/AdminSideBarComponent.js"></script>
</head>
<body>
<div class="container-fluid bg-light">
    <div class="row">
        <!-- Sidebar -->
        <admin-side-bar class="col-md-2 d-none d-md-block sidebar"
                        imgSrc="../images/icons/shopNestIconTransparent.png"></admin-side-bar>

        <!-- Main Content -->
        <div class="col-md-10">
            <!-- Top Bar -->
            <div class="top-bar d-flex justify-content-between align-items-center">
                <h1>Information utilisateur</h1>
                <a href="" class="text-decoration-none btn-lg account-button">
                    <img class="rounded-circle" src="../images/profiles/AntoineLangevin.png" alt="logo" width="40px"
                         height="40px">
                    <?php echo $_SESSION['user']->getFirstName() . ' ' . $_SESSION['user']->getLastName(); ?>
                </a>
            </div>

            <!-- User Search Form -->
            <div class="ban-list-table p-4 shadow rounded bg-white">
                <p class="font-weight-bold text-danger">
                    <?php
                    if (isset($msg)) {
                        echo $msg;
                    } ?>
                </p>
                <h2 class="text-left">Recherche d'utilisateur</h2>
                <form action="../index.php?action=searchFullUserInfo" method="post" id="searchUserForm">
                    <p>Vous devez remplir au moins une des entrées suivantes.</p>
                    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                    <!-- Form inputs and buttons -->
                    <label>
                        <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Prénom" name="firstName">
                    </label>
                    <label>
                        <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Nom de famille"
                               name="lastName">
                    </label>
                    <label>
                        <input type="email" class="form-control mb-2 mr-sm-2" placeholder="Courriel" name="email">
                    </label>
                    <label>
                        <input type="tel" class="form-control mb-2 mr-sm-2" placeholder="Numéro de téléphone"
                               name="phone">
                    </label>
                    <button type="submit" class="btn btn-primary mb-2">Chercher</button>
                </form>
            </div>

            <!-- User Details and Transactions -->
            <?php if (!empty($userData)): ?>
                <?php foreach ($userData as $datum): ?>
                    <?php $user = $datum['user']; ?>
                    <!-- User Details -->
                    <div class="shadow rounded bg-white p-4">
                        <h3>Détails de l'utilisateur</h3>
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Prénom</th>
                                <th>Nom de famille</th>
                                <th>Courriel</th>
                                <th>Date de naissance</th>
                                <th>Numéro de téléphone</th>
                                <th>Dernière connexion</th>
                                <th>Avertissements</th>
                                <th>Rôle</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><?= htmlspecialchars($user->getFirstName()) ?></td>
                                <td><?= htmlspecialchars($user->getLastName()) ?></td>
                                <td><?= htmlspecialchars($user->getEmail()) ?></td>
                                <td><?= htmlspecialchars($user->getBirthDate()->format('Y-m-d')) ?></td>
                                <td><?= htmlspecialchars($user->getPhoneNumber()) ?></td>
                                <td><?= htmlspecialchars($user->getLastLoginDate()->format('Y-m-d H:i:s')) ?></td>
                                <td><?= htmlspecialchars($user->getWarnings()) ?></td>
                                <td><?= htmlspecialchars($user->getRole()) ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                <!-- boutons avertir, bannir et voir information -->
                    <div class="d-flex">
                        <button class="btn btn-primary mb-2 show-more-button" data-index="<?= $user->getId(); ?>">
                            Montrer plus
                        </button>
                        <!-- Bouton pour montrer le reste des informations de l'utilisateur. -->
                        <form action="../index.php?action=addWarning" method="post">
                            <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                            <input type="hidden" name="warn" value="<?php echo $user->getId(); ?>">
                            <button type="submit" class="btn btn-warning">Avertir</button>
                        </form>
                        <?php $_SESSION['searchedUser'] = $user; ?>
                        <a href="banForm.php?id=<?php echo $user->getId(); ?>" class="btn btn-danger">Bannir</a>
                    </div>
                <!-- fin options -->
                    <div class="hidden-sections-<?= $user->getId(); ?> hidden-section">
                        <!-- Commandes -->
                        <div class="shadow rounded bg-white p-4">
                            <h3>Liste des commandes</h3>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Id de transaction</th>
                                    <th>Adresse de livraison</th>
                                    <th>Commandé le</th>
                                    <td>Voir</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $orders = $datum['orders'] ?? []; ?>
                                <?php foreach ($orders as $order): ?>
                                    <?php if ($order instanceof Order): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($order->getId()) ?></td>
                                            <td><?= htmlspecialchars($order->getOrderStatus()) ?></td>
                                            <td><?= htmlspecialchars($order->getTransactionId()) ?></td>
                                            <!-- TODO: régler problème d'affichage du "Commandé le" -->
                                            <?php if (isset($deliveryAddresses[$order->getDeliveryAddressId()]) && $deliveryAddresses[$order->getDeliveryAddressId()] instanceof Address): ?>
                                                <td><?= htmlspecialchars($deliveryAddresses[$order->getDeliveryAddressId()]->toString()) ?></td>
                                            <?php endif ?>
                                            <td><?= htmlspecialchars($order->getCreatedAt()) ?></td>
                                            <?php $_SESSION['orders'][$order->getId()] = $order; ?>
                                            <td><a href="order.php?id=<?php echo $order->getId(); ?>" class="btn btn-primary">Information</a></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Transactions -->
                        <div class="shadow rounded bg-white p-4">
                            <h3>Liste des transactions</h3>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Total</th>
                                    <th>Shipping Address</th>
                                    <th>Billing Address</th>
                                    <th>Mode de paiement</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $transactions = $datum['transactions'] ?? []; ?>
                                <?php foreach ($transactions as $transaction): ?>
                                    <?php if ($transaction instanceof Transaction): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($transaction->getId()) ?></td>
                                            <td><?= htmlspecialchars($transaction->getTotal()) ?>$ CAD</td>
                                            <td><?= htmlspecialchars($transaction->getShippingAddress()) ?></td>
                                            <td><?= htmlspecialchars($transaction->getBillingAddress()) ?></td>
                                            <td><?= htmlspecialchars($transaction->getPaymentMethod()) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Reviews -->
                        <div class="shadow rounded bg-white p-4">
                            <h3>Liste des reviews</h3>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Commentaire</th>
                                    <th>Numbre d'étoiles</th>
                                    <th>ID du produit</th>
                                    <th>Déposé le</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $reviews = $datum['reviews'] ?? []; ?>
                                <?php foreach ($reviews as $review): ?>
                                    <?php if ($review instanceof Review): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($review->getId()) ?></td>
                                            <td><?= htmlspecialchars($review->getComment()) ?></td>
                                            <td><?= htmlspecialchars($review->getNumberStars()) ?></td>
                                            <td><?= htmlspecialchars($review->getProductId()) ?></td>
                                            <td><?= htmlspecialchars($review->getCreatedAt()->format('Y-m-d')) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.show-more-button').forEach(function (button) {
            button.addEventListener('click', function () {
                var index = this.getAttribute('data-index');
                var hiddenSections = document.querySelectorAll('.hidden-sections-' + index);
                hiddenSections.forEach(function (section) {
                    if (section.classList.contains('hidden')) {
                        section.classList.remove('hidden');
                        section.classList.add('visible');
                    } else {
                        section.classList.remove('visible');
                        section.classList.add('hidden');
                    }
                });
                this.textContent = section.classList.contains('hidden') ? 'Montrer plus' : 'Montrer moins';
            });
        });
    });
</script>
</body>
</html>