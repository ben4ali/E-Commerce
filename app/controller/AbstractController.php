<?php
/**
 * File: AbstractController.php
 * Author: Antoine Langevin
 * Date: November 26, 2023
 * Description: Classe abstraite fournissant les différentes signatures et implémentation de méthodes
 * nécessaires au fonctionnement des contrôleurs.
 */

namespace controller;

use JetBrains\PhpStorm\NoReturn;


require_once __DIR__ . '/../model/DAO/UserDAO.php';
require_once __DIR__ . '/../model/User.php';

abstract class AbstractController
{

    /**
     * Contructeur de base pour les fichiers contrôleurs.
     */
    public function __construct()
    {
    }

    #[NoReturn] public function showLoginPage(): void
    {
        header('Location: auth.php');
        exit;
    }

    abstract public function execute(): void;

    abstract public function getActionName(): string;

}