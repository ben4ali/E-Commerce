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

if(!isset($_SESSION['orders']) && !isset($_GET['id'])) { // direct access (probably)
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
}
// data is here
$order = null;
if($_SESSION['orders'][$_GET['id']] instanceof Order) {
    $order = $_SESSION['orders'][$_GET['id']];
    unset($_SESSION['orders']);
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
                <h1>Commande NO: <?php echo $order->getId(); ?></h1>
                <a href="" class="text-decoration-none btn-lg account-button">
                    <img class="rounded-circle" src="../images/profiles/AntoineLangevin.png" alt="logo" width="40px"
                         height="40px">
                    <?php echo $_SESSION['user']->getFirstName() . ' ' . $_SESSION['user']->getLastName(); ?>
                </a>
            </div>
            <!-- Order data -->
            <div class="container">
                <div class="row">
                    <div class="col-md-12 mt-4">
                        <h1>Détails de la commande</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h2>Informations générales</h2>
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>ID de la commande:</th>
                                <td><?php echo $order->getId(); ?></td>
                            </tr>
                            <tr>
                                <th>ID de l'utilisateur:</th>
                                <td><?php echo $order->getUserId(); ?></td>
                            </tr>
                            <!-- Add more order details here as needed -->
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h2>Informations livraison</h2>
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>Adresse: </th>
                                <td><?php echo $order->getDeliveryAddressId(); ?></td>
                            </tr>
                            <!-- Add more delivery details here as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h2>Détails de la transactions</h2>
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>ID:</th>
                                <td><?php echo $order->getTransactionId(); ?></td>
                            </tr>
                            <!-- Add more transaction details here as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h2>Other Order Details</h2>
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>Created At:</th>
                                <td><?php echo $order->getCreatedAt(); ?></td>
                            </tr>
                            <tr>
                                <th>Order Status:</th>
                                <td><?php echo $order->getOrderStatus(); ?></td>
                            </tr>
                            <tr>
                                <th>Special Instruction:</th>
                                <td><?php echo $order->getSpecialInstruction(); ?></td>
                            </tr>
                            <!-- Add more order details here as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>
</body>
</html>