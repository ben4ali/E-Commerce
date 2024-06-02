<?php 
$title = "Panier";
include_once __DIR__ . '/userIncludes.php';
if (!isset($_SESSION['logged_in'])) {
    // L'utilisateur n'est pas connecté, redirection à la page auth.php.
    header("Location: auth.php");
}
$userName = $_SESSION['logged_in'];

require_once __DIR__ . '/../../model/Item.php';
require_once __DIR__ . '/../../model/DAO/ItemDAO.php';
use model\DAO\ItemDAO;
// Get the ItemDAO instance
$itemDAO = new ItemDAO();

?>
<?php
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : array();
?>
<body>
<!--Corps de page-->
<div class="container my-3">
    <div class="jumbotron">

        <!--Article(s)-->
        <div class="container">
            <div class="card">
                <div class="card-body" style="height: 450px; overflow-y: auto;">
                    <!--Table des articles-->
                    <div class="container my-5">
                        <h1>Votre panier</h1>
                        <!--liste des produits, rajouter des images du produit peut etre-->
                        <table class="table mt-5">
                            
                            <thead>
                            <tr>
                                <th scope="col">Produit</th>
                                <th scope="col">Quantité</th>
                                <th scope="col">Prix</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Loop through the items in the cart
                            foreach ($cart as $productId => $quantity) {
                                // Get the item from the database
                                $item = $itemDAO->getById($productId);
                                // Display the item in the table
                                echo "<tr>";
                                echo "<td>" . $item->getName() . "</td>";
                                echo "<td>" . $quantity . "</td>";
                                echo "<td>" . $item->getPrice() . " $</td>";
                                echo "<td>";
                                echo "<form method='post' action='../index.php?action=removeItemFromCart'>";
                                echo "<input type='hidden' name='product_id' value='{$productId}'>";
                                echo "<button type='submit' class='btn btn-Secondary'>X</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="container my-5">
                <div class="row">
                    <!-- Delivery address -->
                    <div class="col-md-6">
                        <h2>Adresse de livraison</h2>
                        <form action="delivery.php" method="post">
                            <div class="mb-3">
                                <label for="Adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="Adresse" name="Adresse">
                            </div>
                            <div class="mb-3">
                                <label for="postal" class="form-label">Code postal</label>
                                <input type="text" class="form-control" id="postal" name="postal">
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Pays</label>
                                <input type="text" class="form-control" id="country" name="country">
                            </div>
                            <div class="mb-3">
                                <label for="province" class="form-label">Province</label>
                                <input type="text" class="form-control" id="province" name="province">
                            </div>
                        </form>
                        <h2 class="mt-5">Options de livraison</h2>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="deliveryOption" id="deliveryOptionStandard" value="standard" checked>
                            <label class="form-check-label" for="deliveryOptionStandard">
                                Livraison standard
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="deliveryOption" id="deliveryOptionExpress" value="express">
                            <label class="form-check-label" for="deliveryOptionExpress">
                                Livraison express
                            </label>
                        </div>
                    </div>
                    <!-- Delivery options and total -->
                    <div class="col-md-6 mt-2">

                        <h2>Total de la commande</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Quantité</th>
                                        <th>Prix</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    foreach ($cart as $productId => $quantity) {
                                        $item = $itemDAO->getById($productId);
                                        $itemTotal = $item->getPrice() * $quantity;
                                        $total += $itemTotal;
                                        echo "<tr>";
                                        echo "<td>{$item->getName()}</td>";
                                        echo "<td>{$quantity}</td>";
                                        echo "<td>{$itemTotal}$</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                    <tr>
                                        <td>Livraison</td>
                                        <td></td>
                                        <td id="deliveryCost">0.00 $</td>
                                    </tr>
                                    <tr>
                                        <td>TVQ</td>
                                        <td></td>
                                        <td id="taxes"><?= round($total * 0.1, 3) ?> $</td>
                                    </tr>
                                    <tr>
                                        <td>TPS</td>
                                        <td></td>
                                        <td id="taxes"><?= round($total * 0.05, 3) ?> $</td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td></td>
                                        <th colspan="2" id="totalCost" data-total-cost-without-delivery="<?= round($total * 1.15,2) ?>"><?= round($total * 1.15,2) ?> $</th>
                                    </tr>
                                </tbody>
                            </table>

                        <form method='post' action='../index.php?action=buyCart'>
                            <button type="submit" class="btn btn-success mt-5">Passer la commande</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>