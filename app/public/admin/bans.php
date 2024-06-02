<?php

use model\Ban;
use model\DAO\BanDAO;

include_once("../../model/DAO/BanDAO.php");
include_once("../../model/Ban.php");

session_start();
$banList = BanDAO::getInstance()->findAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Base dep. -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <script src="../javascript/adminScripts.js"></script>
    <script src="../javascript/components/AdminSideBarComponent.js"></script>
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
        <admin-side-bar class="col-md-2 d-none d-md-block sidebar"
                        imgSrc="../images/icons/shopNestIconTransparent.png"></admin-side-bar>

        <!-- Options du haut -->
        <div class="col-md-10">
            <div class="top-bar d-flex justify-content-between align-items-center">
                <h1>Gestionnaire Utilisateurs</h1>
                <a href="" class="text-decoration-none btn-lg account-button">
                    <img class="rounded-circle" src="../images/profiles/AntoineLangevin.png" alt="logo" width="40px"
                         height="40px">
                    <?php echo $_SESSION['user']->getFirstName() . ' ' . $_SESSION['user']->getLastName(); ?>
                </a>
            </div>
            <div>
                <div class="ban-list-table p-4 shadow rounded bg-white">
                    <h2 class="text-left">Liste des banissements</h2>
                    <p class="font-weight-bold text-success">
                        <?php
                        if (!empty($_SESSION['message'])) {
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                        } ?>
                    </p>
                    <?php if (!empty($banList)): ?>
                        <table id="banTable" class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Prénom</th>
                                <th>Nom de famille</th>
                                <th>Date</th>
                                <th>Raison</th>
                                <th>Admin</th>
                                <th>Option</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($banList as $ban): ?>
                                <?php if ($ban instanceof Ban): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($ban->getId()) ?></td>
                                        <td><?= htmlspecialchars($ban->getUser()->getFirstName()) ?></td>
                                        <td><?= htmlspecialchars($ban->getUser()->getLastName()) ?></td>
                                        <td><?= htmlspecialchars($ban->getCreatedAt()->format('Y-m-d')) ?></td>
                                        <td><?= htmlspecialchars($ban->getReason()) ?></td>
                                        <td><?= htmlspecialchars($ban->getAdmin()->getFirstName() . ' ' . $ban->getAdmin()->getLastName()) ?></td>
                                        <td>
                                            <form action="../index.php?action=unbanUser" method="post">
                                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                                <button type="submit" name="banId"
                                                        value="<?= htmlspecialchars($ban->getId()) ?>"
                                                        class="btn btn-danger btn-sm">
                                                    Unban
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <script>
                            $(document).ready(function () {
                                $('#banTable').DataTable();
                            });
                        </script>
                        <a href="users.php" class="btn-danger btn-lg">Bannir un utilisateur</a>
                    <?php else: ?>
                        <p>Aucun bannissement trouvé.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>