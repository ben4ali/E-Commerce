<?php

namespace controller\Merchant;

use controller\AbstractController;
use JetBrains\PhpStorm\NoReturn;
use model\DAO\ItemDAO;
use model\Item;
use DateTime;
use model\Merchant;

require_once __DIR__ . '/../../CSRF.php';

class AddItemController extends AbstractController
{

    #[NoReturn] public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            // Validate and sanitize form inputs
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
            $upc = filter_input(INPUT_POST, 'upc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $categoryId = filter_input(INPUT_POST, 'categoryId', FILTER_VALIDATE_INT);

            $uploadDirectory = 'images/produits/';
            $originalFilename = $_FILES['fileToUpload']['name'];
            $uniqueFilename = uniqid() . '_' . $originalFilename;
            $targetFilePath = $uploadDirectory . $uniqueFilename;

            $fullPathForUpload = __DIR__ . '/../../public/' . $targetFilePath;

            if (!file_exists(__DIR__ . '/../../public/' . $uploadDirectory)) {
                mkdir(__DIR__ . '/../../public/' . $uploadDirectory, 0777, true);
            }

            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $fullPathForUpload)) {
                echo "File uploaded successfully.";
            } else {
                echo "There was an error uploading the file.";
            }

            $error = '';

            if (!$name) {
                $error .= "Le nom est invalide.";
            }
            if (!$description) {
                $error .= "La description est invalide.";
            }
            if (!$price) {
                $error .= 'Le prix est invalide.';
            }
            if (!$quantity) {
                $error .= 'La quantité est invalide.';
            }
            if (!$upc) {
                $error .= 'Le code UPC est invalide.';
            }
            if (!$categoryId) {
                $error .= 'L\'ID de catégorie est invalide.';
            }

            if (!empty($error)) {
                $_SESSION['error'] = $error;
                header('Location: merchant/viewItem.php');
                exit;
            }

            $merchant = null;
            if($_SESSION['merchant'] instanceof Merchant) $merchant = $_SESSION['merchant'];
            $merchantId = $merchant->getMerchantId();

            $item = new Item(
                -1,
                $merchant->getBusiness()->getId(),
                $name,
                $description,
                $price,
                $quantity,
                $upc,
                $categoryId,
                $targetFilePath,
                '',
                $merchantId,
                new DateTime()
            );

            if (ItemDAO::getInstance()->create($item)) {
                $_SESSION['message'] = "L'Item a été rajouté avec succès!";
            } else {
                $_SESSION['message'] = "Il y a eu une erreur lors de l'enregistrement de votre item.";
            }
            header('Location: index.php?action=CreateMerchantItemList');
        } else {
            header('Location: index.php');
        }
        exit;
    }

    public function getActionName(): string
    {
        return 'merchantAddItem';
    }
}