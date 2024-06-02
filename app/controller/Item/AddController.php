<?php

namespace controller\Item;

use controller\AbstractController;
use DateTime;
use model\DAO\ItemDAO;
use model\DAO\UserDAO;
use model\Item;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../CSRF.php';

class AddController extends AbstractController
{
    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            $item = ItemDAO::getInstance()->getById($_SESSION['item_id']);
            if ($item == null) {
                $newItem = new Item(
                    $_POST['id'],
                    $_POST['businessId'],
                    $_POST['name'],
                    $_POST['description'],
                    $_POST['price'],
                    $_POST['quantity'],
                    $_POST['upc'],
                    $_POST['category'],
                    $_POST['imageUrl'],
                    $_POST['details'],
                    $_POST['merchant_id'],
                    new DateTime('now')
                );
                UserDAO::getInstance()->create($newItem);
                $item_id = ItemDAO::getInstance()->getById($_POST['id'])->getId();
                if ($item_id) {
                    $newItem->setId($item_id);
                } else {
                    header('Location: .php');
                }
            }
        }
    }

    public function getActionName(): string
    {
        return 'addProduit';
    }
}