<?php

namespace controller\Merchant;

use controller\AbstractController;
use Exception;
use model\Address;
use model\Business;
use model\DAO\AddressDAO;
use model\DAO\BusinessDAO;
use model\DAO\MerchantDAO;
use model\DAO\UserDAO;
use model\Merchant;
use model\User;

require_once __DIR__ . '/../../model/DAO/MerchantDAO.php';
require_once __DIR__ . '/../../model/DAO/AddressDAO.php';
require_once __DIR__ . '/../../model/DAO/BusinessDAO.php';
require_once __DIR__ . '/../../model/DAO/UserDAO.php';
require_once __DIR__ . '/../../model/Merchant.php';
require_once __DIR__ . '/../../model/Address.php';
require_once __DIR__ . '/../../model/Business.php';
require_once __DIR__ . '/../AbstractController.php';

class RegisterMerchantController extends AbstractController
{
    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            session_start();
            // vérification des variables
            $companyName = filter_input(INPUT_POST, 'companyName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $employees = filter_input(INPUT_POST, 'employees', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $workEmail = filter_input(INPUT_POST, 'workEmail', FILTER_VALIDATE_EMAIL);
            $siteweb = filter_input(INPUT_POST, 'siteweb', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $typeCompany = filter_input(INPUT_POST, 'typeCompany', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ageWork = filter_input(INPUT_POST, 'ageWork', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $phoneWork = preg_replace('/[^0-9+]/', '', $_POST['phoneWork']);
            $ne = filter_input(INPUT_POST, 'ne', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Extract address data
            $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $postal_code = filter_input(INPUT_POST, 'postal_code', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $error = '';

            // Vérification des données
            // Intégrité des données.
            if (!$companyName) $error .= "Le nom de l'entreprise est invalide.";
            if (!$employees) $error .= "Le nombre d'employés est invalide.";
            if (!$workEmail) $error .= 'Email de l\'entreprise invalide.';
            if ($phoneWork == null || !preg_match('/^\+?\d{10,15}$/', $phoneWork)) $error .= 'Numéro de téléphone de l\'entreprise invalide.';
            if (!$typeCompany) $error .= 'Type de l\'entreprise invalide.';
            if (!filter_var($ageWork)) $error .= "La date de création de l'entreprise est invalide.";

            if (MerchantDAO::getInstance()->getByEmail($workEmail) !== null) {
                $error .= "L'adresse courriel est déjà utilisée, veuillez choisir un autre ou vous enregistrer";
            }
            if (MerchantDAO::getInstance()->getByPhoneNumber($phoneWork) !== null) {
                $error .= "Le numéro de téléphone est déjà utilisé.\n";
            }

            // Redirection s'il y a eu des erreurs lors de la vérification des données du formulaire.
            if (!empty($error)) {
                $_SESSION['error'] = $error;
                header('Location: merchant/signIn.php');
                exit;
            }

            // Enregistrement des données dans la BD.
            try {
                $user = null;
                if($_SESSION['user'] instanceof User) $user = $_SESSION['user'];
                $address = new Address(-1, $street, $city, $province, $country, $postal_code);
                AddressDAO::getInstance()->create($address);
                $addressObj = AddressDAO::getInstance()->getExact($street, $city, $province, $country, $postal_code);
                $business = new Business(
                    -1,
                    $user->getId(),
                    $addressObj->getId(),
                    $companyName,
                    $workEmail,
                    $siteweb,
                    $typeCompany,
                    $ne,
                    $description
                );

                $merchant = new Merchant(-1, $user, $business);
                MerchantDAO::getInstance()->create($merchant);
                BusinessDAO::getInstance()->create($business);

                $_SESSION['message'] = "Bienvenue dans notre famille!";
                header('Location: infoPage.php');
                exit;
            } catch (Exception $e) {
                error_log('Error while registering new merchant : ' . $e->getMessage());
                header('Location: user/signIn.php');
                exit;
            }
        } else {
            header('Location: user/signIn.php');
            exit;
        }

    }

    public function getActionName(): string
    {
        return 'registerMerchant';
    }
}