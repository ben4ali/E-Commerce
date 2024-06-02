<?php

namespace controller\Admin;

use controller\AbstractController;
use JetBrains\PhpStorm\NoReturn;
use model\DAO\AdminDAO;

require_once __DIR__ . '/../../model/DAO/AdminDAO.php';
require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../CSRF.php';

class BanUserController extends AbstractController
{

    #[NoReturn] public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token); // appel déjà session_start()

            $targetId = $_POST['userId'];
            $adminId = $_SESSION['user']->getId();
            $reason = $_POST['reason'];
            if (AdminDAO::getInstance()->banUser($targetId, $adminId, $reason)) {
                // success
                $_SESSION['message'] = "L'utilisateur $targetId a été banni pour raison: $reason";
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
        return "banUser";
    }
}