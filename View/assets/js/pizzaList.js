function getPizzaList() {
    var request = new XMLHttpRequest();
    request.open("GET", "ProductsController.php?pizza=1");
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var resp = JSON.parse(this.responseText);
//            console.log(resp);
            var container = document.getElementById("pizza-conainer");
            var wrap = document.createElement("DIV");
            wrap.setAttribute("id", "pizza-warp");
            for (var i in resp) {
                var pizzaWrap = document.createElement("DIV");
                pizzaWrap.setAttribute("id", "pizza");

                var name = document.createElement("p");
                name.setAttribute("id", "product-info");
                name.innerHTML = "Name " + resp[i]["name"];

                var price = document.createElement("p");
                price.setAttribute("id", "product-info");
                price.innerHTML = "Price " + resp[i]["price"];

                var img = document.createElement("img");
                img.src = "../View/assets/images/" + resp[i]["img_url"];

                var btn = document.createElement("BUTTON");
                btn.setAttribute("value", resp[i]["id"]);
                btn.setAttribute("id", "buy-btn");
                btn.innerHTML = "In Cart";
                btn.addEventListener("click", function () {
                    getProductInfo(this.value);
                });

                pizzaWrap.appendChild(name);
                pizzaWrap.appendChild(price);
                pizzaWrap.appendChild(img);
                pizzaWrap.appendChild(btn);
                wrap.appendChild(pizzaWrap);

            }
            container.appendChild(wrap);
        }
    };
    request.send();
}
getPizzaList();

function getProductInfo(ind) {
    var request = new XMLHttpRequest();
    request.open("GET", "ProductsController.php?productId=" + ind);
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var resp = JSON.parse(this.responseText);
//            console.log(resp);
            var container = document.getElementById("pizza-conainer");

            var pizza = document.getElementById("pizza-warp");
            container.removeChild(pizza);

            var pizzaView = document.createElement("DIV");
            pizzaView.setAttribute("id", "pizza-wrap");

//            var id = document.createElement("ipnut");
//            id.setAttribute("type", "hidden");
//            id.setAttribute("id", resp[0]["id"]);

            var productName = document.createElement("h3");
            productName.setAttribute("id", "pizza-info-name");
            productName.innerHTML = resp[0]["product_name"];

            var productPrice = document.createElement("h4");
            productPrice.setAttribute("id", "pizza-info-price");
            productPrice.innerHTML = resp[0]["product_price"];

            var img = document.createElement("img");
            img.setAttribute("id", "pizza-info");
            img.src = "../View/assets/images/" + resp[0]["img_url"];

            pizzaView.appendChild(productName);
            pizzaView.appendChild(productPrice);
            pizzaView.appendChild(img);
//            pizzaView.appendChild(id);

            for (var i in resp) {
                var ingrediance = document.createElement("p");
                ingrediance.setAttribute("id", "pizza-info-ing");
                ingrediance.innerHTML = resp[i]["name"];

                pizzaView.appendChild(ingrediance);
            }

            var btn = document.createElement("BUTTON");
            btn.setAttribute("value", resp[i]["id"]);
            btn.setAttribute("id", "pizza-info");
            btn.innerHTML = "Modify Content";
            btn.addEventListener("click", function () {
                getCategories(this.value);
            });

            var buyBtn = document.createElement("BUTTON");
            btn.setAttribute("value", resp[i]["id"]);
            buyBtn.setAttribute("id", "pizza-info");
            buyBtn.setAttribute("value", resp[0]["id"]);
            buyBtn.innerHTML = "Add to  cart";
            buyBtn.addEventListener("click", function () {
                addToCart(this.value);
            });
            pizzaView.appendChild(btn);
            pizzaView.appendChild(buyBtn);
            container.appendChild(pizzaView);
        }
    };
    request.send();
}

function getCategories(productId) {
    var request = new XMLHttpRequest();
    request.open("GET", "ProductsController.php?categories");
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var resp = JSON.parse(this.responseText);

            for (var j in resp) {
                var container = document.getElementById("pizza-wrap");
                var name = document.createElement("h3");
                name.setAttribute("id", "cat-wrap");
                name.innerHTML = resp[j]["name"];

                var container = document.getElementById("pizza-wrap");
                var req = new XMLHttpRequest();
                req.open("GET", "ProductsController.php?categoriesId=" + resp[j]["id"]);
                req.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        var res = JSON.parse(this.responseText);
//                        console.log(res);
                        var catWrap = document.getElementById("cat-wrap");
                        var ul = document.createElement("ul");
                        for (var k in res) {
                            var li = document.createElement("li");
                            li.innerHTML = res[k]["name"];

                            var checkbox = document.createElement("input");
                            checkbox.setAttribute("value", res[k]["id"]);
                            checkbox.setAttribute("id", "check-" + res[k]["id"]);
                            checkbox.setAttribute("type", "checkbox");
                            li.appendChild(checkbox);

                            checkbox.addEventListener("click", function () {
                                addExtraPrice(this.value, productId);
                            });
                            ul.appendChild(li);
                        }
                        catWrap.appendChild(ul);
                        container.appendChild(catWrap);
                    }
                };
                req.send();
                container.appendChild(name);
            }
        }
    };
    request.send();
}
function addExtraPrice(ingId, productId) {

    var getProductId = document.getElementById("check-" + ingId);
    if (getProductId.checked !== false) {
        getProductId.checked = true;
        addExtraIngr(ingId, productId);

    } else {
        if (getProductId.checked !== true) {
            getProductId.checked = false;
            minusExtraPrice(ingId, productId);
        }
    }
}

function minusExtraPrice(ingId, productId) {

    var request = new XMLHttpRequest();
    request.open("POST", "ProductsController.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var resp = this.responseText;
            console.log(resp);
            var price = document.getElementById("pizza-info-price");
            price.innerHTML = Number(price.innerHTML) - Number(resp);
        }
    };
    request.send("minusIngId=" + ingId + "&minusPrdId=" + productId);

}

function addToCart(productId) {

    var request = new XMLHttpRequest();
    request.open("POST", "ProductsController.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var response = this.responseText;
//            console.log(response);
            alert(response);


        }
    };
    request.send("proId=" + productId);
}

function addExtraIngr(ingId, toProductId) {
    var request = new XMLHttpRequest();
    request.open("POST", "ProductsController.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var resp = this.responseText;
            var price = document.getElementById("pizza-info-price");
            price.innerHTML = Number(price.innerHTML) + Number(resp);
        }
    };
    request.send("ingId=" + ingId + "&prdId=" + toProductId);
}

