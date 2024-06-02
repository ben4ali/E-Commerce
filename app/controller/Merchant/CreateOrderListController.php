<?php

namespace controller\Merchant;

use controller\AbstractController;
use JetBrains\PhpStorm\NoReturn;
use model\DAO\OrderDAO;
use model\DAO\TransactionDAO;

class CreateOrderListController extends AbstractController
{
    #[NoReturn] public function execute(): void
    {
        session_start();

        $businessId = $_SESSION['business']->getId() ?? null;

        if (!$businessId) {
            $_SESSION['error'] = 'Il y a eu un problème lors de la recherche de vos commandes.';
            header('Location: merchant/viewOrders.php');
            exit;
        }

        $orders = OrderDAO::getInstance()->getByBusinessId($businessId);
        $transactions = TransactionDAO::getInstance()->getByBusinessId($businessId);

        $_SESSION['orderListData'] = $orders;
        $_SESSION['transactionListData'] = $transactions;

        header('Location: merchant/viewOrders.php');
        exit;
    }

    public function getActionName(): string
    {
        return 'GetMerchantOrderList';
    }
}
