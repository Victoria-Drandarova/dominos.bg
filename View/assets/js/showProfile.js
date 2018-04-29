function setDetails() {

    var objReq = new XMLHttpRequest();
    objReq.onreadystatechange = function () {
        if(this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            document.getElementById("f_name").setAttribute("value", response.first_name);
            document.getElementById("l_name").setAttribute("value", response.last_name);
            document.getElementById("email").setAttribute("value", response.email);
            // alert(this.responseText);
        }

    };
    objReq.open("get", "../Controller/showProfileController.php");
    objReq.send();
}
setDetails();