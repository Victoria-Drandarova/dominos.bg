function getHistory(){
    var historyAjax = new XMLHttpRequest();
    historyAjax.open("GET", "PurchaseController.php?get_history=1");
    historyAjax.onreadystatechange = function(){
        if (this.readyState === 4 && this.status === 200) {
            var listOfPurchase = JSON.parse(this.responseText);
            if (!listOfPurchase) {
                alert("empty  hostory!");
            }
            console.log(listOfPurchase);
            generateHistoryList(listOfPurchase, "history-list");
        }
    };
    historyAjax.send();
}

function generateHistoryList(response, containerId) {
    var basicContent = document.getElementById(containerId);
    var table = document.createElement('table');

    table.setAttribute("id", "histoy-table");
    var tr = document.createElement('tr');
    var date = document.createElement('th');
    date.innerHTML = "Date of Order";

    tr.appendChild(date);
    table.appendChild(tr);

    for (var i in response) {

        var tr = document.createElement('tr');
        var dateTd = document.createElement('td');
        
        var dateBtn = document.createElement("BUTTON");
        dateBtn.setAttribute("class", "history-btn");
        dateBtn.setAttribute("id", "date-" + response[i]["date"]);
        dateBtn.setAttribute("value", response[i]["user_order_id"]);
        dateBtn.innerHTML = response[i]["date"];
        
        dateBtn.addEventListener("click", function(){
            showDetailHistory(this.value);
        });
        
        dateTd.appendChild(dateBtn);
        tr.appendChild(dateTd);
        table.appendChild(tr);
    }
    basicContent.appendChild(table);
}
function showDetailHistory(historyId){
    
    var XML = new XMLHttpRequest();
    XML.open("GET", "PurchaseController.php?historyId=" + historyId);
    XML.onreadystatechange = function(){
       if (this.readyState === 4 && this.status === 200) {
            var resp = JSON.parse(this.responseText);
            console.log(resp);
            
        }
    };
    
    XML.send();
}