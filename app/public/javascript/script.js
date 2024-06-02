function setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
}

function makeEditable(id) {
    const element = document.getElementById(id);
    if (element) {
        element.readOnly = false;
        element.focus();
    }
}


function getCookie(name) {
    const keyValue = document.cookie.match(`(^|;) ?${name}=([^;]*)(;|$)`);
    return keyValue ? keyValue[2] : null;
}


const elementsToStyle = {
    body: document.body,
    header: document.querySelector('header'),
    p: document.querySelectorAll('p'),
    a: document.querySelectorAll('a'),
    label: document.querySelectorAll('label'),
    th: document.querySelectorAll('th'),
    td: document.querySelectorAll('td'),
    h5: document.querySelectorAll('h5'),
    input: document.querySelectorAll('input'),
    select: document.querySelectorAll('select'),
    card: document.querySelectorAll('.card'),
    cardBody: document.querySelectorAll('.card-body'),
    jumbotron: document.querySelectorAll('.jumbotron'),
    btnLight: document.querySelectorAll('.btn-light'),
    mainContainerSignIn: document.querySelector(".mainContainer-signIn"),
    mainContainerAuth: document.querySelector(".mainContainer-auth"),
    btnDarkOutline: document.querySelectorAll(".btn-outline-dark"),
    logoImage: document.querySelectorAll('#logoBase')
};


function toggleDarkMode() {
    const darkModeEnabled = !document.body.classList.contains('dark-mode');
    setCookie('darkModeEnabled', darkModeEnabled ? '1' : '0', 1);

    const {
        body,
        header,
        p,
        a,
        label,
        th,
        td,
        h5,
        input,
        select,
        card,
        cardBody,
        jumbotron,
        btnLight,
        mainContainerSignIn,
        mainContainerAuth,
        btnDarkOutline,
        logoImage,
        darkModeBtn
    } = elementsToStyle;


    body.style.backgroundColor = darkModeEnabled ? 'rgb(30,30,30)' : 'white';
    body.style.color = darkModeEnabled ? 'white' : 'black';

    header.style.backgroundColor = darkModeEnabled ? 'rgb(15,15,15)' : 'white';
    header.style.color = darkModeEnabled ? 'white' : 'black';

    [p, a, label, th, td, h5].forEach(elements => {
        elements.forEach(element => {
            element.style.color = darkModeEnabled ? 'white' : 'black';
        });
    });

    [input, select].forEach(elements => {
        elements.forEach(element => {
            element.style.backgroundColor = darkModeEnabled ? 'rgb(120,120,120)' : 'white';
            element.style.color = darkModeEnabled ? 'white' : 'black';
        });
    });

    [card, cardBody, btnLight].forEach(elements => {
        elements.forEach(element => {
            element.style.backgroundColor = darkModeEnabled ? 'rgb(40,40,40)' : 'white';
        });
    });

    [mainContainerSignIn, mainContainerAuth].forEach(container => {
        if (container) {
            container.style.backgroundColor = darkModeEnabled ? 'rgb(42,42,42)' : 'whitesmoke'
        }
    });

    if (logoImage) {
        logoImage.forEach(element => {
            element.src = darkModeEnabled ? 'images/icons/whiteShopNestIconTransparent.png' : 'images/icons/shopNestIconTransparent.png';
        });
    }
    if (darkModeBtn) {
        console.log("test")
        darkModeBtn.forEach(element => {
            element.src = darkModeEnabled ? 'images/icons/lightBublWhite.png' : 'images/icons/lightBuble.png';
        });
    }
    if (jumbotron) {
        jumbotron.forEach(element=>{
            element.style.backgroundColor = darkModeEnabled ? 'rgb(37,37,37)' : 'rgb(233, 236, 239)';
        })
    }

    btnDarkOutline.forEach(element => {
        element.style.color = darkModeEnabled ? 'white' : 'black';
        element.style.borderColor = darkModeEnabled ? 'white' : 'rgb(20,24,25)';
    });

    body.classList.toggle('dark-mode', darkModeEnabled);
}

function initializeQuantityControls(quantityInputId, btnMinusId, btnPlusId) {
    const quantityInput = document.getElementById(quantityInputId);
    const btnMinus = document.getElementById(btnMinusId);
    const btnPlus = document.getElementById(btnPlusId);
  
    if (quantityInput && btnMinus && btnPlus) {
      btnMinus.addEventListener("click", function () {
        decrementQuantity(quantityInput);
      });
  
      btnPlus.addEventListener("click", function () {
        incrementQuantity(quantityInput);
      });
    }
  
    function decrementQuantity(input) {
      let currentValue = parseInt(input.value);
      if (currentValue > 1) {
        input.value = currentValue - 1;
      }
    }
  
    function incrementQuantity(input) {
      let currentValue = parseInt(input.value);
      input.value = currentValue + 1;
    }
  }
  
  initializeQuantityControls("quantity", "btnMinus", "btnPlus");

document.addEventListener('DOMContentLoaded', function () {
    const darkModeButton = document.getElementById('darkModeButton');
    darkModeButton.addEventListener('click', toggleDarkMode);
    const darkModeCookie = getCookie('darkModeEnabled');
    if (darkModeCookie === '1') {
        toggleDarkMode();
    }
});

document.getElementById('deliveryOptionStandard').addEventListener('change', function() {
    document.getElementById('deliveryCost').textContent = '0.00 $';
    updateTotalCost();
});

document.getElementById('deliveryOptionExpress').addEventListener('change', function() {
    document.getElementById('deliveryCost').textContent = '12.35 $';
    updateTotalCost();
});

function updateTotalCost() {
    var deliveryCost = parseFloat(document.getElementById('deliveryCost').textContent);
    var totalCostField = document.getElementById('totalCost');
    var totalCostWithoutDelivery = parseFloat(totalCostField.getAttribute('data-total-cost-without-delivery'));
    var totalCost = totalCostWithoutDelivery + deliveryCost;
    totalCostField.textContent = totalCost.toFixed(2) + ' $';
}

document.querySelectorAll('.remove-item').forEach(function(button) {
    button.addEventListener('click', function() {
        var productId = this.getAttribute('data-product-id');
        removeFromCart(productId);
    });
});