<?php

namespace controller\Client;

use controller\AbstractController;

require_once __DIR__ . '/../AbstractController.php';

class AddItemCartController extends AbstractController
{
    public function execute(): void
    {
        if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
            return;
        }
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        if (!isset($_COOKIE['cart'])) {
            $cart = [];
        } else {
            $cart = json_decode($_COOKIE['cart'], true);
        }

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }
        setcookie('cart', json_encode($cart), time() + 86400, '/');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    public function getActionName(): string
    {
        return 'addItemToCart';
    }
}
?>
