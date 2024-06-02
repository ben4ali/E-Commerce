<?php $title = 'Inscription';
include("../template/header.php"); ?>

<?php
/*
if (isset($_SESSION['logged_in'])) {
    // L'utilisateur est déjà connecté, ne peut pas être dans la page de connexion.
    header("Location: profile.php");
}
*/

$error = '';
if (isset($_SESSION['error'])) {
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
            <div class="merchantBox">
                <h3 class="font-weight-bolder d-flex align-self-center justify-content-center header-height">Portail
                    Marchand - Inscription</h3>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <p><br>
                    Pour mieux service vous et vos clients, nous avons besoin de quelques informations
                    sur votre entreprise. Merci de fournir les bonnes informations.
                </p>
                <form action="../index.php?action=registerMerchant" method="post" class="mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="companyName" class="font-weight-bolder">Nom entreprise :</label>
                                <input type="text" class="form-control" id="companyName" name="companyName" required
                                       placeholder="Nom de votre compagnie">
                            </div>
                            <div class="form-group">
                                <label for="employees" class="font-weight-bolder">Nombre d'employé :</label>
                                <select name="employees" id="employees" required class="form-control">
                                    <option value="">--Choisir une option--</option>
                                    <option value="micro">1-10</option>
                                    <option value="small">11-50</option>
                                    <option value="medium">51-250</option>
                                    <option value="large">250+</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="workEmail" class="font-weight-bolder"
                                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">Email de l'entreprise :</label>
                                <input type="email" class="form-control" id="workEmail" name="workEmail" required
                                       placeholder="Votre email de compagnie">
                            </div>
                            <div class="form-group">
                                <label for="siteweb" class="font-weight-bolder d-block mb-2">URL site web de
                                    l'entreprise :</label>
                                <input type="text" name="siteweb" id="siteweb" class="form-control">
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="street">Street:</label>
                                    <input type="text" id="street" name="street" class="form-control"/>
                                    <label for="city">City:</label>
                                    <input type="text" id="city" name="city" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="province">Province:</label>
                                <input type="text" id="province" name="province" class="form-control"/>
                                <label for="country">Country:</label>
                                <input type="text" id="country" name="country" class="form-control"/>
                                <label for="postal_code">Postal Code:</label>
                                <input type="text" id="postal_code" name="postal_code" class="form-control"/>
                                <label for="typeCompte" class="font-weight-bolder">Type de l'entreprise :</label>
                                <label for="typeCompany"></label><select name="typeCompany" id="typeCompany" required
                                                                         class="form-control">
                                    <option value="">--Choisir une option--</option>
                                    <option value="elevage">Élevage</option>
                                    <option value="culturesAgricoles">Cultures agricoles</option>
                                    <option value="sylviculture">Sylviculture</option>
                                    <option value="pecheAquaculture">Pêche et aquaculture</option>
                                    <option value="minesCharbonMineraux">Mines de charbon et minéraux</option>
                                    <option value="extractionPetroleGaz">Extraction de pétrole et de gaz</option>
                                    <option value="extractionPierresPrecieuses">Extraction de pierres précieuses et
                                        métaux précieux
                                    </option>
                                    <option value="alimentaireBoissons">Alimentaire et boissons</option>
                                    <option value="textilesHabillement">Textiles et habillement</option>
                                    <option value="produitsElectroniques">Produits électroniques et informatiques
                                    </option>
                                    <option value="produitsChimiques">Produits chimiques</option>
                                    <option value="machinesEquipements">Machines et équipements</option>
                                    <option value="productionDistributionElectricite">Production et distribution
                                        d'électricité
                                    </option>
                                    <option value="energiesRenouvelables">Energies renouvelables</option>
                                    <option value="distributionGaz">Distribution de gaz</option>
                                    <option value="batimentResidentiel">Bâtiment résidentiel</option>
                                    <option value="batimentNonResidentiel">Bâtiment non résidentiel</option>
                                    <option value="travauxPublics">Travaux publics et infrastructures</option>
                                    <option value="commerceGros">Commerce de gros</option>
                                    <option value="commerceDetail">Commerce de détail</option>
                                    <option value="ecommerce">E-commerce</option>
                                    <option value="transportAerien">Transport aérien</option>
                                    <option value="transportMaritime">Transport maritime</option>
                                    <option value="transportFerroviaire">Transport ferroviaire</option>
                                    <option value="transportRoutier">Transport routier</option>
                                    <option value="banques">Banques</option>
                                    <option value="assurances">Assurances</option>
                                    <option value="investissements">Investissements</option>
                                    <option value="agencesImmobilières">Agences immobilières</option>
                                    <option value="gestionImmobilier">Gestion immobilière</option>
                                    <option value="promotionImmobilière">Promotion immobilière</option>
                                    <option value="logiciels">Logiciels</option>
                                    <option value="servicesInformatiques">Services informatiques</option>
                                    <option value="hardware">Hardware</option>
                                    <option value="hopitaux">Hôpitaux</option>
                                    <option value="cliniques">Cliniques</option>
                                    <option value="servicesParamedicaux">Services paramédicaux</option>
                                    <option value="ecolesPrimairesSecondaires">Écoles primaires et secondaires</option>
                                    <option value="enseignementSuperieur">Enseignement supérieur</option>
                                    <option value="formationProfessionnelle">Formation professionnelle</option>
                                    <option value="cinemaProduction">Cinéma et production audiovisuelle</option>
                                    <option value="musique">Musique</option>
                                    <option value="jeuxVideo">Jeux vidéo</option>
                                    <option value="tourisme">Tourisme</option>
                                    <option value="conseil">Conseil</option>
                                    <option value="avocats">Avocats</option>
                                    <option value="architectes">Architectes</option>
                                    <option value="administrationLocale">Administration locale</option>
                                    <option value="administrationNationale">Administration nationale</option>
                                    <option value="servicesUrgence">Services d'urgence</option>
                                    <option value="televisionRadio">Télévision et radio</option>
                                    <option value="presseEcrite">Presse écrite</option>
                                    <option value="internetMediasSociaux">Internet et médias sociaux</option>
                                    <option value="hotels">Hôtels</option>
                                    <option value="restaurants">Restaurants</option>
                                    <option value="cafes">Cafés</option>
                                    <option value="charites">Charités</option>
                                    <option value="ong">ONG</option>
                                    <option value="associationsProfessionnelles">Associations professionnelles</option>
                                    <option value="musees">Musées</option>
                                    <option value="theatres">Théâtres</option>
                                    <option value="galeriesArt">Galeries d'art</option>
                                    <option value="biotechnologies">Biotechnologies</option>
                                    <option value="pharmaceutiques">Pharmaceutiques</option>
                                    <option value="rechercheAcademique">Recherche académique</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ageWork" class="font-weight-bolder d-block mb-2">Date création
                                    compagnie :</label>
                                <input type="date" name="ageWork" id="ageWork" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="phoneWork" class="font-weight-bolder d-block mb-2" pattern="^\d{10}$">Numéro
                                    de téléphone de la compagnie :</label>
                                <input type="tel" name="phoneWork" id="phoneWork" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="ne" class="font-weight-bolder d-block mb-2">Numéro d'entreprise
                                    (NE) :</label>
                                <input type="text" name="ne" id="ne" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="font-weight-bolder d-block mb-2">Description :</label>
                        <textarea name="description" id="description" rows="3" cols="50"></textarea>
                    </div>
                    <input type="hidden" name="action" value="registerBusinessLastStep">
                    <button type="submit" class="btn btn-outline-info btn-lg">S'enregistrer</button>
                    <a href="signIn.php" class="btn btn-link">Retour</a>
                </form>
            </div>
        </div>
    </div>
    </body>
<?php include("../template/footer.php"); ?>