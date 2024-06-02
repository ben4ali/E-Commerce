<?php $title = 'Inscription';

include_once __DIR__ . '/userIncludes.php';

if (isset($_SESSION['logged_in'])) {
    // L'utilisateur est déjà connecté, ne peut pas être dans la page de connexion.
    header("Location: profile.php");
}

if (!empty($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
    <body>
    <div class="main-div">
        <div class="mainContainer-signIn container d-flex justify-content-center align-items-center">
            <div class="logoBox d-flex justify-content-end">
                <img src="../images/icons/shopNestIconTransparent.png" alt="logo" width="300px" id="logoBase">
            </div>
            <div class="signUpBox">
                <h3 class="font-weight-bolder d-flex align-self-center justify-content-center header-height">Inscription
                </h3>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <form action="../index.php?action=registerClient" method="POST" class="mt-3" id="form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstName" class="font-weight-bolder">Prénom :</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required
                                       placeholder="Entrez votre prénom">
                            </div>
                            <div class="form-group">
                                <label for="lastName" class="font-weight-bolder">Nom :</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required
                                       placeholder="Entrez votre nom">
                            </div>
                            <div class="form-group">
                                <label for="email" class="font-weight-bolder"
                                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">Email :</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       placeholder="Entrez un email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="typeCompte" class="font-weight-bolder">Type de compte :</label>
                                <select name="typeCompte" id="typeCompte" class="form-control" required>
                                    <option value="">--Choisir une option--</option>
                                    <option value="merchant">Marchand</option>
                                    <option value="client">Client</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="age" class="font-weight-bolder d-block mb-2">Votre âge :</label>
                                <input type="date" name="age" id="age" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="font-weight-bolder d-block mb-2" pattern="^\d{10}$">Numéro de
                                    téléphone :</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required
                                       pattern="^\+?\d{10,15}$" title="Enter a valid phone number">
                            </div>
                        </div>
                    </div>
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
                        <label for="password" class="font-weight-bolder">Mot de passe :</label>
                        <input id="password" class="form-control" type="password" name="password"
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                               title="Au moins une lettre majuscule et minuscule et au moins 8 charactères" required
                               placeholder="Entrez un mot de passe"/>
                               <input type="checkbox" onclick="togglePasswordView()">Afficher mot de passe
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="font-weight-bolder">Confirmation mot de passe
                            :</label>
                        <input type="password" class="form-control" id="password_confirmation"
                               name="password_confirmation" required
                               placeholder="Entrez le même mot de passe">
                    </div>
                    <!--
                    <div class="form-group">
                        <div class="h-captcha" data-sitekey="8d711b5a-ab00-4887-acb8-9deba844f487"></div>
                    </div>
                    -->
                    <!-- 
                        1: Envoyer formulaire de pré-inscription.
                        2: Rediriger l'utilisateur sur le second portail d'inscription.
                    -->
                    <div>
                        <input type="hidden" name="action" value="register">
                        <button type="submit" class="btn btn-outline-info btn-lg">S'inscrire</button>
                        <div class="g-signin2" data-onsuccess="onSignIn"></div>
                    </div>
                    <h4 class="mt-2">Ou</h4>
                    <div>
                        <a href="auth.php" class="btn btn-block btn-outline-success btn-lg">Connexion</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    </body>
<?php include("../template/footer.php"); ?>