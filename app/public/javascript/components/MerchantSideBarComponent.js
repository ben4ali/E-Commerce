class MerchantSideBarComponent extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({mode: 'open'});
    }

    connectedCallback() {
        const imgSrc = this.getAttribute('imgSrc');
        // Template for the custom element.
        const template = document.createElement('template');
        template.innerHTML = `
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/merchant.css">
       
        <div class="sidebar-merchant sidebar-sticky">
            <div class="menuTitle">
                <h5>
                    <img src="${imgSrc}" alt="logo" width="70px" height="70px">
                    ShopNest
                </h5>
            </div>
            <h5 class="text-center">Bonjour, Antoine</h5>
            <ul class="nav flex-column menuBtn text-center">
                <li><a href="/merchant/dashboard.php" class="btn btn-link"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="/index.php?action=CreateMerchantItemList"><i class="fas fa-icons"></i> Produits</a></li>
                <li><a href="/index.php?action=GetMerchantOrderList" class="btn btn-link"><i class="fas fa-box"></i> Commandes</a></li>
                <li><a href="/merchant/analytics.php" class="btn btn-link"><i class="fas fa-chart-line"></i> Statistiques</a></li>
                <li><a href="/index.php?action=GetMerchantTransactionList" class="btn btn-link"><i class="fas fa-money-bill"></i> Transactions</a></li>
            </ul>
            <a href="/index.php?action=logoutClient" class="btn-primary btn-lg text-decoration-none logoutBtn">Déconnexion</a>
        </div>
    `;
        this.shadowRoot.appendChild(template.content.cloneNode(true));
    }
}

// Define the new element
customElements.define('merchant-side-bar', MerchantSideBarComponent);
