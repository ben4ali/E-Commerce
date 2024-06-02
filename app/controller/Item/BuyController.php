<?php

namespace controller\Item;

use controller\AbstractController;

require_once __DIR__ . '/../AbstractController.php';

class BuyController extends AbstractController
{
    public function execute(): void
    {

        if (!isset($_COOKIE['cart'])) {
            return;
        }
        setcookie('cart', '', time() - 3600, '/');
        $_SESSION['message'] = 'Merci pour votre achat!';
        header('Location: infoPage.php');
    }

    public function getActionName(): string
    {
        return 'buyCart';
    }
}
?>