/* getCartContent is function that show user's
 *  products and allow to modify the quantity of the
 *  selected product  */

function getCartContent() {
    var request = new XMLHttpRequest();
    request.open("POST", "ProductsController.php?");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var response = JSON.parse(this.responseText);
//            console.log(response);

            generateCartList(response, "cart_content");
        }
    };
    request.send("cart=1");
}
getCartContent();

function generateCartList(response, containerId) {

    var basicContent = document.getElementById(containerId);
    var table = document.createElement('table');

    table.setAttribute("id", "product-table");
    var tr = document.createElement('tr');

    var productName = document.createElement('th');
    productName.innerHTML = "Food";

    var price = document.createElement('th');
    price.innerHTML = "Price";

    var quantity = document.createElement('th');
    quantity.innerHTML = "Quantity";

    var img = document.createElement('th');
    img.innerHTML = "Image";

    var plus = document.createElement('th');
    plus.innerHTML = "Add one more";

    var minus = document.createElement('th');
    minus.innerHTML = "Remove one";

    tr.appendChild(productName);
    tr.appendChild(price);
    tr.appendChild(quantity);
    tr.appendChild(img);
    tr.appendChild(plus);
    tr.appendChild(minus);
    table.appendChild(tr);

    for (var i in response) {

        var tr = document.createElement('tr');

        var name = document.createElement('td');
        var price = document.createElement('td');
        var quantity = document.createElement('td');
        var img = document.createElement('img');

        var plus = document.createElement('button');
        plus.setAttribute("value", response[i]["id"]);

        var minus = document.createElement('button');
        minus.setAttribute("value", response[i]["id"]);

        name.innerHTML = response[i]["name"];
        price.innerHTML = response[i]["price"];
        quantity.innerHTML = response[i]["quantity"];
        quantity.setAttribute("id", "price-" + response[i]["id"]);
        img.src = "../View/assets/images/" + response[i]["img_url"];

        plus.innerHTML = "Add one more";
        plus.addEventListener("click", function () {
            plusQunatity(this.value);
        });
        minus.innerHTML = "Remove one";
        minus.addEventListener("click", function () {
            minusQunatity(this.value);
        });

        var plusTd = document.createElement('td');
        plusTd.appendChild(plus);

        var minusTd = document.createElement('td');
        minusTd.appendChild(minus);

        tr.appendChild(name);
        tr.appendChild(price);
        tr.appendChild(quantity);
        tr.appendChild(img);
        tr.appendChild(plusTd);
        tr.appendChild(minusTd);

        table.appendChild(tr);
    }
    basicContent.appendChild(table);
}

function plusQunatity(productId) {
    var request = new XMLHttpRequest();
    request.open("GET", "ProductsController.php?prId=" + productId);
    
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var response = this.responseText;
//            console.log(response);
            var price = document.getElementById("price-" + productId);
            price.innerHTML = response;

        }
    };
    request.send();
}


function minusQunatity(productId) {
    var request = new XMLHttpRequest();
    request.open("GET", "ProductsController.php?minusId=" + productId);
    
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var response = this.responseText;
//            console.log(response);
            var price = document.getElementById("price-" + productId);
            price.innerHTML = response;

        }
    };
    request.send();
}