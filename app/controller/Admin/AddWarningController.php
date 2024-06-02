<?php

namespace controller\Admin;

use controller\AbstractController;
use JetBrains\PhpStorm\NoReturn;
use model\DAO\AdminDAO;

require_once __DIR__ . '/../../CSRF.php';

class AddWarningController extends AbstractController
{

    #[NoReturn] public function execute(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["warn"])) {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            $user = AdminDAO::getInstance()->getUserById((int)$_POST['warn']);
            if (AdminDAO::getInstance()->addWarningToUser($user)) {
                $name = $user->getFirstName() . ' ' . $user->getLastName();
                $_SESSION['message'] = "L'utilisateur {$user->getId()} : {$name} a été averti";
                header('Location: admin/users.php');
                exit;
            }
        }
    }

    public function getActionName(): string
    {
        return "addWarning";
    }
}