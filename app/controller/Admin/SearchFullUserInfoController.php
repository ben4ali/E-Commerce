<?php

namespace controller\Admin;

use controller\AbstractController;
use model\DAO\AddressDAO;
use model\DAO\AdminDAO;
use model\DAO\OrderDAO;
use model\DAO\ReviewDAO;
use model\DAO\TransactionDAO;
use model\Order;
use model\Transaction;

require_once __DIR__ . "/../../model/DAO/AddressDAO.php";
require_once __DIR__ . "/../../model/DAO/AdminDAO.php";
require_once __DIR__ . "/../../model/DAO/TransactionDAO.php";
require_once __DIR__ . "/../../model/DAO/OrderDAO.php";
require_once __DIR__ . "/../../model/DAO/ReviewDAO.php";
require_once __DIR__ . "/../../model/DAO/UserDAO.php";
require_once __DIR__ . "/../../model/Transaction.php";
require_once __DIR__ . "/../../model/Address.php";
require_once __DIR__ . "/../../model/Order.php";
require_once __DIR__ . "/../../model/Review.php";
require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../CSRF.php';

class SearchFullUserInfoController extends AbstractController
{

    public function execute(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validation du token csrf
            $token = filter_input(INPUT_POST, 'csrf');
            session_match_tokens($token);

            $firstName = $_POST["firstName"] ?? '';
            $lastName = $_POST["lastName"] ?? '';
            $email = $_POST["email"] ?? '';
            $phone = $_POST["phone"] ?? '';

            // Create an associative array of criteria based on non-empty input
            $criteria = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'phone' => $phone
            ];

            $users = AdminDAO::getInstance()->findUsers($criteria);

            if (!empty($users)) {
                $searchFullUserInfo = [];

                foreach ($users as $user) {
                    $shippingAddresses = [];
                    $billingAddresses = [];
                    $deliveryAddresses = [];

                    // Récupération des données relatives à l'utilisateur.
                    $transactions = TransactionDAO::getInstance()->getByUserId($user->getId());
                    $orders = OrderDAO::getInstance()->getByUserId($user->getId());
                    $reviews = ReviewDAO::getInstance()->getByUserId($user->getId());

                    foreach ($transactions as $transaction) {
                        if ($transaction instanceof Transaction) {
                            $shippingAddresses[$transaction->getShippingAddress()] = AddressDAO::getInstance()->getById($transaction->getShippingAddress());
                            $billingAddresses[$transaction->getBillingAddress()] = AddressDAO::getInstance()->getById($transaction->getBillingAddress());
                        }
                    }
                    foreach ($orders as $order) {
                        if ($order instanceof Order) {
                            $deliveryAddresses[$order->getDeliveryAddressId()] = AddressDAO::getInstance()->getById($order->getDeliveryAddressId());
                        }
                    }

                    // Store user-related data in an array
                    $searchFullUserInfo[] = [
                        'user' => $user,
                        'transactions' => $transactions,
                        'orders' => $orders,
                        'reviews' => $reviews,
                        'shippingAddresses' => $shippingAddresses,
                        'billingAddresses' => $billingAddresses,
                        'deliveryAddresses' => $deliveryAddresses,
                    ];
                }

                // Enregistrement dans la session
                $_SESSION['searchFullUserInfo'] = $searchFullUserInfo;
            } else {
                $_SESSION['message'] = 'Aucun utilisateur trouvé.';
            }

            header('Location: admin/users.php');
            exit;
        }
    }

    public function getActionName(): string
    {
        return 'searchFullUserInfo';
    }
}