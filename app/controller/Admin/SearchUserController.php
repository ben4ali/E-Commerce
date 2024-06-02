<?php

namespace controller\Admin;

use controller\AbstractController;
use model\DAO\AdminDAO;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../model/DAO/AdminDAO.php';
require_once __DIR__ . '/../../CSRF.php';

class SearchUserController extends AbstractController
{

    public function execute(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            $firstName = $_POST["firstName"] ?? '';
            $lastName = $_POST["lastName"] ?? '';
            $email = $_POST["email"] ?? '';
            $phone = $_POST["phone"] ?? '';

            // sanitize inputs
            // ...

            $_SESSION['searchedUser'] = AdminDAO::getInstance()->findUsers([$firstName, $lastName, $email, $phone]);
            header('Location: ' . $_POST['origin']);
            exit;
        }
    }

    public function getActionName(): string
    {
        return 'searchUserAdmin';
    }
}