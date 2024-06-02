<?php
use model\DAO\ItemDAO;
?>
<div class="col-md-6">
    <div class="card product-box-small-div">
        <div class="card-body">
            <div class="container">
                <div class="row justify-content-center">
                    <?php
                    for ($i = 0; $i < 4; $i++) {
                        $randomItem = ItemDAO::getInstance()->getRandomItem();
                        if ($randomItem) {
                            echo '<div class="col-md-3">
                                    <a href="produit-selection.php?id='.$randomItem->getId().'"><img src="../images/produits/'.$randomItem->getImageUrl().'" alt="produit" class="product-box-small"></a>
                                    <p class="product-box-small-p">'.$randomItem->getPrice().' $</p>
                                  </div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>