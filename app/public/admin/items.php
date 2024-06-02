<?php

use model\DAO\AdminDAO;

$title = 'Item Manage';
include("../template/header.php");
include_once("../../model/DAO/AdminDAO.php");
// TODO: Déplacer dans un contrôleur...
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $item = AdminDAO::getInstance()->getItemById((int)$_POST['delete']);
    AdminDAO::getInstance()->delete($item);
}
?>
    <body>
    <div class="bg-secondary text-white text-center py-3">
        <h1>Admin</h1>
    </div>
    <div class="container mt-4 ">
        <div class="row">
            <div class="col-md-6">
                <section class="user-search p-3 border admin-panel">
                    <h2>Items</h2>
                    <div class="containter-fluid">
                        <form action="items.php" method="POST">
                            <div class="mb-3 mt-3">
                                <label for="comment">Rechercher</label>
                                <label for="firstName">
                                    <input type="text" class="form-control" id="firstName" placeholder="Entrer nom"
                                           name="firstName">
                                </label>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <button type="submit" name="recherche" class="btn btn-primary">Recherche</button>
                                </div>
                                <div class="col-md-4 ml-n4">
                                    <button type="submit" name="afficher_tout" class="btn btn-primary">Afficher tout
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <a href="dashboard.php" class="btn btn-danger">Retour</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <ul class="list-group">
                        <form action="items.php" method="POST">
                            <?php
                            // TODO: Déplacer dans un contrôleur..
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                if (isset($_POST["recherche"])) {
                                    $firstName = $_POST["firstName"];
                                    $searchedItem = AdminDAO::getInstance()->getItemByName($firstName);
                                    if ($searchedItem && is_object($searchedItem)) {
                                        echo '<li>
                                            <button type="submit" name="item_id" class="slctButton" value="' . $searchedItem->getId() . '">
                                                <div class="card my-1">
                                                    <div class="card-body itemSelection">
                                                        ' . $searchedItem->getName() . ' ' . $searchedItem->getPrice() . '
                                                    </div> 
                                                </div>
                                            </button>
                                        </li>';
                                    } else {
                                        echo '<li>Aucun resultat trouvé</li>';
                                    }
                                } elseif (isset($_POST["afficher_tout"])) {
                                    $items = AdminDAO::getInstance()->getAllItems();
                                    if (count($items) == 0) {
                                        echo '<li>Aucun resultat trouvé</li>';
                                    } else {
                                        foreach ($items as $item) {
                                            echo '<li>
                                                <button type="submit" name="item_id" value="' . $item->getId() . '" class="slctButton">
                                                    <div class="card my-1">
                                                        <div class="card-body itemSelection">
                                                            ' . $item->getName() . ' ' . $item->getPrice() . '
                                                        </div> 
                                                    </div>
                                                </button>
                                            </li>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </form>
                    </ul>
                </section>
            </div>
            <div class="col-md-6 admin-panel mb-5">
                <section class="p-3 border admin-panel">
                    <h2>Détails de l'item</h2>
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["item_id"])) {
                        $itemId = (int)$_POST['item_id'];
                        $selectItm = AdminDAO::getInstance()->getItemById($itemId);
                        if ($selectItm) {
                            echo '             
                            <div class="d-flex justify-content-center">
                                <img alt="" style="width: 300px; height: 150px; border-radius: 50%;" title=""
                                     class="img-circle img-thumbnail isTooltip" src="https://via.placeholder.com/300">
                            </div> 
                            <div class="d-flex justify-content-center mt-4">
                                <div class="container">
                                    <div class="card my-1">
                                        <div class="card-body">
                                            <div class="col-md-6 font-weight-bolder">
                                                Information
                                                <div class="tableResponsive">
                                                    <table class="table table-user-information">
                                                        <tbody>
                                                        <tr>
                                                            <td class="font-weight-bolder">
                                                                <p>Nom du produit</p>
                                                            </td>
                                                            <td class="text-dark">
                                                                <p>' . $selectItm->getName() . '</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bolder">
                                                                <p>Description</p>
                                                            </td>
                                                            <td class="text-dark">
                                                                <p>' . $selectItm->getDescription() . '</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bolder">
                                                                <p>Prix</p>
                                                            </td>
                                                            <td class="text-dark">
                                                                <p>' . $selectItm->getPrice() . '</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bolder">
                                                                <p>Quantité</p>
                                                            </td>
                                                            <td class="text-dark">
                                                                <p>' . $selectItm->getQuantity() . '</p>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="d-flex flex-column bd-highlight">
                                <form method="POST">
                                    <button type="submit" class="btn btn-danger mt-1" name="delete" value="' . $selectItm->getId() . '">Supprimer</button>
                                </form>
                            </div>
                            ';
                        } else {
                            echo "Item not found.";
                        }
                    }
                    ?>
                </section>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <a href="dashboard.php" class="btn btn-primary">Retour à la page admin</a>
    </div>
    </body>
<?php include("../template/footer.php"); ?>