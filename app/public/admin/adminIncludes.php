<?php

require_once __DIR__ . '/../../CSP.php';
require_once __DIR__ . '/../../Exceptions/CSPNotInitialized.php';

use Exceptions\CSPNotInitialized;

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$CSP = new CSP();
$CSP->initialize();

try {
    $CSP->add(
        ['https://code.jquery.com', 'https://cdnjs.cloudflare.com', 'https://maxcdn.bootstrapcdn.com'], // scripts
        ['self', 'https://cdn.jsdelivr.net', 'https://fonts.googleapis.com']                            // styles
    );

} catch (CSPNotInitialized $e) {
    // erreur lors de la création du content-security-policy
    $_SESSION['error'] = $e->getMessage();
    header('Location: errorForm.php');
    exit;
}
?>