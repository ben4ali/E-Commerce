<?php
// Données relatives aux commandes, produits vendus, transactions et total des ventes doivent ête récupérées.
session_start();
$dashboardData = $_SESSION['dashboardData'] ?? null;
$endDate = new DateTime();
$startDate = (new DateTime())->modify('-30 days');
$displayStartDate = $startDate->format('Y-m-d');
$displayEndDate = $endDate->format('Y-m-d');
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
    <script src="../javascript/components/MerchantTopButtonsComponent.js"></script>
    <script src="../javascript/components/MerchantSideBarComponent.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Le menu sidebar -->
        <merchant-side-bar class="col-md-2 d-none d-md-block sidebar"
                        imgSrc="../images/icons/shopNestIconTransparent.png"></merchant-side-bar>

        <!-- Options du haut-->
        <div class="col-md-10 bg-light">
            <div class="top-bar d-flex justify-content-between align-items-center">
                <h1>Overview - Dashboard</h1>
                <a href="" class="text-decoration-none btn-lg account-button">
                    <img class="rounded-circle" src="../images/profiles/AntoineLangevin.png" alt="logo" width="40px"
                         height="40px">
                    Antoine Langevin
                </a>
            </div>
            <merchant-top-bar></merchant-top-bar>
            <div class="text-center p-2">
                <h4>Statistiques Rapides - <?= $displayStartDate ?> à <?= $displayEndDate ?></h4>
                <p>Valeurs comparées au dernier mois.</p>
            </div>
            <div class="row">
                <?php if ($dashboardData): ?>
                    <div class="col-lg-6 mb-4">
                        <div class="box">
                            <p class="font-weight-bold">Commandes: <span id="ordersCount">0</span> (<span class="text-success" id="ordersChange">0</span><span class="text-success">%</span>)</p>                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="box">
                            <p class="font-weight-bold">Transactions: <span id="transactionsCount">0</span> (<span class="text-success" id="transactionsChange">0</span><span class="text-success">%</span>)</p>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="box">
                            <p class="font-weight-bold">Produits vendus: <span id="soldProductsCount">0</span> (<span class="text-success" id="soldProductsChange">0</span><span class="text-success">%</span>)</p>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="box">
                            <p class="font-weight-bold">Total des ventes: <span id="totalRevenueCount">0</span> CAD$ (<span class="text-success" id="totalRevenueChange">0</span><span class="text-success">%</span>)</p>
                        </div>
                    </div>
                    <script>
                        function animateValue(id, start, end, duration, isPercentage = false) {
                            let current = start;
                            let range = end - start;
                            let increment = end > start ? 1 : -1;
                            let stepTime = Math.abs(duration / range);
                            let obj = document.getElementById(id);

                            let timer = setInterval(function() {
                                current += increment;
                                increment *= 1.05;
                                if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                                    current = end;
                                    clearInterval(timer);
                                }
                                obj.innerHTML = isPercentage ? current.toFixed(2) : Math.round(current);
                            }, stepTime);
                        }

                        animateValue("ordersCount", 0, <?= $dashboardData['currentData']['orders'] ?>, 2000);
                        animateValue("ordersChange", 0, <?= $dashboardData['percentageChange']['orders'] ?>, 2000, true);
                        animateValue("transactionsCount", 0, <?= $dashboardData['currentData']['transactions'] ?>, 2000);
                        animateValue("transactionsChange", 0, <?= $dashboardData['percentageChange']['transactions'] ?>, 2000, true);
                        animateValue("soldProductsCount", 0, <?= $dashboardData['currentData']['soldProducts'] ?>, 2000);
                        animateValue("soldProductsChange", 0, <?= $dashboardData['percentageChange']['soldProducts'] ?>, 2000, true);
                        animateValue("totalRevenueCount", 0, <?= $dashboardData['currentData']['totalRevenue'] ?>, 2000);
                        animateValue("totalRevenueChange", 0, <?= $dashboardData['percentageChange']['totalRevenue'] ?>, 2000, true);
                    </script>

                <?php else: ?>
                    <div class="col-lg-6 mb-4">
                        <div class="box">
                            <p>No data available.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

</body>
</html>