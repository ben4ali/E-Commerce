<?php
use model\DAO\ItemDAO;
use model\DAO\ReviewDAO;
?>

<div class="col-md-6">
    <div class="card product-box-long-div">
        <div class="card-body">
            <?php
            $randomItem = ItemDAO::getInstance()->getRandomItem();
            if ($randomItem) {
                $reviews = ReviewDAO::getInstance()->getByItemId((int)$randomItem->getId());
                $averageStars = 0;

                if (sizeof($reviews) > 0) {
                    $totalStars = 0;
                    foreach ($reviews as $review) {
                        $totalStars += $review->getNumberStars();
                    }
                    $averageStars = $totalStars / sizeof($reviews);
                }
                $averageStarsRounded = round($averageStars);

                echo '<div class="container">
                        <div class="row mt-5">
                            <div class="col-md-8 mt-5">
                            <a href="produit-selection.php?id='.$randomItem->getId().'"><img src="../images/produits/'.$randomItem->getImageUrl().'" alt="produit" class="product-box-long""></a>
                            </div>
                            <div class="col-md-4">
                                <h4>'.$randomItem->getName().'</h4>
                                <p>'.$randomItem->getDescription().'</p>
                                <h3>'.$randomItem->getPrice().' $</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">';
                                
                echo '</div>
                        </div>
                      </div>';

                echo '<div class="row mr-3 mb-5">
                        <div class="col-md-12">
                            <div class="text-right">';
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $averageStarsRounded) {
                        echo '<img src="../images/icons/star.png" class="product-box-long-img">';
                    } else {
                        echo '<img src="../images/icons/starRating.png" class="product-box-long-img">';
                    }
                }
                echo '</div></div></div>';
            }
            ?>
        </div>
    </div>
    <div class="card my-5 product-box-long-card">

    </div>

</div>