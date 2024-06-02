<?php

namespace controller\Item;

use controller\AbstractController;
use model\DAO\ItemDAO;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../CSRF.php';
class DeleteController extends AbstractController
{
    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            $item = ItemDAO::getInstance()->getById($_SESSION['item_id']);
            if ($item !== null) {
                ItemDAO::getInstance()->delete($item);
            } else {
                header("Location: .php");
            }
            header("Location: .php");
            exit;
        }
    }

    public function getActionName(): string
    {
        return 'deleteProduit';
    }
}