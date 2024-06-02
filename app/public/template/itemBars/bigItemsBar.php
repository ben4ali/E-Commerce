<?php
use model\DAO\ItemDAO;
?>
<div class="col-md-4">
    <div class="card">
        <?php
        for ($i = 0; $i < 2; $i++) {
            $randomItem = ItemDAO::getInstance()->getRandomItem();
            if ($randomItem) {
                echo '<div class="card-body">
                        <div class="d-flex justify-content-center"> <!-- Center the image -->
                            <img src="../images/produits/'.$randomItem->getImageUrl().'" alt="produit" class="card-img-top" class="product-box-big">
                        </div>
                        <h5 class="card-title text-center">'.$randomItem->getName().'</h5>
                        <p class="card-text text-center">'.$randomItem->getPrice().' $</p>
                        <a href="produit-selection.php?id='.$randomItem->getId().'" class="btn btn-primary btn-sm d-block mx-auto">Voir le produit</a>
                      </div>';
            }
        }
        ?>
    </div>
</div>