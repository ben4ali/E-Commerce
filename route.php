<?php
// route.php

use controller\ControllerFactory;

require_once 'app/controller/ControllerFactory.php'; // includes all other controllers.

if(!isset($controllerFactory)) $controllerFactory = new ControllerFactory(); // inits so all controllers are registered.

if (isset($_GET['action']) && ControllerFactory::isRegistered($_GET['action'])) {
    ControllerFactory::get($_GET['action'])->execute();
}

/* Pas besoin de faire des trucs ici, tout est automatisé mtn  */