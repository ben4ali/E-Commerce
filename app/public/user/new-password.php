<?php $title = 'Réinitialisation du mot de passe';
include_once __DIR__ . '/userIncludes.php';
?>

    <body>

    <div class="row justify-content-center m-2 text-center">
        <div class="col-md-12">
            <h3>Réinitialisation de mot de passe</h3>
            <p>Vous pouvez visualiser ou changer les informations de votre compte ici.</p>
        </div>
        <div class="mt-4 mx-2 col-md-4 rounded-lg">
            <h4 class="text-left">Entrez votre email</h4>
            <form action="" method="post" class="mt-2" id="formReset">
                    <script>
                        function togglePasswordView(){
                            var x = document.getElementById("password");
                            if (x.type === "password") {
                            x.type = "text";
                            } else {
                            x.type = "password";
                            }
                        }
                    </script>
                <div class="form-group">
                    <label for="password"></label>
                    <input type="password" class="form-control" id="password" name="password" required
                           placeholder="Entrez un nouveau mot de passe">
                    <label>
                        <input type="checkbox" onclick="togglePasswordView()">
                    </label>Afficher mot de passe
                    <label for="password-confirmation"></label>
                    <input type="password" class="form-control" id="password-confirmation" name="password-confirmation"
                           required placeholder="Entrez à nouveau le mot de passe">
                </div>
                <!-- Ici, une fonction va être appelé pour envoyer un email à l'utilisateur via le server mail. -->
                <div>
                    <a href="auth.php" class="btn btn-outline-danger btn-lg">Retour</a>
                    <a href="profile.php" class="btn btn-lg btn-outline-success btn-light">Confirmer nouveau mot de
                        passe</a>
                    <!-- <button type="submit" class="btn btn-lg btn-outline-success btn-light">Confirmez nouveau mot de passe</button> -->
                </div>
            </form>
        </div>
    </div>
    </body>
<?php include("../template/footer.php"); ?>