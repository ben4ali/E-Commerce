<?php

use model\Appeal;
use model\DAO\AppealDAO;

include_once("../../model/DAO/AppealDAO.php");
include_once("../../model/Appeal.php");

session_start();
require_once __DIR__ . '/../../model/User.php';
if(!$_SESSION['user'] instanceof \model\User) {
    echo "Il y a eu un problème lors de la cueillette d'informations";
    exit;
}

$banAppealList = AppealDAO::getInstance()->findAll();

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Ban Appeals</title>
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
                    <?php echo $_SESSION['user']->getFirstName() . ' ' . $_SESSION['user']->getLastName(); ?>
                </a>
            </div>
            <div>
                <div class="ban-list-table p-4 shadow rounded bg-white">
                    <h2 class="text-left">Liste des ban appeals</h2>
                    <?php if (!empty($banAppealList)): ?>
                        <table id="appealTable" class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Prénom</th>
                                <th>Nom de famille</th>
                                <th>Date</th>
                                <th>Raison</th>
                                <th>Option</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($banAppealList as $appeal): ?>
                                <?php if ($appeal instanceof Appeal): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($appeal->getId()) ?></td>
                                        <td><?= htmlspecialchars($appeal->getUser()->getFirstName()) ?></td>
                                        <td><?= htmlspecialchars($appeal->getUser()->getLastName()) ?></td>
                                        <td><?= htmlspecialchars($appeal->getCreatedAt()->format('Y-m-d')) ?></td>
                                        <td><?= htmlspecialchars($appeal->getComment()) ?></td>
                                        <td>
                                            <form action="" method="post">
                                                <button type="submit" name="user_id"
                                                        value="<?= htmlspecialchars($appeal->getId()) ?>"
                                                        class="btn btn-primary">
                                                    Voir
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
                                $('#appealTable').DataTable();
                            });
                        </script>
                    <?php else: ?>
                        <p>Aucun ban appeal trouvé.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>