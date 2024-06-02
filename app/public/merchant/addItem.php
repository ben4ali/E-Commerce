<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="../css/merchant.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../javascript/components/MerchantSideBarComponent.js"></script>
    <script src="../javascript/components/MerchantTopButtonsComponent.js"></script>
    <!-- DataTables dep. -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
</head>
<body>
<div class="container-fluid bg-light">
    <div class="row">
        <!-- Le menu sidebar -->
        <merchant-side-bar class="col-md-2 d-none d-md-block sidebar"
                           imgSrc="../images/icons/shopNestIconTransparent.png"></merchant-side-bar>

        <!-- Options du haut-->
        <div class="col-md-10">
            <div class="top-bar d-flex justify-content-between align-items-center">
                <h1>Overview - Commandes</h1>
                <a href="" class="text-decoration-none btn-lg account-button">
                    <img class="rounded-circle" src="../images/profiles/AntoineLangevin.png" alt="logo" width="40px"
                         height="40px">
                    Antoine Langevin
                </a>
            </div>
            <!-- Macro -->
            <merchant-top-bar></merchant-top-bar>

            <div class="main-body p-4">
                <h3>Ajout d'item</h3>
                <p>Vous pouvez rajouter un item ici, veuillez fournir toutes les informations demandées.</p>
                <p>Dès que vous cliquer sur le bouton 'Enregistrer', le produit va être mis en vente.</p>
                <form action="../index.php?action=merchantAddItem" method="POST">
                    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Prix (CAD):</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantité:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="upc" class="form-label">UPC:</label>
                        <input type="text" class="form-control" id="upc" name="upc" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryId" class="form-label">ID de catégorie:</label>
                        <p>Si vous ne connaissez pas votre ID de catégorie, vous pouvez toujours en prendre connaissance <a href="">ici</a></p>
                        <input type="number" class="form-control" id="categoryId" name="categoryId" required>
                    </div>
                    <div class="mb-3">
                        <label for="imageUrl" class="form-label">Image:</label>
                        <input type="file" class="form-control" id="imageUrl" name="imageUrl">
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>