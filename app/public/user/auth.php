<?php $title = 'Authentication';

include_once __DIR__ . '/userIncludes.php';

if (isset($_SESSION['logged_in'])) {
    // L'utilisateur est déjà connecté, ne peut pas être dans la page de connexion.
    header("Location: profile.php");
}
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
    <body>
    <div class="main-div">
        <div class="mainContainer-auth container d-flex justify-content-center align-items-center">
            <div class="logoBox d-flex justify-content-center">
                <img src="../images/icons/shopNestIconTransparent.png" alt="logo" width="300px" id="logoBase">
            </div>

            <div class="loginBox justify-content-center">
                <h3 class="font-weight-bolder c-flex align-self-center justify-content-center">Identifiant</h3>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <!-- Authentication form.  -->
                <form action="../index.php?action=authenticateClient" method="post" class="mt-3" id="formLogin">
                    <div class="form-group">
                        <label for="email" class="font-weight-bolder">Email:</label>
                        <input type="text" class="form-control" id="email" name="email" required
                               placeholder="Entrez un email">
                    </div>
                    <!--Password-->
                    <!--TODO: Je met la fonction ici parce qu'elle veut pas fonctionner quand je la met dans un fichier externe. À régler plus tard.-->
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
                        <label for="password" class="font-weight-bolder">Mot de passe:</label>
                        <input type="password" class="form-control" id="password" name="password" value="password" required
                               placeholder="Entrez le mot de passe">
                        <label>
                            <input type="checkbox" onclick="togglePasswordView()">
                        </label>Afficher mot de passe
                    </div>
                    
                    <div>
                        <input type="checkbox" id="souvenir" name="souvenir_option" value="Se Souvenir">
                        <label for="souvenir" class="font-weight-bolder">Se souvenir</label>
                    </div>
                    <button type="submit" class="btn btn-block btn-outline-success btn-lg">Connexion</button>
                    <h4 class="mt-2">Ou</h4>
                    <div>
                        <a href="signIn.php" class="btn btn-outline-info btn-lg">S'inscrire</a>
                        <a href="email-form.php" class="btn btn-link">Mot de passe oublié?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </body>

<?php include_once("../template/footer.php"); ?>