<?php

namespace controller\Admin;

use controller\AbstractController;
use JetBrains\PhpStorm\NoReturn;
use model\DAO\AdminDAO;

require_once __DIR__ . '/../../CSRF.php';

class UnbanUserController extends AbstractController
{

    #[NoReturn] public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            $banId = $_POST['banId'];
            if (AdminDAO::getInstance()->unbanUser($banId)) {
                // success
                $_SESSION['message'] = "Le ban $banId a été détruit avec success.";
                header('Location: admin/bans.php');
                exit;
            }
        }
        $_SESSION['error'] = "Il y a eu un problème durant l'execution de votre requête.";
        header('Location: errorForm.php');
        exit;
    }

    public function getActionName(): string
    {
        return "unbanUser";
    }
}