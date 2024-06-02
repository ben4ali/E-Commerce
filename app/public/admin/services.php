<?php

require_once __DIR__ . '/../../model/User.php';
session_start();
if(!$_SESSION['user'] instanceof \model\User) {
    echo "Il y a eu un problème lors de la cueillette d'informations";
    exit;
}
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
        <div class="col-md-10">
            <!-- Top Bar -->
            <div class="top-bar d-flex justify-content-between align-items-center">
                <h1>Services</h1>
                <a href="" class="text-decoration-none btn-lg account-button">
                    <img class="rounded-circle" src="../images/profiles/AntoineLangevin.png" alt="logo" width="40px"
                         height="40px">
                    <?php echo $_SESSION['user']->getFirstName() . ' ' . $_SESSION['user']->getLastName(); ?>
                </a>
            </div>
            <div>
                <?php
                if (isset($_SESSION['message'])) {
                    if ($_SESSION['message']) $msg = 'done'; else $msg = 'undone';
                    echo "Opération terminée, code: " . $msg;
                    unset($_SESSION['message']);
                }
                ?>
                <form action="../index.php?action=adminPopulateBD" method="post" name="populateDB">
                    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                    <button class="btn-lg btn-primary" type="submit">Ajouter des données fausses au BD</button>
                </form>
                <div>
                    <a href="admins.php" class="btn-lg btn-primary text-decoration-none">Gestion des administrateurs</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>