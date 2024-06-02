<?php

namespace controller\Admin;

use controller\AbstractController;
use JetBrains\PhpStorm\NoReturn;
use model\DAO\AdminDAO;
use model\Item;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../CSRF.php';

class UpdateItemController extends AbstractController
{
    #[NoReturn] public function execute(): void
    {
        // TODO: Pourquoi $_POST['action'] ?
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            $action = $_POST['action'];
            if ($action === 'warn' && isset($_POST['item_id'])) {
                $item_id = (int)$_POST['item_id'];
                $item = AdminDAO::getInstance()->getItemById($item_id);

                if ($item instanceof Item) {
                    //Update l'item
                    AdminDAO::getInstance()->update($item);
                }
            }
        }
        header('Location: items.php');
        exit;
    }

    public function getActionName(): string
    {
        return 'updateItem';
    }
}