<?php
require_once __DIR__ . '/../../model/User.php';
$title = 'Authentification et sécurité';

include_once __DIR__ . '/userIncludes.php';

if (!isset($_SESSION['logged_in'])) {
    // L'utilisateur n'est pas connecté, redirection à la page auth.php.
    header("Location: auth.php");
}
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
    <body>
    <div class="container mt-4">
        <div class="row justify-content-center m-2 text-center">
            <div class="col-md-12">
                <h3>Connexion et sécurité</h3>
                <p>Modifiez vos informations personnelles.</p>
            </div>
        </div>
        <!-- Formulaire de mise à jour d'information utilisateur -->
        <form action="../index.php?action=updateClient" method="post">
            <div class="form-group">
                <table class="table table-user-information">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    <tbody>
                    <tr>
                        <td class="font-weight-bolder">Prénom :</td>
                        <td class="text-primary">
                            <label for="prenom"></label><input type="text" class="form-control" name="prenom"
                                                               id="prenom"
                                                               value="<?php echo $_SESSION['user']->getFirstName(); ?>"
                                                               readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-info btn-md" onclick="makeEditable('prenom')">
                                Éditer
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bolder">Nom :</td>
                        <td class="text-primary">
                            <label for="nom"></label><input type="text" class="form-control" name="nom" id="nom"
                                                            value="<?php echo $_SESSION['user']->getLastName(); ?>"
                                                            readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-info btn-md" onclick="makeEditable('nom')">
                                Éditer
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bolder">Email :</td>
                        <td class="text-primary">
                            <label for="email"></label><input type="email" class="form-control" name="email" id="email"
                                                              value="<?php echo $_SESSION['user']->getEmail(); ?>"
                                                              readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-info btn-md" onclick="makeEditable('email')">
                                Éditer
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bolder">Téléphone primaire :</td>
                        <td class="text-primary">
                            <label for="telephone"></label><input type="tel" class="form-control" name="telephone"
                                                                  id="telephone"
                                                                  value="<?php echo $_SESSION['user']->getPhoneNumber(); ?>"
                                                                  readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-info btn-md"
                                    onclick="makeEditable('telephone')">Éditer
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <a href="deleteAccount.php" class="btn btn-block btn-outline-danger btn-lg">Supprimer Compte</a>
            </div>
            <div>
                <button type="submit" class="btn btn-block btn-outline-success btn-lg">Sauvegarder</button>
            </div>
            <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
        </form>
    </div>
    <script src="../javascript/script.js" defer></script>
    </body>

<?php
include_once("../template/footer.php");
?>