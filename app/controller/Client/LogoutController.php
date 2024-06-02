<?php

namespace controller\Client;

use controller\AbstractController;
use JetBrains\PhpStorm\NoReturn;

require_once __DIR__ . '/../AbstractController.php';

class LogoutController extends AbstractController
{
    #[NoReturn] public function execute(): void
    {
        session_start();
        session_destroy();
        header('Location: index.php');
        exit;
    }

    public function getActionName(): string
    {
        return 'logoutClient';
    }
}