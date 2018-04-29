function setValues() {
    debugger;
    var city = document.getElementById("city").value;
    var hood = document.getElementById("hood").value;
    var blok = document.getElementById("blok").value;
    var entrance = document.getElementById("entrance").value;
    var addressRequest = new XMLHttpRequest();
    addressRequest.open("POST", "../Controller/addressController.php");
    addressRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    addressRequest.onreadystatechange = function(){
        if(this.readyState === 4 && this.status === 200){
            var addressResponse =  JSON.parse(this.responseText);
            console.log(addressResponse);
            var containerId = "addressError";
            generateErrList(addressResponse, containerId);
        }
    };
    addressRequest.send("city=" + city + "&hood=" + hood + "&blok=" + blok + "&entrance=" + entrance);

}

function generateErrList(err, containerId){
    var container = document.getElementById(containerId);
    container.innerHTML = "";
    var errName = document.createElement("h4");
    errName.setAttribute("id", "logErr");
    errName.innerHTML = err;
    container.appendChild(errName);

}