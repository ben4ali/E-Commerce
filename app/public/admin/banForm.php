<?php

use model\User;

require_once("../../model/User.php");
session_start();
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
<div class="container-fluid bg-light">
    <div class="row">
        <!-- Le menu sidebar -->
        <admin-side-bar class="col-md-2 d-none d-md-block sidebar"
                        imgSrc="../images/icons/shopNestIconTransparent.png"></admin-side-bar>
        <!-- The sidebar component will be included here as before -->
        <div class="col-md-10">
            <div class="top-bar d-flex justify-content-between align-items-center">
                <h1>Formulaire de banissement</h1>
                <a href="" class="text-decoration-none btn-lg account-button">
                    <img class="rounded-circle" src="../images/profiles/AntoineLangevin.png" alt="logo" width="40px"
                         height="40px">
                    <?php echo $_SESSION['user']->getFirstName() . ' ' . $_SESSION['user']->getLastName(); ?>
                </a>
            </div>

            <!-- Ban User Form Section -->
            <div>
                <!-- Table avec les informations de l'utilisateur -->
                <?php if (isset($_SESSION['searchedUser'])): ?>
                    <?php
                    $searchedUser = $_SESSION['searchedUser'];
                    unset($_SESSION['searchedUser']);
                    ?>
                    <div class="user-search-form p-4 shadow rounded bg-white">
                        <h3>Détails de l'utilisateur</h3>
                        <?php if ($searchedUser instanceof User): ?>
                            <table class="table table-hover table-bordered">
                                <tbody>
                                <tr>
                                    <th>Prénom</th>
                                    <td><?= htmlspecialchars($searchedUser->getFirstName()) ?></td>
                                </tr>
                                <tr>
                                    <th>Nom de famille</th>
                                    <td><?= htmlspecialchars($searchedUser->getLastName()) ?></td>
                                </tr>
                                <tr>
                                    <th>Courriel</th>
                                    <td><?= htmlspecialchars($searchedUser->getEmail()) ?></td>
                                </tr>
                                <tr>
                                    <th>Date de naissance</th>
                                    <td><?= htmlspecialchars($searchedUser->getBirthDate()->format('Y-m-d')) ?></td>
                                </tr>
                                <tr>
                                    <th>Numéro de téléphone</th>
                                    <td><?= htmlspecialchars($searchedUser->getPhoneNumber()) ?></td>
                                </tr>
                                <tr>
                                    <th>Dernière connexion</th>
                                    <td><?= htmlspecialchars($searchedUser->getLastLoginDate()->format('Y-m-d H:i:s')) ?></td>
                                </tr>
                                <tr>
                                    <th>Avertissements</th>
                                    <td><?= htmlspecialchars($searchedUser->getWarnings()) ?></td>
                                </tr>
                                <tr>
                                    <th>Rôle</th>
                                    <td><?= htmlspecialchars($searchedUser->getRole()) ?></td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="d-flex flex-column bd-highlight">
                                <!-- Premier form pour faire la suppression d'un compte utilisateur. -->
                                <form action="../index.php?action=banUser" method="POST">
                                    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                    <div class="form-group">
                                        <label>
                                            <textarea cols="100" type="text" class="form-control mb-2 mr-sm-2"
                                                      placeholder="Raison du banissement" name="reason"
                                                      required></textarea>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="action" value="ban">
                                        <button type="submit" class="btn btn-danger" name="userId"
                                                value="<?php echo $searchedUser->getId() ?>">Confirmer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>