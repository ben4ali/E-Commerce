<?php

namespace controller\Merchant;

use controller\AbstractController;
use model\DAO\MerchantDAO;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../CSRF.php';
class UnregisterMerchantController extends AbstractController
{
    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            session_start();
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            if (isset($_SESSION['merchant'])) {
                MerchantDAO::getInstance()->delete($_SESSION['merchant']);
                session_destroy();
                header("Location: index.php");
                exit;
            } else {
                error_log("L'object marchand n'était pas set.");
            }
        }
    }

    public function getActionName(): string
    {
        return 'unregisterMarchand';
    }
}