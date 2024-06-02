<?php

namespace controller\Services;

use controller\AbstractController;
use JetBrains\PhpStorm\NoReturn;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../Utils/populatedb.php';
require_once __DIR__ . '/../../CSRF.php';

class PopulateDBController extends AbstractController
{

    #[NoReturn] public function execute(): void
    {
        // validation du token csrf
        $token = filter_input(INPUT_POST, 'csrf');
        session_match_tokens($token);
        $_SESSION['message'] = INIT_populateDB();
        header('Location: admin/services.php');
        exit;
    }

    public function getActionName(): string
    {
        return 'adminPopulateBD';
    }
}