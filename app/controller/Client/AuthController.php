<?php

namespace controller\Client;

use controller\AbstractController;
use JetBrains\PhpStorm\NoReturn;
use model\DAO\BusinessDAO;
use model\DAO\MerchantDAO;
use model\DAO\UserDAO;
use Exception;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../model/DAO/BusinessDAO.php';
require_once __DIR__ . '/../../model/DAO/MerchantDAO.php';
require_once __DIR__ . '/../../CSRF.php';

class AuthController extends AbstractController
{
    #[NoReturn] public function execute(): void
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];

            // Validation des données.
            $error = '';
            if (!$email) $error .= 'Adresse email invalide. ';
            if (!$password) $error .= 'Mot de passe manquant. ';

            if (!empty($error)) {
                $_SESSION['error'] = rtrim($error);
                header('Location: user/auth.php');
                exit;
            }

            // Vérification du mot de passe
            $userDAO = UserDAO::getInstance();
            $verification = $userDAO->verifyPassword($email, $password, true);

            if (!$verification) {
                $_SESSION['error'] = "Email ou mot de passe non valide.";
                header('Location: user/auth.php');
                exit;
            }
            session_regenerate_id(true);

            // Connexion valide
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $email;

            // Stockage de l'objet utilisateur dans la session
            $_SESSION['user'] = $userDAO->getByEmail($email);

            // Génération du token CSRF
            session_generate_token();

            // Redirection
            if($_SESSION['user']->getRole() == 'merchant') {
                $_SESSION['merchant'] = MerchantDAO::getInstance()->getByUserId($_SESSION['user']->getId());
                $_SESSION['business'] = BusinessDAO::getInstance()->getByMerchantId($_SESSION['merchant']->getId());
            }
            $redirectLocation = $_SESSION['user']->getRole() == 'admin' ? '/index.php?action=getAdminDashboardStats' :
                ($_SESSION['user']->getRole() == 'merchant' ? 'index.php?action=getMerchantDashboardStats' : 'user/accountInfo.php');

            header("Location: $redirectLocation");
            exit;
        }

        // Redirect if not a POST request
        header("Location: user/auth.php");
        exit;
    }


    public function getActionName(): string
    {
        return 'authenticateClient';
    }
}