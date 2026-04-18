// Get various elements from the DOM
e_cookie = document.getElementById('cookie');
e_cookieQuantity = document.getElementById('cookieQuantity');
e_cookieQuantity_under = document.getElementById('cookieQuantity_under');
e_cookieCps = document.getElementById('cookieCps');
e_products = document.getElementById('products');

// Create eventlistener on cookie
e_cookie.addEventListener('click', cookieClick);

// Initialise variables
var cookies = 0;
var cps = 0;
var cookiesPerClick = 1;
var ticksPerSecond = 10;
var storeOptions = {};

// User clicks the giant cookie
function cookieClick(e) {
    // Prevent default
    e.preventDefault();

    // They get a cookie
    cookies += cookiesPerClick;
    // Show them how many cookies they have
    displayCookies();

    // Create div showing cookie earnered
    var div = document.createElement("div");
    div.className = "click_feedback";
    div.style.left = e.clientX + "px";
    div.style.top = e.clientY + "px";
    div.innerHTML = "+" + cookiesPerClick;

    // Add the newly created div to the body
    document.body.appendChild(div);

    // Remove the div after the CSS animation has played
    setTimeout(function() {
        document.body.removeChild(div);
    }, 2500);
}

// Display cookies
function displayCookies() {
    // Check plurals
    if(Math.floor(cookies) == 1) {
        e_cookieQuantity_under.innerHTML = 'cookie';
    } else {
        e_cookieQuantity_under.innerHTML = 'cookies';
    }
    // Show number of cookies, always with 1 decimal place
    e_cookieQuantity.innerHTML = Math.floor(cookies).toLocaleString();

    // Update the title to match
    document.title = Math.floor(cookies).toLocaleString() + " " + e_cookieQuantity_under.innerHTML + " - Cookie Clicker";
}

// Adds items to the store
function addStoreOption(storeOption) {
    // Create long string of HTML code that represents a product. Fills in with details from storeOption object where appropriate.
    var s = "<li class='product' id='" + storeOption.id + "' onclick='purchaseStoreOption(" + storeOption.id + ")'>" +
            "<p class='product_owned'>" + ((storeOption.product_owned === 0) ? "" : storeOption.product_owned) + "</p>" +
            "<img src='" + storeOption.image_src + "' alt='" + storeOption.product_name +"' class='product_image' draggable='false' />" +
            "<div class='product_text'>" +
            "<p class='product_title'><span class='product_name'>" + storeOption.product_name + "</span> (<span class='product_cps'>" + storeOption.product_cps.toLocaleString() + "</span> cps)</p>" +
            "<p class='product_price'>" + storeOption.product_price.toLocaleString() + "</p></li>" +
            "</div>";
    // Updates the proudct list with the new product
    e_products.innerHTML += s;
}

// Handles clicking on a product in the store
function purchaseStoreOption(id) {
    // Check they have enough cookies to purchase product
    if(storeOptions[id].product_price <= cookies) {
        // If they do, subtract that number of cookies
        cookies -= storeOptions[id].product_price;
        // Incremement the product_owned value
        storeOptions[id].product_owned++;
        // Update the DOM to reflect the new product_owned value
        document.getElementById(id).getElementsByClassName('product_owned')[0].innerHTML = storeOptions[id].product_owned;

        // Recalculate the cps
        calculateCps();
    }
}

// Calculates cps
function calculateCps() {
    // Reset the cps to zero
    cps = 0;
    // Loop through products
    for(var i = 0, keys = Object.keys(storeOptions); i < keys.length; i++) {
        // For each product, total cps = number owned * cps of one
        cps += storeOptions[keys[i]].product_owned * storeOptions[keys[i]].product_cps;
    }

    // Display cps
    e_cookieCps.innerHTML = cps.toLocaleString(undefined, {minimumFractionDigits: 1, maximumFractionDigits: 1});
}

// Every tick, update the number of cookies and display it
function gameLoop() {
    // Add the cookies earned in the last tick to the total
    cookies += (cps / ticksPerSecond);
    displayCookies();
}

// Creates example storeOption object
storeOptions = {
    1: {
        id: 1,
        product_owned: 0,
        image_src: "img/cursor.svg",
        product_name: "Cursor",
        product_cps: 0.1,
        product_price: 10
    },
    2: {
        id: 2,
        product_owned: 0,
        image_src: "img/oven.svg",
        product_name: "Oven",
        product_cps: 1.5,
        product_price: 100
    },
    3: {
        id: 3,
        product_owned: 0,
        image_src: "img/chef.svg",
        product_name: "Chef",
        product_cps: 20,
        product_price: 1000
    }
};
// Adds the storeOptions to the page
addStoreOption(storeOptions["1"]);
addStoreOption(storeOptions["2"]);
addStoreOption(storeOptions["3"]);

// Set gameLoop running
window.setInterval(gameLoop, (1000 / ticksPerSecond));
