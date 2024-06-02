<?php

$title = 'Info Page';
include("template/header.php");

use Exceptions\CSPNotInitialized;

require_once("../Exceptions/CSPNotInitialized.php");

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

?>

<div class="content container">
    <h2><?php
        if (!empty($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);}
        else
        {echo "Merci pour votre achat!";} 
        ?>
        </h2>
        
    <a href="index.php">Retour à la page d'accueil</a>
</div>

<?php
include_once("template/footer.php");
?>
