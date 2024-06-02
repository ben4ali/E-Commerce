<?php

namespace controller\Merchant;

use controller\AbstractController;
use JetBrains\PhpStorm\NoReturn;
use model\DAO\AddressDAO;
use model\DAO\TransactionDAO;
use model\DAO\UserDAO;
use model\Transaction;

class CreateTransactionListController extends AbstractController
{

    #[NoReturn] public function execute(): void
    {
        session_start();

        $businessId = $_SESSION['business']->getId() ?? null;

        if ($businessId == null) {
            $_SESSION['error'] = 'Il y a eu un problème lors de la recherche de vos commandes.';
            header('Location: merchant/viewTransactions.php');
            exit;
        }

        $transactions = TransactionDAO::getInstance()->getByBusinessId($businessId);

        $users = [];
        $addresses = [];
        foreach ($transactions as $datum) {
            if($datum instanceof Transaction) {
                $users[$datum->getUserId()] = UserDAO::getInstance()->getById($datum->getUserId());
                $addresses[$datum->getBillingAddress()] = AddressDAO::getInstance()->getById($datum->getBillingAddress());
            }
        }


        $_SESSION['transactionListData'] = $transactions;
        $_SESSION['userData'] = $users;
        $_SESSION['addresses'] = $addresses;

        header('Location: merchant/viewTransactions.php');
        exit;
    }

    public function getActionName(): string
    {
        return 'GetMerchantTransactionList';
    }
}