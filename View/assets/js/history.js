function getHistory() {
    var historyAjax = new XMLHttpRequest();
    historyAjax.open("GET", "PurchaseController.php?get_history=1");
    historyAjax.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var listOfPurchase = JSON.parse(this.responseText);
            if (!listOfPurchase) {
                alert("empty  hostory!");
            }
//            console.log(listOfPurchase);
            generateHistoryList(listOfPurchase, "history-list");
        }
    };
    historyAjax.send();
}


function generateHistoryList(response, containerId) {
    var basicContent = document.getElementById(containerId);
    basicContent.innerHTML = "";
    var table = document.createElement('table');
    table.setAttribute("id", "histoy-table");
    var tr = document.createElement('tr');
    var date = document.createElement('th');
    date.innerHTML = "Date of Order";

    tr.appendChild(date);
    table.appendChild(tr);

    for (var i in response) {

        var tr = document.createElement('tr');
        tr.setAttribute("id", response[i]["user_order_id"]);
        var dateTd = document.createElement('td');

        var dateBtn = document.createElement("BUTTON");
        dateBtn.setAttribute("class", "history-btn");
        dateBtn.setAttribute("id", "date-" + response[i]["date"]);
        dateBtn.setAttribute("value", response[i]["user_order_id"]);
        dateBtn.innerHTML = response[i]["date"];

        dateBtn.addEventListener("click", function () {
            showDetailHistory(this.value);
        });

        dateTd.appendChild(dateBtn);
        tr.appendChild(dateTd);
        table.appendChild(tr);
    }
    basicContent.appendChild(table);
}
function showDetailHistory(historyId) {

    var XML = new XMLHttpRequest();
    XML.open("GET", "PurchaseController.php?historyId=" + historyId);
    XML.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var resp = JSON.parse(this.responseText);
//            console.log(resp);
            var divHolder = document.createElement("DIV");
            divHolder.setAttribute("id", historyId);

            generateHistoryDetails(resp, historyId);
        }
    };
    XML.send();
}

function generateHistoryDetails(arr, containerId) {
    var container = document.getElementById(containerId);
    container.setAttribute("id", "history-holder");
    container.innerHTML = "";
    var wrap = document.createElement("DIV");
    container.appendChild(wrap);

    for (var i in arr) {

        if (i == 0) {
            var name = document.createElement("p");
            name.setAttribute("class", "order-info-name");
            name.innerHTML = "Product: " + arr[i]["name"];

            var quantity = document.createElement("p");
            quantity.setAttribute("class", "order-info-name");
            quantity.innerHTML = "Quantity: " + arr[i]["quantity"];
            
            var size = document.createElement("p");
            size.setAttribute("class", "order-info-name");
            size.innerHTML = "Size: " + arr[i]["size"];

            var nn = document.createElement("p");
            nn.setAttribute("class", "order-info");
            nn.innerHTML = "Plus: " + arr[i]["in_name"];
            
            var total = document.createElement("h4");
            total.setAttribute("class", "total-price");
            total.innerHTML = "Total price: " + arr["total"] + "lv.";

            wrap.appendChild(name);
            wrap.appendChild(quantity);
            wrap.appendChild(size);
            wrap.appendChild(nn);

        } else {
            if (arr[i]["name"] === arr[i - 1]["name"]) {

                var nn = document.createElement("p");
                nn.setAttribute("class", "order-info");
                nn.innerHTML = "Plus: " + arr[i]["in_name"];
                wrap.appendChild(nn);
            } else {
                var name = document.createElement("p");
                name.setAttribute("class", "order-info-name");
                name.innerHTML = "Product: " + arr[i]["name"];

                var quantity = document.createElement("p");
                quantity.setAttribute("class", "order-info-name");
                quantity.innerHTML = "Quantity: " + arr[i]["quantity"];
                
                 var size = document.createElement("p");
                size.setAttribute("class", "order-info-name");
                size.innerHTML = "Quantity: " + arr[i]["size"];

                var nn = document.createElement("p");
                nn.setAttribute("class", "order-info");
                nn.innerHTML = "Plus: " + arr[i]["in_name"];
                wrap.appendChild(nn);
                wrap.appendChild(name);
                wrap.appendChild(size);
                wrap.appendChild(quantity);
                wrap.appendChild(nn);
            }
        }
        wrap.appendChild(total);
    }
    container.appendChild(wrap);
    container.appendChild(total);
}