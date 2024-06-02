<?php

namespace controller\Item;

use controller\AbstractController;
use model\DAO\ItemDAO;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../CSRF.php';

class UpdateAllController extends AbstractController
{
    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            $item = ItemDAO::getInstance()->getById($_SESSION['item_id']);
            if ($item !== null) {
                $item->setCategoryId($_POST['category']);
                $item->setName($_POST['name']);
                $item->setDescription($_POST['description']);
                $item->setPrice($_POST['price']);
                $item->setUpc($_POST['upc']);
                $item->setQuantity($_POST['quantity']);
                ItemDAO::getInstance()->update($item);
                $_SESSION['item_id'] = $item->getId();
            } else {
                header("Location: .php");
            }
            header("Location: .php");
            exit;
        }
    }

    public function getActionName(): string
    {
        return 'updateProduitAll';
    }
}