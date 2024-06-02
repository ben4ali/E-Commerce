<?php

use model\Address;
use model\Transaction;
use model\User;

require_once "../../model/Transaction.php";
require_once "../../model/Address.php";
require_once "../../model/User.php";

session_start();

$transactions = $_SESSION['transactionListData'] ?? [];
$users = $_SESSION['userData'] ?? [];
$addresses = $_SESSION['addresses'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="../css/merchant.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../javascript/components/MerchantSideBarComponent.js"></script>
    <script src="../javascript/components/MerchantTopButtonsComponent.js"></script>
    <!-- DataTables dep. -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
</head>
<body>
<div class="container-fluid bg-light">
    <div class="row">
        <!-- Le menu sidebar -->
        <merchant-side-bar class="col-md-2 d-none d-md-block sidebar"
                           imgSrc="../images/icons/shopNestIconTransparent.png"></merchant-side-bar>

        <!-- Options du haut-->
        <div class="col-md-10">
            <div class="top-bar d-flex justify-content-between align-items-center">
                <h1>Overview - Commandes</h1>
                <a href="" class="text-decoration-none btn-lg account-button">
                    <img class="rounded-circle" src="../images/profiles/AntoineLangevin.png" alt="logo" width="40px"
                         height="40px">
                    Antoine Langevin
                </a>
            </div>
            <!-- Macro -->
            <merchant-top-bar></merchant-top-bar>

            <div class="main-body p-4">
                <h2>Liste des commandes</h2>
                <p class="font-weight-bold text-success">
                    <?php
                    if (!empty($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    } ?>
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="transactionTable">
                        <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Méthode de paiement</th>
                            <th>Total</th>
                            <th>Addresse de facturation</th>
                            <th>Utilisateur</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <?php if($transaction instanceof Transaction): ?>
                                <tr>
                                    <td><?= htmlspecialchars($transaction->getId()) ?></td>
                                    <td><?= htmlspecialchars($transaction->getPaymentMethod()) ?></td>
                                    <td>$<?= htmlspecialchars(number_format($transaction->calculate(), 2)) ?></td>
                                    <?php
                                    $address = $addresses[$transaction->getBillingAddress()];
                                    if($address instanceof Address) {
                                        $address = $addresses[$transaction->getBillingAddress()];
                                        echo "<td>{$address->getStreet()}, {$address->getCity()}, {$address->getProvince()}, {$address->getCountry()} {$address->getPostalCode()}</td>";
                                    } else echo "<td>N/A</td>";
                                    ?>
                                    <?php
                                    $user = null;
                                    if($users[$transaction->getUserId()] instanceof User) {
                                        $user = $users[$transaction->getUserId()];
                                        $name = $user->getFirstName() . $user->getLastName();
                                        echo "<td>$name</td>";
                                    } else echo "<td>N/A</td>";
                                    ?>
                                </tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>

            <script>
                $(document).ready(function() {
                    $('#transactionTable').DataTable({
                        "transaction": [[ 1, "asc" ]]
                    });
                });
            </script>

        </div>
    </div>
</div>

</body>
</html>