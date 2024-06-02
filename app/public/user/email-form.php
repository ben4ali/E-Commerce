<?php $title = 'Réinitialisation mot de passe';
include_once __DIR__ . '/userIncludes.php';
?>

    <body>
    <div class="row justify-content-center m-2 text-center">
        <div class="col-md-12">
            <h3>Réinitialisation de mot de passe</h3>
            <p>Vous pouvez visualiser ou changer les informations de votre compte ici.</p>
        </div>
        <div class="mt-4 mx-2 col-md-4 rounded-lg">
            <h6 class="text-left">Entrez votre email</h6>
            <form action="" method="post" class="mt-2" id="formEmail">
                <div class="form-group">
                    <label for="email"></label>
                    <input type="text" class="form-control" id="email" name="email" required
                           placeholder="Entrez un email">
                </div>
                <p class="text-left">Un email va être envoyé pour réinitialiser votre mot de passe.</p>
                <!-- Ici, une fonction va être appelé pour envoyer un email à l'utilisateur via le server mail. -->
                <div>
                    <a href="auth.php" class="btn btn-outline-danger btn-lg">Retour</a>
                    <!-- pour l'instant, juste un a:href, mais va être un bouton de submit plus tard -->
                    <a href="code.php" class="btn btn-lg btn-outline-success btn-light">Continuer</a>
                    <!-- <button type="submit" class="btn btn-lg btn-outline-success btn-light">Continuez</button> -->
                </div>
            </form>
        </div>
    </div>
    </body>
<?php include("../template/footer.php"); ?>