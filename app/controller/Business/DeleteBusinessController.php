<?php

namespace controller\Business;

use controller\AbstractController;
use model\DAO\BusinessDAO;

require_once __DIR__ . '/../AbstractController.php';

class DeleteBusinessController extends AbstractController
{
    public function execute(): void
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $company = BusinessDAO::getInstance()->getById($_SESSION['company_id']);
            if ($company !== null) {
                BusinessDAO::getInstance()->delete($company);
            } else {
                header("Location: .php");
            }
            header("Location: .php");
            exit;
        }
    }

    public function getActionName(): string
    {
        return 'deleteCompany';
    }
}