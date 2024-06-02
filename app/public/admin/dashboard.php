<?php
require_once __DIR__ . '/../../model/User.php';
session_start();
if(!$_SESSION['user'] instanceof \model\User) {
    echo "Il y a eu un problème lors de la cueillette d'informations";
    exit;
}

$adminDashboardData = $_SESSION['adminDashboardData'] ?? null;
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../javascript/adminScripts.js"></script>
    <script src="../javascript/components/AdminSideBarComponent.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Le menu sidebar -->
        <admin-side-bar class="col-md-2 d-none d-md-block sidebar"
                        imgSrc="../images/icons/shopNestIconTransparent.png"></admin-side-bar>

        <!-- Options du haut-->
        <div class="col-md-10 bg-light">
            <div class="top-bar d-flex justify-content-between align-items-center">
                <h1>Overview - Dashboard</h1>
                <a href="" class="text-decoration-none btn-lg account-button">
                    <img class="rounded-circle" src="../images/profiles/AntoineLangevin.png" alt="logo" width="40px"
                         height="40px">
                    <?php echo $_SESSION['user']->getFirstName() . ' ' . $_SESSION['user']->getLastName(); ?>
                </a>
            </div>
            <div class="module-buttons text-decoration-none text-center">
                <a href="users.php" class="module-button btn-users"><i class="fas fa-chart-line"></i>
                    Utilisateurs</a>
                <a href="merchants.php" class="module-button btn-merchants"><i class="fas fa-shopping-cart"></i>
                    Marchants</a>
                <a href="bans.php" class="module-button btn-bans"><i class="fas fa-user"></i> Bans</a>
                <a href="appeals.php" class="module-button btn-appeals"><i class="fas fa-industry"></i> Ban
                    Appeals</a>
            </div>
            <div class="text-center p-2">
                <h4>Statistiques Rapides - <?= $displayStartDate ?> à <?= $displayEndDate ?></h4>
                <p>Valeurs comparées au dernier mois.</p>
            </div>
            <div class="row">
                <?php if ($adminDashboardData): ?>
                    <div class="col-lg-6 mb-4">
                        <div class="box">
                            <p class="font-weight-bold">Utilisateurs: <span id="usersCount">0</span> (<span class="text-success" id="usersChange">0</span><span class="text-success">%</span>)</p>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="box">
                            <p class="font-weight-bold">Marchands: <span id="merchantsCount">0</span> (<span class="text-success" id="merchantsChange">0</span><span class="text-success">%</span>)</p>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="box">
                            <p class="font-weight-bold">Produits vendus: <span id="soldProductsCount">0</span> (<span class="text-success" id="soldProductsChange">0</span><span class="text-success">%</span>)</p>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="box">
                            <p class="font-weight-bold">Total des ventes: <span id="totalSellsCount">0</span> CAD$ (<span class="text-success" id="totalSellsChange">0</span><span class="text-success">%</span>)</p>
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

                        animateValue("usersCount", 0, <?= $adminDashboardData['currentData']['totalUsers'] ?? 0 ?>, 2000);
                        animateValue("usersChange", 0, <?= $adminDashboardData['percentageChange']['totalUsers'] ?? 0 ?>, 2000, true);
                        animateValue("merchantsCount", 0, <?= $adminDashboardData['currentData']['totalMerchants'] ?? 0 ?>, 2000);
                        animateValue("merchantsChange", 0, <?= $adminDashboardData['percentageChange']['totalMerchants'] ?? 0 ?>, 2000, true);
                        animateValue("soldProductsCount", 0, <?= $adminDashboardData['currentData']['soldProducts'] ?? 0 ?>, 2000);
                        animateValue("soldProductsChange", 0, <?= $adminDashboardData['percentageChange']['soldProducts'] ?? 0 ?>, 2000, true);
                        animateValue("totalSellsCount", 0, <?= $adminDashboardData['currentData']['totalSells'] ?? 0 ?>, 2000);
                        animateValue("totalSellsChange", 0, <?= $adminDashboardData['percentageChange']['totalSells'] ?? 0 ?>, 2000, true);
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