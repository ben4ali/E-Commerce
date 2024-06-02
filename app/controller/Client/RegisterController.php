<?php

namespace controller\Client;

use controller\AbstractController;
use DateTime;
use model\DAO\UserDAO;
use model\User;

require_once __DIR__ . '/../AbstractController.php';

class RegisterController extends AbstractController
{
    public function execute(): void
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Valider le hCaptcha en premier
            /*
            $token = $_POST['h-captcha-response'];
            $secretKey = "8d711b5a-ab00-4887-acb8-9deba844f487";
            $response = file_get_contents("https://hcaptcha.com/siteverify?secret=$secretKey&response=$token");
            $responseData = json_decode($response);
            if (!$responseData->success) {
                $_SESSION['error'] = 'hCaptcha invalide.';
                header('Location: ../user/signIn.php');
                exit;
            }
            */
            // Validation du formulaire.
            $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $age = date_parse($_POST['age']);
            $phoneNumber = preg_replace('/[^0-9+]/', '', $_POST['phone']);
            $typeCompte = filter_input(INPUT_POST, 'typeCompte', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = $_POST['password'];
            $password_confirmation = $_POST['password_confirmation'];
            // fin de la validation

            $year = $age['year'];
            $month = $age['month'];
            $day = $age['day'];
            $ageDT = new DateTime("$year-$month-$day");
            $currentDateTime = new DateTime('now');
            $error = '';

            // Vérification des données
            // Intégrité des données.
            if (!checkdate($month, $day, $year) || $ageDT > $currentDateTime) $error .= "Votre date de naissance est invalide.";
            if (!$email) $error .= 'Email invalide.';
            if ($phoneNumber == null || !preg_match('/^\+?\d{10,15}$/', $phoneNumber)) $error .= 'Téléphone invalide.';
            if (!preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/', $password)) $error .= "Mot de passe trop faible.";

            // vérification des données au niveau du BD.
            if (UserDAO::getInstance()->getByEmail($email) != null) {
                $error .= "L'adresse courriel est déjà utilisée, veuillez choisir un autre ou vous enregistrer";
            }
            if (UserDAO::getInstance()->getByPhoneNumber($phoneNumber) != null) {
                $error .= "Le numéro de téléphone est déjà utilisé.\n";
            }
            if ($password != $password_confirmation) $error .= "Vous devez écrire deux fois le même mot de passe.";

            // Redirection s'il y a eu des erreurs lors de la vérification des données du formulaire.
            if (!empty($error)) {
                $_SESSION['error'] = $error;
                header('Location: ../user/signIn.php');
                exit;
            }
            // fin de la vérification.

            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            // Enregistrement de l'utilisateur.
            if($typeCompte == 'merchant') {
                $newUser = new User(
                    null,
                    $firstName,
                    $lastName,
                    $email,
                    $ageDT,
                    $phoneNumber,
                    'images/profiles/' . $firstName . $lastName . '.png',
                    new DateTime(),
                    $hashed_password,
                    'merchant'
                );
            } else {
                $newUser = new User(
                    null,
                    $firstName,
                    $lastName,
                    $email,
                    $ageDT,
                    $phoneNumber,
                    'images/profiles/' . $firstName . $lastName . '.png',
                    new DateTime(),
                    $hashed_password,
                );
            }

            // Enregistrement du nouvel utilisateur dans la BD.
            if (!UserDAO::getInstance()->create($newUser)) {
                $_SESSION['error'] = 'erreur lors de la création du nouveau compte, essayez plus tard!';
                header('Location: errorForm.php');
                exit;
            }
            if($typeCompte == 'merchant') {
                $_SESSION['user'] = UserDAO::getInstance()->getByEmail($email);
                $_SESSION['user']->setRole('merchant');
                header('Location: merchant/signIn.php');
                exit;
            }

            $_SESSION['message'] = 'Bienvenue dans la famille! Vous pouvez maintenant vous connecter.';
            header('Location: infoPage.php');
            exit;
        }
        header('Location: user/signIn.php');
        exit;
    }

    public function getActionName(): string
    {
        return 'registerClient';
    }
}