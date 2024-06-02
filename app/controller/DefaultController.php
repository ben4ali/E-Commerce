<?php

namespace controller;

use JetBrains\PhpStorm\NoReturn;

class DefaultController extends AbstractController
{

    #[noreturn] public function execute(): void
    {
        $this->showLoginPage();
    }

    public function getActionName(): string
    {
        return 'showLoginPage';
    }
}