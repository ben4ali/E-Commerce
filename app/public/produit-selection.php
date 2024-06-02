<?php
$title = "SHOPNEST";
include_once("template/header.php");
?>

<?php
require_once __DIR__ . '/../model/Item.php';
require_once __DIR__ . '/../model/DAO/ItemDAO.php';
require_once __DIR__ . '/../model/DAO/ReviewDAO.php';
require_once __DIR__ . '/../model/DAO/UserDAO.php';
require_once __DIR__ . '/../model/Review.php';
require_once __DIR__ . '/../model/User.php';

use model\DAO\ItemDAO;
use model\DAO\ReviewDAO;
use model\DAO\UserDAO;
use model\Item;
use Exceptions\CSPNotInitialized;

try {
    if (!isset($CSP)) throw new CSPNotInitialized("Le content-security-policy n'a pas été initialisé correctement.");
    $CSP->execute();
} catch (CSPNotInitialized $e) {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    // erreur lors de la création du content-security-policy
    $_SESSION['error'] = $e->getMessage();
    header('Location: errorForm.php');
    exit;
}

$idData = $_GET['id'] ?? '';

if ($idData == '') {
    $_SESSION['error'] = "Désolé, il y a eu un problème lors de l'affichage du produit, revenez plus tard! errno: 001 id: $idData";
    header('Location: errorForm.php');
    exit;
}
$item = ItemDAO::getInstance()->getById((int)$idData);
if (!$item instanceof Item) {
    $_SESSION['error'] = "Désolé, il y a eu un problème lors de l'affichage du produit, revenez plus tard! errno: 002";
    header('Location: errorForm.php');
    exit;
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : array();

    if (isset($cart[$product_id])) {
        $cart[$product_id] += $quantity;
    } else {
        $cart[$product_id] = $quantity;
    }

    setcookie('cart', json_encode($cart), time() + (86400 * 30), "/"); // 86400 = 1 day
}
?>
<html lang="eng">
<body>
<div class="content-wrapper"> <!-- Content Wrapper -->
    <!-- Corps -->
    <div class="container mt-5">
        <div class="row mb-5">
            <!-- Image -->
            <div class="col-md-4">
                <hr class="my-4">
                <img src="<?php echo '../images/produits/' . $item->getImageUrl() ?>" alt="produit" width="100%"
                     height="auto">
            </div>
            <!-- Titre & Description -->
            <div class="col-md-5">
                <hr class="my-4">
                <h3 class="display-5"><?php echo $item->getName() ?></h3>
                <p class="lead"><?php echo $item->getDescription() ?></p>
                <?php
                $reviews = ReviewDAO::getInstance()->getByItemId((int)$idData);
                if (sizeof($reviews) > 0) {
                    $totalStars = 0;
                    foreach ($reviews as $review) {
                        $totalStars += $review->getNumberStars();
                    }
                    $averageStars = $totalStars / sizeof($reviews);
                    $averageStarsRounded = round($averageStars);

                    echo '<h6>' . number_format($averageStars, 1) . '/5.0</h6>';
                    echo '<div class="row">';
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $averageStarsRounded) {
                            echo '<div class="col-sm-1"><img class="product-box-long-img" src="../images/icons/star.png" alt="globalRating"></div>';
                        } else {
                            echo '<div class="col-sm-1"><img class="product-box-long-img" src="../images/icons/starRating.png" alt="globalRating"></div>';
                        }
                    }
                    echo '</div>';
                } else {
                    echo '<h6>-/5.0</h6>
                        <div class="row">
                            <div class="col-sm-1"><img class="product-box-long-img" src="../images/icons/starRating.png" alt="globalRating"></div>
                            <div class="col-sm-1"><img class="product-box-long-img" src="../images/icons/starRating.png" alt="globalRating"></div>
                            <div class="col-sm-1"><img class="product-box-long-img" src="../images/icons/starRating.png" alt="globalRating"></div>
                            <div class="col-sm-1"><img class="product-box-long-img" src="../images/icons/starRating.png" alt="globalRating"></div>
                            <div class="col-sm-1"><img class="product-box-long-img" src="../images/icons/starRating.png" alt="globalRating"></div>
                        </div>';

                }
                ?>
            </div>
            <!-- Prix -->
            <div class="col-lg-3">
                <hr class="my-4">
                <h3><?php echo $item->getPrice(); ?> $</h3>
                <p>Disponibilité : <?php echo $item->getQuantity(); ?> en stock</p>
                <div class="input-group mb-3 col-md-6 ml-n3">
                    <p class="input-group-text">Quantité</p>
                    <button class="btn btn-outline-secondary btn-sm" type="button" id="btnMinus">-</button>
                    <input type="text" class="form-control text-center card-price-text" id="quantity" value="1" readonly>
                    <button class="btn btn-outline-secondary btn-sm" type="button" id="btnPlus">+</button>
                </div>
                <form method="post" action="/index.php?action=addItemToCart">
                    <input type="hidden" name="product_id" value="<?php echo $item->getId(); ?>">
                    <input type="hidden" name="quantity" id="hiddenQuantity" value="1">
                    <button id="add-to-cart-button" class="btn btn-primary">Ajouter au panier</button>
                </form>
            </div>

            <script>
                document.getElementById('btnMinus').addEventListener('click', function() {
                    var quantityInput = document.getElementById('quantity');
                    var hiddenQuantityInput = document.getElementById('hiddenQuantity');
                    var currentValue = parseInt(quantityInput.value, 10);
                    currentValue = currentValue - 1;
                    if(currentValue >= 1) {
                        quantityInput.value = currentValue;
                        hiddenQuantityInput.value = currentValue;
                    }
                });

                document.getElementById('btnPlus').addEventListener('click', function() {
                    var quantityInput = document.getElementById('quantity');
                    var hiddenQuantityInput = document.getElementById('hiddenQuantity');
                    var currentValue = parseInt(quantityInput.value, 10);
                    currentValue = currentValue + 1; // increment the value
                    quantityInput.value = currentValue;
                    hiddenQuantityInput.value = currentValue;
                });
            </script>

        </div>

        <!-- Avis Section -->
        <h2>Avis</h2>
        <hr class="my-4">

        <?php
        $reviews = ReviewDAO::getInstance()->getByItemId((int)$idData);

        if (sizeof($reviews) > 0) {
            foreach ($reviews as $review) {
                $user = UserDAO::getInstance()->getById($review->getUserId());

                echo '
        <div class="row my-4">
            <!-- User Information -->
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($user->getFirstName()) . ' ' . htmlspecialchars($user->getLastName()) . '</h5>
                    </div>
                </div>
            </div>
            <!-- Review Comment -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">' . htmlspecialchars($review->getComment()) . '</p>
                    </div>
                </div>
            </div>
        </div>';

            }
        } else {
            echo '<h4>Aucun avis sur ce produit.</h4>';
        }
        echo '<div class="mb-5"></div>';
        ?>

        <!-- Fin Avis -->
    </div>

    <!-- Produits recommandés -->
    <div class="jumbotron">
        <div class="container">
            <div class="row">
                <div class="jumbotron">
                    <div class="container">
                        <h2>Autres produits qui pourraient vous plaire</h2>
                    </div>
                    <hr class="my-4">
                    <div class="container">
                        <div class="row">
                            <?php
                            for ($i = 0; $i < 4; $i++) {
                                $randomItem = ItemDAO::getInstance()->getRandomItem();
                                if ($randomItem) {
                                    ?>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <a class="card-link"
                                               href="produit-selection.php?id=<?php echo $randomItem->getId(); ?>">
                                                <div class="card-body ps-body">
                                                    <img class="center img-fluid"
                                                         src="<?php echo '../images/produits/' . $randomItem->getImageUrl() ?>"
                                                         alt="produit">
                                                </div>
                                                <div class="card-footer ps-footer">
                                                    <h6 class="text-center"><?php echo $randomItem->getName(); ?></h6>
                                                    <h5 class="text-center"><?php echo $randomItem->getPrice(); ?>
                                                        $</h5>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <hr class="my-4">
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Produits recommandés -->
</div> <!-- End of Content Wrapper -->
</body>
</html>
<?php include_once("template/footer.php"); ?>
