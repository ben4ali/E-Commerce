<?php
require_once __DIR__ . '/../../model/User.php';
$title = 'Suppression compte';
include_once __DIR__ . '/userIncludes.php';
?>

<?php
if (!isset($_SESSION['logged_in'])) {
    // L'utilisateur n'est pas connecté, redirection à la page auth.php.
    header("Location: auth.php");
}
?>

    <body>
    <div class="container mt-6">
        <div class="row justify-content-center m-2 text-center">
            <div class="col-md-6">
                <h3>Suppression du compte</h3>
                <p>Êtes-vous sûre de vouloir supprimer votre compte?
                    Toute les informations de cotre compte serons supprimées de nos bases de données.</p>
            </div>
        </div>
        <form action="../index.php?action=unregisterClient" method="post">
            <div>
                <a href="authentificationSecu.php" class="btn btn-block btn-outline-success btn-lg">Retour</a>
            </div>
            <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
            <button type="submit" class="btn btn-block btn-outline-danger btn-lg">Confirmer</button>
        </form>
    </div>
    </body>

<?php include("../template/footer.php"); ?>