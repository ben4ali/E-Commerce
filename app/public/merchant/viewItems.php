<?php

use model\Item;
use model\Business;

require_once "../../model/Item.php";
require_once "../../model/Business.php";

session_start();

$items = [];
$categories = [];

if(!isset($_SESSION['itemListData'])) {
    $business = null;
    if($_SESSION['business'] instanceof Business) {
        $_SESSION['error'] .= 'Problème lors de la recherche de vos produits. Votre ID: ' . $_SESSION['business']->getId();
    }
} else {
    if(isset($_SESSION['itemListData']['items'])){
        $items = $_SESSION['itemListData']['items'];
        $categories = $_SESSION['itemListData']['categories'];
    } else {
        $_SESSION['error'] = "Vous avez aucun produit dans votre magasin! Aller sur la page d'ajout d'item!";
    }
}
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
                <h1>Overview - Produits</h1>
                <a href="" class="text-decoration-none btn-lg account-button">
                    <img class="rounded-circle" src="../images/profiles/AntoineLangevin.png" alt="logo" width="40px"
                         height="40px">
                    Antoine Langevin
                </a>
            </div>
            <merchant-top-bar></merchant-top-bar>

            <!-- main body -->
            <div>
                <div class="item-list-table p-4 shadow rounded bg-white">
                    <h2>Détails des items</h2>
                    <p class="font-weight-bold text-danger">
                        <?php
                        if (!empty($_SESSION['error'])) {
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        } ?>
                    </p>
                    <p class="font-weight-bold text-danger">
                        <?php
                        if (!empty($_SESSION['message'])) {
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                        } ?>
                    </p>
                    <?php if (!empty($items)): ?>
                    <table id="itemTable" class="table table-bordered table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>UPC</th>
                            <th>Nom</th>
                            <th>Date d'ajout</th>
                            <th>Prix Magasin</th>
                            <th>Quantité en stock</th>
                            <th>Quantité vendu</th>
                            <th>Catégorie</th>
                            <th>Voir Page</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $item): ?>
                            <?php if ($item instanceof Item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item->getId()) ?></td>
                                    <td><?= htmlspecialchars($item->getUpc()) ?></td>
                                    <td><?= htmlspecialchars($item->getName()) ?></td>
                                    <td><?= htmlspecialchars($item->getRegisteredDate()->format('Y-m-d')) ?></td>
                                    <td><?= htmlspecialchars($item->getPrice()) ?></td>
                                    <td><?= htmlspecialchars($item->getQuantity()) ?></td>
                                    <?php
                                    $sold = $_SESSION['itemListData']['soldItems'][$item->getId()]['total_sold'] ?? 0;
                                    ?>
                                    <td><?= htmlspecialchars($sold) ?></td>
                                    <td><?= htmlspecialchars($categories[$item->getCategoryId()]) ?></td>
                                    <td><a href="../produit-selection.php?id=<?php echo $item->getId(); ?>"
                                           class="btn-primary btn-lg">Page</a></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    <a class="btn-lg btn-primary" href="addItem.php">Ajouter un produit</a>
                    <script>
                        $(document).ready(function () {
                            $('#itemTable').DataTable();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>