<?php

namespace controller\Merchant;

use controller\AbstractController;
use model\DAO\MerchantDAO;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../CSRF.php';

class UpdateMerchantController extends AbstractController
{
    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            $merchant = MerchantDAO::getInstance()->getByEmail($_SESSION['email']);
            if ($merchant !== null) {
                $merchant->setBusiness($_POST['company']);
                $merchant->setEmail($_POST['email']);
                $merchant->setLastName($_POST['nom']);
                $merchant->setFirstName($_POST['prenom']);
                $merchant->setPhoneNumber($_POST['telephone']);

                MerchantDAO::getInstance()->update($merchant);

                $_SESSION['merchant'] = $merchant;
                $_SESSION['email'] = $_POST['email'];

                header("Location: authentificationSecuMarchand.php");
            } else {
                header("Location: index.php");
            }
            exit;

        }
        include '../public/authentificationSecuMarchand.php';
    }

    public function getActionName(): string
    {
        return 'updateMarchand';
    }
}