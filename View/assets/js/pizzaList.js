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
                price.innerHTML = "Price " + resp[i]["price"] + " lv.";

                var img = document.createElement("img");
                img.setAttribute("id", "product-img");
                img.src = "../View/assets/images/" + resp[i]["img_url"];

                var btn = document.createElement("BUTTON");
                btn.setAttribute("value", resp[i]["id"]);
                btn.setAttribute("id", "buy-btn");
                btn.innerHTML = "Buy me!";
                btn.addEventListener("click", function () {
                    getProductInfo(this.value);
                    addToCart(this.value);
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

/* */
function getProductInfo(ind) {
    var request = new XMLHttpRequest();
    request.open("GET", "ProductsController.php?productId=" + ind);
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var resp = JSON.parse(this.responseText);

            if (!resp) {
                window.location.replace("../Controller/indexController.php?page=login");
            }

            var container = document.getElementById("pizza-conainer");

            var pizza = document.getElementById("pizza-warp");
            container.removeChild(pizza);

            var pizzaView = document.createElement("DIV");
            pizzaView.setAttribute("id", "pizza-wrap");

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
            getSizes(ind);
            pizzaView.appendChild(btn);

            container.appendChild(pizzaView);
        }
    };
    request.send();
}

function getSizes(producInd) {
    var sizeAjax = new XMLHttpRequest();
    sizeAjax.open("GET", "SizesController.php?size=1");
    sizeAjax.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var sizes = JSON.parse(this.responseText);
            createDropDown(sizes, "size-wrap", producInd);
        }
    };
    sizeAjax.send();
}

function createDropDown(sizes, containerId, producInd) {
    var select = document.createElement("SELECT");
    select.setAttribute("id", "size-dropdown");
    var container = document.getElementById(containerId);

    for (var i in sizes) {
        var option = document.createElement("OPTION");
        option.setAttribute("id", "size-option");
        option.setAttribute("value", sizes[i]["id"]);
        option.innerHTML = sizes[i]["size"];

        select.appendChild(option);

    }
    select.addEventListener("change", function () {
        changeSize(this.value, producInd);
    });
    container.appendChild(select);
}

function changeSize(size, productId) {
    var changeSize = new XMLHttpRequest();
    changeSize.open("GET", "SizesController.php?changeSize=" + size + "&productId=" + productId);
    changeSize.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var sizes = JSON.parse(this.responseText);
//            console.log(sizes);
            var productPrice = document.getElementById("pizza-info-price").innerHTML = sizes;;
        }
    };
    changeSize.send();
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

                        var catWrap = document.getElementById("cat-wrap");
                        var ul = document.createElement("ul");
                        for (var k in res) {

                            var checkbox = document.createElement("input");
                            checkbox.setAttribute("value", res[k]["id"]);
                            checkbox.setAttribute("id", "check-" + res[k]["id"]);
                            checkbox.setAttribute("type", "checkbox");

                            var XML = new XMLHttpRequest();
                            XML.open("GET", "ProductsController.php?iId=" + res[k]["id"]
                                    + "&proId=" + productId);
                            XML.onreadystatechange = function () {
                                if (this.readyState === 4 && this.status === 200) {
                                    var result = JSON.parse(this.responseText);

                                    if (result) {

                                        var cb = document.getElementById("check-" + result.id);
                                        cb.checked = true;

                                        if (cb.checked === false) {
                                            cb.checked = true;
                                        }
                                        var price = document.getElementById("pizza-info-price");
                                        price.innerHTML = Number(price.innerHTML)
                                                + Number(result["price"]);
                                        cb.addEventListener("click", function () {
                                            minusExtraPrice(result["id"], productId);
                                            ;
                                        });

                                    } else {
                                        if (!result) {
                                            checkbox.checked = false;
                                            checkbox.addEventListener("click", function () {
                                                addExtraPrice(result["id"], productId);
                                                ;
                                            });
                                        }
                                    }
                                }
                            };
                            XML.send();
                            var li = document.createElement("li");
                            li.innerHTML = res[k]["name"];

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

function addToCart(productId) {

    var request = new XMLHttpRequest();
    request.open("POST", "ProductsController.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var response = this.responseText;

            if (!response) {
                window.location.replace("../Controller/indexController.php?page=login");
            }
        }
    };
    request.send("proId=" + productId);
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
            var price = document.getElementById("pizza-info-price");
            price.innerHTML = Number(price.innerHTML) - Number(resp);
        }
    };
    request.send("minusIngId=" + ingId + "&minusPrdId=" + productId);

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

