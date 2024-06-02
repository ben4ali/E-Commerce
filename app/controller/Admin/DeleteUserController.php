<?php

namespace controller\Admin;

use controller\AbstractController;
use JetBrains\PhpStorm\NoReturn;
use model\DAO\AdminDAO;
use model\User;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../CSRF.php';

class DeleteUserController extends AbstractController
{
    #[NoReturn] public function execute(): void
    {
        // TODO: Pourquoi $_POST['action'] ?
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            $action = $_POST['action'];
            if ($action === 'delete' && isset($_POST['user_id'])) {
                $userId = (int)$_POST['user_id'];
                $user = AdminDAO::getInstance()->getUserById($userId);

                if ($user instanceof User) {
                    AdminDAO::getInstance()->addWarningToUser($user);
                }
            }
        }
        header('Location: users.php');
        exit;
    }

    public function getActionName(): string
    {
        return 'deleteUser';
    }
}