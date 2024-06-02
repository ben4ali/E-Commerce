<?php $title = 'Error Form';
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

<div class="container">
    <h2><?php
        if (!empty($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        else{
            echo "Erreur introuvable";
        }
        ?>
        <?php
            if(isset($_COOKIE['cart'])) {
                echo "The 'cart' cookie is set.";
            } else {
                echo "The 'cart' cookie is not set.";
            }
        ?>
    </h2>
    <a href="index.php">Retour à la page d'accueil</a>
</div>

<?php
include_once("template/footer.php");
?>
