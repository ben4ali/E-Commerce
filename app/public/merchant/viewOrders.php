<?php
use model\Order;
use model\Transaction;

require_once "../../model/Order.php";
require_once "../../model/Transaction.php";

session_start();

$orders = $_SESSION['orderListData'] ?? [];
$transactions = $_SESSION['transactionListData'] ?? [];

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
                    <table class="table table-bordered table-hover" id="orderTable">
                        <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Détails</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($orders as $order): ?>
                        <?php if($order instanceof Order): ?>
                            <?php
                            // Determine status class based on order status
                            $statusClass = match ($order->getOrderStatus()) {
                                'Delivered' => 'status-delivered',
                                'Pending' => 'status-pending',
                                'Canceled' => 'status-canceled',
                                'Shipped' => 'status-shipped',
                                default => 'bg-secondary',
                            };
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($order->getId()) ?></td>
                                <td class="text-center font-weight-bold <?php echo $statusClass ?>">
                                    <?= htmlspecialchars($order->getOrderStatus()) ?>
                                </td>
                                <td><?= htmlspecialchars($order->getCreatedAt()) ?></td>
                                <!-- Assume $order->getTotal() exists and provides the order total -->
                                <?php if(isset($transactions[$order->getId()]) && $transactions[$order->getId()] instanceof Transaction): ?>
                                    <td>$<?= htmlspecialchars(number_format($transactions[$order->getId()]->calculate(), 2)) ?></td>
                                <?php else: ?>
                                    <td>N/A</td>
                                <?php endif; ?>
                                <td>
                                    <!-- Assume there's a route or script to handle order detail fetching -->
                                    <a href="orderDetails.php?orderId=<?= $order->getId() ?>" class="btn btn-primary">Détails</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    $('#orderTable').DataTable({
                        "order": [[ 1, "asc" ]]
                    });
                });
            </script>

        </div>
    </div>
</div>

</body>
</html>