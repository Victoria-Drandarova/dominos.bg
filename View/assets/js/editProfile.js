
function setDetails() {

    var objReq = new XMLHttpRequest();

    objReq.onreadystatechange = function () {
         if(this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            document.getElementById("f_name").setAttribute("value", response.fName);
            document.getElementById("l_name").setAttribute("value", response.lName);
            document.getElementById("email").setAttribute("value", response.email);
            // alert(this.responseText);
         }

    };
    objReq.open("get", "../Controller/editProfileController.php");
    objReq.send();
}

