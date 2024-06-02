<?php
$title = "Résultats de la recherche";

include_once("../model/Item.php");
include_once("../model/DAO/ItemDAO.php");
include_once("../Exceptions/CSPNotInitialized.php");

use Exceptions\CSPNotInitialized;
use model\DAO\ItemDAO;

$itemDAO = ItemDAO::getInstance();

if (isset($_GET['search_query'])) {
    $searchQuery = $_GET['search_query'];
    $products = $itemDAO->search($searchQuery);

    include 'template/header.php';
    // note, le $CSP devrait être initialisé dans le header.php
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
    ?>

    <body>
    <div class="container my-5">
        <h1>Résultats de la recherche pour "<?php echo $searchQuery; ?>"</h1>

        <div class="container mt-5 mb-3">
            <div class="row">
                <?php

                if ($products !== null) {
                    foreach ($products as $product) {
                        ?>
                            <div class="col-md-3 mb-4">
                                <div class="card w-100 h-100">
                                    <img src="<?php echo '../images/produits/'.$product->getImageUrl()?>"
                                        class="card-img-top w-100 height-auto object-fit-cover" alt="<?php echo $product->getName(); ?>">
                                    <div class="card-body">
                                        
                                    </div>
                                    <div class="card-footer">
                                        <a class="card-title h5 text-reset"
                                        href="produit-selection.php?id=<?php echo $product->getId(); ?>"><?php echo $product->getName(); ?></a>
                                        <strong><?php echo $product->getPrice(); ?></strong>
                                        <form method="post" action="/index.php?action=addItemToCart">
                                            <input type="hidden" name="product_id" value=<?php echo $product->getId()?>>
                                            <input type="hidden" name="quantity" value="1">
                                            <button id="add-to-cart-button" class="btn btn-primary">Add to Cart</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                } else {
                    echo "Aucun résultat trouvé pour la recherche.";
                }
                ?>
            </div>
        </div>

    </div>
    </body>

    <?php include 'template/footer.php';
}
?>
