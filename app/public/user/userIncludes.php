<?php
include("../template/header.php");

use Exceptions\CSPNotInitialized;
require_once("../../Exceptions/CSPNotInitialized.php");

// note, le $CSP devrait être initialisé dans le header.php
try {
    if (!isset($CSP)) throw new CSPNotInitialized("Le content-security-policy n'a pas été initialisé correctement.");
    $CSP->execute();
} catch (CSPNotInitialized $e) {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    // erreur lors de la création du content-security-policy
    $_SESSION['error'] = $e->getMessage();
    header('Location: errorForm.php');
    exit;
}