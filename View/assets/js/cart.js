/* getCartContent is function that show user's
 *  products and allow to modify the quantity of the
 *  selected product  */
getCartContent();
function getCartContent() {
    var request = new XMLHttpRequest();
    request.open("POST", "ProductsController.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var response = JSON.parse(this.responseText);
            console.log(response);
            if (!response) {
                window.location.replace("../Controller/indexController.php?page=login");
            }
            generateCartList(response, "tprod");
        }
    };
    request.send("cart=1");
}

function generateCartList(response, containerId) {
    console.log(response);
    var basicContent = document.getElementById(containerId);
    var table = document.getElementsByClassName("products_table");

    var tableData = document.getElementById("data");

    for (var i in response.pizzaList) {

        var tr = document.createElement('tr');

        var id = response.pizzaList[i].id;

        var productName = document.createElement('td');
        productName.setAttribute("id", "n-" + id);
        productName.innerHTML = response.pizzaList[i].name;
        
        var productPrice = document.createElement('td');
        productPrice.innerHTML = response.pizzaList[i].price + " lv.";
        productPrice.setAttribute("id", "price-1" + response.pizzaList[i].price);
        
          var productQuantity = document.createElement('td');
        productQuantity.setAttribute("id", "q-" + response.pizzaList[i].id);
        productQuantity.innerHTML = response.pizzaList[i].quantity;

        var productSize = document.createElement("td");
        productSize.innerHTML = response.pizzaList[i].size;

        var img = document.createElement('img');
        img.src = "../View/assets/images/" + response.pizzaList[i].img_url;
        var imgTd = document.createElement("td");
        imgTd.appendChild(img);

        var total = document.createElement("p");
        total.innerHTML = "Total price: " + response.total + " lv.";
        
        var plus = document.createElement('button');
        plus.setAttribute("value", response.pizzaList[i].id);
        plus.innerHTML = "Add one more";
        plus.addEventListener("click", function () {
            plusQunatity(this.value);
        });
        var plusTd = document.createElement('td');
        plusTd.appendChild(plus);

        var minus = document.createElement('button');
        minus.setAttribute("value", response.pizzaList[i].id);
        minus.innerHTML = "Remove one";
        minus.addEventListener("click", function () {
            minusQunatity(this.value);
        });
        var minusTd = document.createElement('td');
        minusTd.appendChild(minus);

        var priceHolder = document.createElement("input");
        priceHolder.setAttribute("type", "hidden");
        priceHolder.setAttribute("id", "price-" + response.pizzaList[i].id);
        priceHolder.setAttribute("value", productPrice.innerHTML);

        if (response.pizzaList[i].extraIng) {
            for (var k in response.pizzaList[i].extraIng) {
//                alert(response[i]["extraIng"][k]);

                getExtraIng(response.pizzaList[i].extraIng[k], response.pizzaList[i].price, "n-" + id);
            }
        }

        tr.appendChild(productName);
        tr.appendChild(productPrice);
        tr.appendChild(productQuantity);
        tr.appendChild(productSize);
        tr.appendChild(imgTd);
        tr.appendChild(plusTd);
        tr.appendChild(minusTd);
        tableData.appendChild(tr);
        
    }
    
    table.appendChild(tableData);
    basicContent.appendChild(table);
    
    var finishBtn = document.createElement("BUTTON");
    finishBtn.setAttribute("id", "finish");
    finishBtn.innerHTML = "Finish Order";

    finishBtn.addEventListener("click", function () {
        finishOrder();
    });
    var tab = document.getElementByClassName("products_table");
    if (tab.children.length < 2) {
        finishBtn.style.display = "none";
    }
    
    var divWrap = document.createElement("DIV");
//    divWrap.appendChild(finishBtn);
    
    basicContent.appendChild(divWrap);
    basicContent.appendChild(total);
}

function plusQunatity(productId) {
    var request = new XMLHttpRequest();
    request.open("GET", "ProductsController.php?prId=" + productId);

    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var response = JSON.parse(this.responseText);
            console.log(response);
            if (!response) {
                return;
            }
            var price = document.getElementById("q-" + productId);
            price.innerHTML = response.quantity;

        }
    };
    request.send();
}

function minusQunatity(productId) {
    var request = new XMLHttpRequest();
    request.open("GET", "ProductsController.php?minusId=" + productId);

    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var response = JSON.parse(this.responseText);
            if (response == 0) {
                location.reload();
            }
//            console.log(response);
            var price = document.getElementById("q-" + productId);
            price.innerHTML = response.quantity;

        }
    };
    request.send();
}

function getExtraIng(ingId, productPrice, containerId) {
    var request = new XMLHttpRequest();
    request.open("GET", "ProductsController.php?extraIngId=" + ingId);

    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var response = JSON.parse(this.responseText);
//            console.log(response);
            var price = document.getElementById("price-" + productPrice);
            price.innerHTML = Number(price.innerHTML) + Number(response["price"]);

            var ingName = document.createElement("h5");
            ingName.setAttribute("id", "ing");
            ingName.innerHTML = "Extra Engredient: " + response["name"];
            var container = document.getElementById(containerId);
            container.appendChild(ingName);
        }
    };
    request.send();
}

function finishOrder() {

    var XML = new XMLHttpRequest();
    XML.open("POST", "PurchaseController.php");
    XML.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    XML.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var response = this.responseText;
            if (response == "success") {
                location.reload();
            }
        }
    };
    XML.send("finish_order=" + 1);
}