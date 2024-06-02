<?php
$title = 'Paiement';
include_once __DIR__ . '/userIncludes.php';
if (!isset($_SESSION['logged_in'])) {
    // L'utilisateur n'est pas connecté, redirection à la page auth.php.
    header("Location: auth.php");
}
$userName = $_SESSION['logged_in'];
?>

    <body>
    <!--Afficher les cartes grâce à "ajouter votre carte"-->

    <div class="container mt-4">
        <div class="row justify-content-center m-2 text-center">
            <div class="col-md-12">
                <h3>Vos informations de paiement</h3>
                <p>Entrez vos informations de paiement ci-dessous.</p>
            </div>
        </div>

        <h4>Entrez vos informations</h4>
        <form action="processPayment.php" method="post">
            <div class="form-group">
                <label for="cardName" class="font-weight-bolder">Nom sur la carte :</label>
                <input type="text" class="form-control text-uppercase" id="cardName" name="cardName" placeholder="PRÉNOM NOM"
                       inputmode="text" required>
            </div>
            <div class="form-group">
                <label for="cardNumber" class="font-weight-bolder">Numéro de carte :</label>
                <input type="text" class="form-control" id="cardNumber" name="cardNumber"
                       placeholder="1111 2222 3333 4444" inputmode="numeric" required>
            </div>
            <script>
                document.getElementById("cardNumber").addEventListener("input", function (e) {
                    var input = this.value.replace(/\D/g, "");
                    if (input.length > 16) {
                        input = input.slice(0, 16);
                    }
                    var formattedInput = "";
                    for (var i = 0; i < input.length; i += 4) {
                        formattedInput += input.slice(i, i + 4);
                        if (i + 4 < input.length) {
                            formattedInput += " ";
                        }
                    }
                    this.value = formattedInput;
                });
            </script>

            <div class="expirationBox">
                <label for="expirationDate" class="font-weight-bolder">Date d'expiration :</label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expirationMonth"></label><select name="expirationMonth" id="expirationMonth"
                                                                         class="form-control" required>
                                <option value="1" selected>01</option>
                                <option value="2">02</option>
                                <option value="3">03</option>
                                <option value="4">04</option>
                                <option value="5">05</option>
                                <option value="6">06</option>
                                <option value="7">07</option>
                                <option value="8">08</option>
                                <option value="9">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <select name="expirationMonth" id="expirationMonth" class="form-control" required>
                                <option value="2023" selected>2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="2030">2030</option>
                                <option value="2031">2031</option>
                                <option value="2032">2032</option>
                                <option value="2033">2033</option>
                                <option value="2034">2034</option>
                                <option value="2035">2035</option>
                                <option value="2036">2036</option>
                                <option value="2037">2037</option>
                                <option value="2038">2038</option>
                                <option value="2039">2039</option>
                                <option value="2040">2040</option>
                                <option value="2041">2041</option>
                                <option value="2042">2042</option>
                                <option value="2043">2043</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="cvv" class="font-weight-bolder">CVV :</label>
                <input type="text" class="form-control" id="cvv" name="cvv" inputmode="numeric" maxlength="3" size="3"
                       placeholder="123" required>
            </div>
            <div>
                <a href="signIn.php" class="btn btn-block btn-outline-success btn-lg">Ajouter votre carte</a>
            </div>
        </form>
    </div>
    </body>

<?php
include_once("../template/footer.php");
?>