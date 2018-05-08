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
//            console.log(resp);
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
            productName.innerHTML = "Product: " + resp[0]["product_name"];

            var productPrice = document.createElement("h4");
            productPrice.setAttribute("id", "pizza-info-price");
            productPrice.innerHTML = "asfsdgdf" + " lv.";

            var img = document.createElement("img");
            img.style.borderRadius = "47%";
            img.style.width = "450px";
            img.setAttribute("id", "pizza-info");
            img.src = "../View/assets/images/" + resp[0]["img_url"];

            pizzaView.appendChild(productName);
            pizzaView.appendChild(productPrice);
            pizzaView.appendChild(img);

            for (var i in resp) {
                var ingrediance = document.createElement("p");
                ingrediance.setAttribute("id", "pizza-info-ing");
                ingrediance.innerHTML = "Products: " + resp[i]["name"];

                pizzaView.appendChild(ingrediance);
            }

            var btn = document.createElement("BUTTON");
            btn.setAttribute("value", resp[i]["id"]);
            btn.setAttribute("id", "pizza-info");
            btn.setAttribute("class", "pizza-info-class");
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
    sizeAjax.open("GET", "SizesController.php?size=" + producInd);
    sizeAjax.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var sizes = JSON.parse(this.responseText);
//            console.log(sizes);
            var totalPrice = document.getElementById("pizza-info-price");
            totalPrice.innerHTML = "Price: " + sizes.total + " lv.";

            createDropDown(sizes, "size-wrap", producInd, sizes.id);
        }
    };
    sizeAjax.send();
}

function createDropDown(sizes, containerId, producInd, defSizeId) {
    var select = document.createElement("SELECT");
    select.setAttribute("id", "size-dropdown");
    select.style.margin = "0px 0px 0px 30px";

    var selectName = document.createElement("DIV");
    selectName.style.padding = "10px";
    selectName.style.margin = "0px 0px 0px 50px";
    selectName.innerHTML = "Изберете Размер";

    var container = document.getElementById(containerId);
    container.appendChild(selectName);
//    console.log(sizes);

    for (var i = 0; i < sizes.sizeList.length; i++) {
        var option = document.createElement("OPTION");
        option.setAttribute("id", "size-option");
        option.setAttribute("value", sizes.sizeList[i].id);
        option.innerHTML = sizes.sizeList[i].size;

        if (option.value == sizes.sizeId) {
            option.selected = "selected";
        }

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
            var sizes = this.responseText;
//            console.log(sizes);
            document.getElementById("pizza-info-price").innerHTML = "Price: " + sizes + " lv.";

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
            var removeBtn = document.getElementsByClassName("pizza-info-class")[0];
            removeBtn.parentNode.removeChild(removeBtn);
            
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
                        catWrap.style.borderRadius = "10px";
                        catWrap.style.background = "white somke";
                        catWrap.style.fontStyle = "italic";
                        catWrap.style.fontSize = "1.1em";
                        catWrap.style.fontHeight = "1em";
                        catWrap.style.display = "inline-block";
                        catWrap.style.width = "250px";
                        catWrap.style.borderRadius = "10px";
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
                                    console.log(result);
                                    if (result) {

                                        var cb = document.getElementById("check-" + result.id);
                                        cb.checked = true;

                                        if (cb.checked === false) {
                                            cb.checked = true;
                                        }

                                        cb.addEventListener("click", function () {
                                            minusExtraPrice(result.id, productId);
                                            
                                        });

                                    } else {
                                        if (!result) {
                                             var cb = document.getElementById("check-" + result.id);
                                            cb.checked = false;
                                            
                                            cb.addEventListener("click", function () {
                                                addExtraPrice(result.id, productId);
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
            alert(response);
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
            var resp = JSON.parse(this.responseText);
            var price = document.getElementById("pizza-info-price");
            price.innerHTML = "Price: " + resp + " lv.";
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
            var resp = JSON.parse(this.responseText);
            console.log(resp);
            
            var price = document.getElementById("pizza-info-price");
            price.innerHTML = "Price: " + resp + " lv.";
        }
    };
    request.send("ingId=" + ingId + "&prdId=" + toProductId);
}

