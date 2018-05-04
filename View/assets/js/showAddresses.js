function showAddresses() {


    debugger;
    var ajax = new XMLHttpRequest();
    var method = "GET";
    var url = "../Controller/showAddressesController.php";
    var asynchronous = true;
    ajax.open(method, url, asynchronous);
    ajax.send();
    ajax.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var data;
            data = JSON.parse(this.responseText);
            console.log(data);
            var html = "";
            for (var a = 0; a < data.length; a++) {
                var town = data[a].town;
                var hood = data[a].hood;
                var bl = data[a].bl;
                var entrance = data[a].entrance;
                var id = data[a].id;
                html += "<tr>";
                html += "<td id='dali'>" + town + "</td>";
                html += "<td>" + hood + "</td>";
                html += "<td>" + bl + "</td>";
                html += "<td>" + entrance + "</td>";
                // html += "<td>" + id + "</td>";
                html += "<td>" + "" +
                    "<form action='../Controller/deleteAddressController.php' method='post'>" +
                    "<input type='hidden' name='hidden_id' value=' " + id + " '  >" +
                    "<input style='color: marron;width: 100%' type='submit'  name='delete_address' " +
                    "value='Изтрий'></form>" + "</td>";


                html += "</tr>";
            }

            console.log(id);
            document.getElementById("data").innerHTML = html;
        }

    }
}
showAddresses();
