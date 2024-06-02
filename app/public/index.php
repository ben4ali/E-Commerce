<?php $title = 'SHOPNET';

require_once __DIR__ . '/../../route.php';
require_once __DIR__ . '/../Exceptions/CSPNotInitialized.php';
include_once __DIR__ . '/template/header.php';

use Exceptions\CSPNotInitialized;
use model\DAO\ItemDAO;
use model\DAO\ReviewDAO;

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
    <html lang="eng">
    <body>
    <!--Produits avant (slider plus tard)-->
    <div class="container mt-4">
        <!-- ref: https://stackoverflow.com/questions/48824568/bootstrap-4-carousel-sliders-not-working -->
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="images/promotional/promotion_1.png" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="images/promotional/promotion_2.png" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="images/promotional/promotion_3.png" alt="Third slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <h2 class="text-center">Produit en avant</h2>
        <div class="row justify-content-center m-5">

            <!--Donner image approprier et changer la desc dans les cartes-->
            <div class="col-p1 m-3">
                <a href="recherche-category-produit.php?category=3">
                    <div class="card category-card">
                        <img class="card-img-top" src="images/categories/kitchen_category.png" alt="Catégorie Cuisine">
                    </div>
                    <div class="text-center card">
                        <h3 class="text-xl-center">Cuisine</h3>
                    </div>
                </a>
            </div>

            <div class="col-p2 m-3">
                <a href="recherche-category-produit.php?category=1">
                    <div class="card category-card">
                        <img class="card-img-top" src="images/categories/winter_category.png" alt="Catégorie Cuisine">
                    </div>
                    <div class="text-center card">
                        <h3 class="text-xl-center">Hiver</h3>
                    </div>
                </a>
            </div>
            <div class="col-p3 m-3">
                <a href="recherche-category-produit.php?category=2">
                    <div class="card category-card">
                        <img class="card-img-top" src="images/categories/electronic_category.png"
                             alt="Catégorie Électronique">
                    </div>
                    <div class="text-center card">
                        <h3 class="text-xl-center">Électronique</h3>
                    </div>
                </a>
            </div>
            <div class="col-p4 m-3">
                <a href="recherche-category-produit.php?category=4">
                    <div class="card category-card">
                        <img class="card-img-top" src="images/categories/book_category.png" alt="Catégorie Livre">
                    </div>
                    <div class="text-center card">
                        <h3 class="text-xl-center">Livre</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!--Jumbotron-->
    <div class="jumbotron mb-5">
        <div class="container text-center">
            <h1>SHOPNEST</h1>
            <p>Où le shopping prend son envol</p>

        </div>
    </div>

    <hr>

    <!--Part-2-->


    <div class="container mt-5">
        <div class="row justify-content-center">
            <!-- Left case -->
            <?php
            include("template/itemBars/smallItemsBar.php");
            ?>

            <!-- Right case -->
            <?php
            include("template/itemBars/smallItemsBar.php");
            ?>
        </div>
    </div>

    <hr>

    <!--Part-3-->
    <div class="container my-5">
        <div class="row justify-content-center">
            <!-- Left case -->
            <?php
            include("template/itemBars/smallItemsBar.php");
            ?>

            <!-- Right case -->
            <?php
            include("template/itemBars/smallItemsBar.php");
            ?>
        </div>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <!-- Left case -->
            <?php
            include("template/itemBars/bigItemsBar.php");
            ?>

            <!-- Right case -->
            <?php
            include("template/itemBars/bigItemsBar.php");
            ?>
        </div>
    </div>

    <hr>

    <!--Part 4-->
    <div class="container my-5">
        <div class="row justify-content-center">
            <!-- Left case -->
            <?php
            include("template/itemBars/longItemsBar.php");
            ?>

            <!-- Right case -->
            <?php
            include("template/itemBars/bigItemsBar.php");
            ?>
        </div>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <!-- Left case -->
            <?php
            include("template/itemBars/bigItemsBar.php");
            ?>

            <!-- Right case -->
            <?php
            include("template/itemBars/longItemsBar.php");
            ?>
        </div>
    </div>

    <hr>

    <div class="container">
        <?php
        // Retrieve 8 random items (4 for each row)
        $randomItems = [];
        for ($i = 0; $i < 8; $i++) {
            $randomItem = ItemDAO::getInstance()->getRandomItem();
            if ($randomItem) {
                $randomItems[] = $randomItem;
            }
        }
        // Display items in the two rows
        for ($row = 0; $row < 2; $row++) {
            echo '<div class="row my-4">';
            for ($col = 0; $col < 4; $col++) {
                $index = $row * 4 + $col;
                if ($index < count($randomItems)) {
                    $item = $randomItems[$index];
                    echo '<div class="col-md-3">';
                    echo '<div class="card index-card">';
                    echo '<div class="card-body">';
                    echo '<a href="produit-selection.php?id=' . $item->getId() . '"><img src="../images/produits/' . $item->getImageUrl() . '" alt="produit" class="index-card-img"></a>';
                    echo '</div>';

                    $reviews = ReviewDAO::getInstance()->getByItemId((int)$item->getId());
                    $averageStars = 0;

                    if (sizeof($reviews) > 0) {
                        $totalStars = 0;
                        foreach ($reviews as $review) {
                            $totalStars += $review->getNumberStars();
                        }
                        $averageStars = $totalStars / sizeof($reviews);
                    }
                    $averageStarsRounded = round($averageStars);
                    echo '<div class="row mb-4 index-card-row ml-5">';
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $averageStarsRounded) {
                            echo '<img src="../images/icons/star.png" class="product-box-long-img" alt="globalRatings">';
                        } else {
                            echo '<img src="../images/icons/starRating.png" class="product-box-long-img" alt="globalRatings">';
                        }
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            echo '</div>';
        }
        ?>
    </div>
    </body>
    </html>
<?php include_once __DIR__ . '/template/footer.php' ?>