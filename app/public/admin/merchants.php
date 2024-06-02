<?php

use model\Merchant;
use model\User;
use model\DAO\MerchantDAO;

include_once("../../model/User.php");
include_once("../../model/DAO/MerchantDAO.php");
include_once("../../model/Merchant.php");

session_start();
$merchantList = MerchantDAO::getInstance()->getAll();
$user = null;
if($_SESSION['user'] instanceof User) $user = $_SESSION['user'];
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
        <admin-side-bar class="col-md-2 d-none d-md-block sidebar"
                        imgSrc="../images/icons/shopNestIconTransparent.png"></admin-side-bar>
        <div class="col-md-10">
            <div class="top-bar d-flex justify-content-between align-items-center">
                <h1>Gestionnaire Utilisateurs</h1>
                <a href="" class="text-decoration-none btn-lg account-button">
                    <img class="rounded-circle" src="../images/profiles/AntoineLangevin.png" alt="logo" width="40px"
                         height="40px">
                    <?php echo $user->getFirstName() . ' ' . $user->getLastName(); ?>
                </a>
            </div>
            <div>
                <div class="ban-list-table p-4 shadow rounded bg-white">
                    <h2 class="text-left">Liste des Marchants</h2>
                    <p class="font-weight-bold text-success">
                        <?php
                        if (!empty($_SESSION['message'])) {
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                        } ?>
                    </p>
                    <?php if (!empty($merchantList)): ?>
                        <table id="merchantTable" class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th>Id propriétaire</th>
                                <th>Propriétaire</th>
                                <th>ID compagnie</th>
                                <td>Nom compagnie</td>
                                <th>Téléphone</th>
                                <th>Adresses courriel</th>
                                <th>Option</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($merchantList as $merchant): ?>
                                <?php if ($merchant instanceof Merchant): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($merchant->getId()) ?></td>
                                        <td><?= htmlspecialchars($merchant->getFirstName() . ' ' . $merchant->getLastName()) ?></td>
                                        <td><?= htmlspecialchars($merchant->getBusiness()->getId()) ?></td>
                                        <td><?= htmlspecialchars($merchant->getBusiness()->getName()) ?></td>
                                        <td><?= htmlspecialchars($merchant->getPhoneNumber()) ?></td>
                                        <td><?= htmlspecialchars($merchant->getEmail()) ?></td>
                                        <td>
                                            <form action="../index.php?action=searchFullMerchantInfo" method="get">
                                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                                <button type="submit" name="merchantId"
                                                        value="<?= htmlspecialchars($merchant->getId()) ?>"
                                                        class="btn btn-primary btn-sm">
                                                    Information
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
                                $('#merchantTable').DataTable();
                            });
                        </script>
                    <?php else: ?>
                        <p>Aucun marchant trouvé.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>