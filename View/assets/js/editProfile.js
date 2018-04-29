
function editInfo(){
    debugger;
    var first_name = document.getElementById("f_name").value;
    var last_name = document.getElementById("l_name").value;
    var email = document.getElementById("email").value;
    var oldpass = document.getElementById("oldpass").value;
    var pass = document.getElementById("pass").value;
    var rpass = document.getElementById("rpass").value;

    var editRequest = new XMLHttpRequest();
    editRequest.open("POST", "../Controller/editProfileController.php");
    editRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    editRequest.onreadystatechange = function(){
        if(this.readyState === 4 && this.status === 200){
            var editResponse = JSON.parse(this.responseText);
            console.log(editResponse);
            console.warn(editResponse);
            var containerId = "error-list";
            generateErrList(editResponse, containerId);
        }
    };

    editRequest.send("f_name=" +  first_name + "&l_name=" + last_name +
        "&email=" + email + "&oldpass=" + oldpass + "&password=" + pass + "&rpassword=" + rpass);

}


function generateErrList(err, containerId){
    var container = document.getElementById(containerId);
    container.innerHTML = "";
    var errName = document.createElement("h4");
    errName.setAttribute("id", "logErr");
    errName.innerHTML = err;

    container.appendChild(errName);

}





