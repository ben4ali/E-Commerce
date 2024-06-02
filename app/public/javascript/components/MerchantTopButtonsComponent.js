class MerchantTopButtonsComponent extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({mode: 'open'});
    }

    connectedCallback() {
        // Template for the custom element.
        const template = document.createElement('template');
        template.innerHTML = `
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/merchant.css">
       
        <div class="module-buttons text-decoration-none text-center">
                <a href="/index.php?action=GetMerchantOrderList" class="module-button btn-commandes"><i class="fas fa-chart-line"></i> Commandes</a>
                <a href="/index.php?action=GetMerchantTransactionList" class="module-button btn-transactions"><i
                        class="fas fa-shopping-cart"></i> Transactions</a>
                <a href="analytics.php?redirect=revenues" class="module-button btn-revenues"><i class="fas fa-user"></i>
                    Total revenues</a>
                <a href="analytics.php?redirect=sells" class="module-button btn-ventes"><i class="fas fa-industry"></i>
                    Total ventes</a>
            </div>
    `;
        this.shadowRoot.appendChild(template.content.cloneNode(true));
    }
}

// Define the new element
customElements.define('merchant-top-bar', MerchantTopButtonsComponent);
