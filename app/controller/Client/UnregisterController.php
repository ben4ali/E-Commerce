<?php

namespace controller\Client;

use controller\AbstractController;
use model\DAO\UserDAO;
use model\User;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../CSRF.php';

class UnregisterController extends AbstractController
{
    /*
     * Tes contrôleurs doivent extends AbstractController pour avoir le execute et le getActionName
     */
    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            if (isset($_SESSION['user']) && $_SESSION['user'] instanceof User) {
                $user = $_SESSION['user'];
                if (!UserDAO::getInstance()->deactivateUser($user)) {
                    $_SESSION['error'] = "Error lors de la suppression de votre compte.";
                    header('Location: errorForm.php');
                    exit;
                }
                session_destroy();
                header("Location: index.php");
            } else {
                $_SESSION['error'] = "Error lors de la suppression de votre compte.";
                header("Locatin: errorForm.php?");
            }
            exit;
        }
    }

    public function getActionName(): string
    {
        return 'unregisterClient';
    }
}