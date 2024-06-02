<?php

use model\Address;
use model\DAO\AddressDAO;
use model\DAO\OrderDAO;
use model\DAO\TransactionDAO;
use model\Item;
use model\Order;
use model\Transaction;
use model\User;

require_once __DIR__ . '/../../model/DAO/OrderDAO.php';
require_once __DIR__ . '/../../model/DAO/TransactionDAO.php';
require_once __DIR__ . '/../../model/DAO/AddressDAO.php';
require_once __DIR__ . '/../../model/Item.php';
require_once __DIR__ . '/../../model/User.php';
require_once __DIR__ . '/../../model/Transaction.php';
require_once __DIR__ . '/../../model/Order.php';
require_once __DIR__ . '/../../model/Address.php';

$title = "Commandes";
include_once __DIR__ . '/userIncludes.php';

$user = null;
if ($_SESSION['user'] instanceof User) $user = $_SESSION['user'];

function displayProductCard(Item $product): void
{
    echo <<<HTML
    <div class="card-body">
        <div class="row ">
            <div class="col-md-2">
                <img src="../images/produits/{$product->getImageUrl()}" alt="Image du produit" class="imgProduitCommande">
            </div>
            <div class="col-md-6">
                <a href="../produit-selection.php?id={$product->getId()}">{$product->getName()}</a>
            </div>
            <div class="col-md-3 ml-4 mt-4">
                <button type="button" class="btn btn-outline-warning">En cours</button>
            </div>
        </div>
    </div>
HTML;
}

function displayOrderInformation(Order $order, Transaction $transaction, Address $address, array $items): void
{
    echo <<<HTML
    <div class="card my-4">
        <div class="card-header m-1">
            <table class="table">
                <thead>
                <th scope="col">Commande placée</th>
                <th scope="col">Total</th>
                <th scope="col">Envoyée à</th>
                <th scope="col">Id de Commande#</th>
                </thead>
                <tbody>
                <td>Octobre 27, 2023</td>
                <td>{$transaction->calculate()}$</td>
                <td>{$address->getStreet()}, {$address->getCity()}, {$address->getProvince()}, {$address->getCountry()} {$address->getPostalCode()}</td>
                <td>{$order->getId()}</td>
                </tbody>
            </table>
        </div>
HTML;

    foreach ($items as $product) {
        if ($product instanceof Item) {
            displayProductCard($product);
        }
    }

    echo <<<HTML
        <div class="card-footer">
            <a href="#">
                <button type="button" class="btn btn-outline-dark">Archiver la commande</button>
            </a>
        </div>
    </div>
HTML;
}

?>

<body>
<div class="container">
    <div class="jumbotron">
        <!-- start -->
        <?php
        $user = $_SESSION['user'];
        $orderDAO = new OrderDAO();
        $transactionDAO = new TransactionDAO();
        $addressDAO = new AddressDAO();
        $orders = $orderDAO->getByUserId($user->getId());

        foreach ($orders as $order) {
            if ($order instanceof Order) {
                $items = $orderDAO->getItemsByOrderId($order->getId());
                $transaction = $transactionDAO->getById($order->getTransactionId());
                $address = $addressDAO->getById($order->getDeliveryAddressId());
                if ($transaction instanceof Transaction && $address instanceof Address) {
                    displayOrderInformation($order, $transaction, $address, $items);
                }
            }

        }
        ?>
    </div>
</div>
</body>

<?php
include_once("../template/footer.php");
?>
