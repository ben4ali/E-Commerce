<?php

namespace controller\Client;

use controller\AbstractController;

require_once __DIR__ . '/../AbstractController.php';

class RemoveItemCartController extends AbstractController
{
    public function execute(): void
    {
        if (!isset($_POST['product_id'])) {
            echo "Product ID not set!";
            return;
        }
        $productId = $_POST['product_id'];
        if (!isset($_COOKIE['cart'])) {
            echo "Cart is empty!";
            return;
        } else {
            $cart = json_decode($_COOKIE['cart'], true);
        }
        if (!isset($cart[$productId])) {
            echo "Product not in cart!";
            return;
        } else {
            unset($cart[$productId]);
        }
        setcookie('cart', json_encode($cart), time() + 86400, '/');
        header('Location: user/panier.php');
    }

    public function getActionName(): string
    {
        return 'removeItemFromCart';
    }
}
?>