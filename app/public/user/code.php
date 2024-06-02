<?php $title = 'Réinitialisation mot de passe';
include_once __DIR__ . '/userIncludes.php';
?>

    <body>
    <div class="row justify-content-center m-2 text-center">
        <div class="mt-4 mx-2 col-md-4 rounded-lg">
            <h3 class="text-left">Vérification du code</h3>
            <form action="" method="post" class="mt-2">
                <div class="form-group">
                    <label for="email"></label>
                    <input type="text" class="form-control" id="email" name="email" required
                           placeholder="Entrez un email">
                </div>
                <p class="text-left">Un email a été envoyé pour réinitialiser votre mot de passe.</p>
                <div>
                    <a href="auth.php" class="btn btn-outline-danger btn-lg">Retour</a>
                    <a href="new-password.php" class="btn btn-lg btn-outline-success btn-light">Continuer</a>
                </div>
            </form>
        </div>
    </div>
    </body>
<?php include_once("../template/footer.php"); ?>