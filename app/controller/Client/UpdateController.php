<?php

namespace controller\Client;

use controller\AbstractController;
use model\DAO\UserDAO;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../CSRF.php';

class UpdateController extends AbstractController
{
    public function execute(): void
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            // Validation des données.
            $firstName = htmlspecialchars($_POST['prenom'] ?? '');
            $lastName = htmlspecialchars($_POST['nom'] ?? '');
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $phoneNumber = htmlspecialchars($_POST['telephone'] ?? '');
            // Fin de la validation des données.

            if (!$firstName || !$lastName || !$email || !$phoneNumber) {
                $_SESSION['error'] = 'Tous les champs sont obligatoires.';
                header('Location: user/authentificationSecu.php');
            } else {
                $user = UserDAO::getInstance()->getByEmail($_SESSION['email']);
                if ($user !== null) {
                    $user->setFirstName($firstName);
                    $user->setLastName($lastName);
                    $user->setEmail($email);
                    $user->setPhoneNumber($phoneNumber);

                    if (UserDAO::getInstance()->update($user)) {
                        // Update session data
                        $_SESSION['user'] = $user;
                        $_SESSION['email'] = $email;
                        header("Location: user/authentificationSecu.php");
                        exit;
                    } else {
                        $_SESSION['error'] = 'Erreur lors de la mise à jour du profil.';
                    }
                } else {
                    $_SESSION['error'] = 'Utilisateur non trouvé.';
                }
                header('Location: errorForm.php');
            }
        }
        header('Location: user/authentificationSecu.php');
    }


    public function getActionName(): string
    {
        return 'updateClient';
    }
}