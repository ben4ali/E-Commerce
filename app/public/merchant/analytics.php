<?php

use model\DAO\OrderDAO;
require_once "../../model/Order.php";
require_once "../../model/DAO/OrderDAO.php";

session_start();
$ordersData = OrderDAO::getInstance()->getOrdersCountLast60Days();
$weeklyOrderCounts = array();
$dateLabels = array();
$weekSum = 0;
$weekStart = null;

foreach ($ordersData as $date => $orderCount) {
    $weekSum += $orderCount;
    if ($weekStart === null) {
        $weekStart = strtotime($date);
    }
    $weekEnd = strtotime($date);
    $dateLabels[] = date('M d', $weekStart) . ' - ' . date('M d', $weekEnd);

    if ($weekEnd - $weekStart >= 7 * 24 * 3600) {
        $weeklyOrderCounts[] = $weekSum;
        $weekSum = 0;
        $weekStart = null;
    }
}

if ($weekSum > 0) {
    $weeklyOrderCounts[] = $weekSum;
}

$weeklyDateLabels = array_slice($dateLabels, 0, count($weeklyOrderCounts));
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
    <!-- Include Chartist.js JavaScript -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <h2>Statistiques de votre boutique</h2>
                <p class="font-weight-bold text-success">
                    <?php
                    if (!empty($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    } ?>
                </p>
                <!-- Main body -- Graphs -->

                <!-- START -->
                <h2>Nombre de commandes</h2>
                <canvas id="orderChart"></canvas>
                <script>
                    // Data
                    var labels = <?php echo json_encode($weeklyDateLabels); ?>;
                    var data = <?php echo json_encode($weeklyOrderCounts); ?>;
                    var formattedData = data.map(function(count) {
                        return 'Orders: ' + count;
                    });

                    var ctx = document.getElementById('orderChart').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Number of Orders',
                                data: data,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgb(192,75,75)',
                                borderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 8,
                                pointBackgroundColor: 'rgb(192,75,75)',
                                pointBorderColor: 'rgb(192,75,75)',
                                pointHoverBackgroundColor: 'rgb(192,75,75)',
                                pointHoverBorderColor: 'rgba(75, 192, 192, 1)',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                xAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Week'
                                    }
                                }],
                                yAxes: [{
                                    beginAtZero: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Number of Orders'
                                    }
                                }]
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    enabled: true,
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        label: function(tooltipItem, data) {
                                            return formattedData[tooltipItem.index];
                                        }
                                    }
                                }
                            },
                            animation: {
                                duration: 2000,
                                onComplete: function(animation) {
                                    myChart.options.hover.mode = 'index';
                                }
                            }
                        }
                    });
                </script>
                <!-- END -->
            </div>
        </div>
    </div>
</div>
</body>
</html>