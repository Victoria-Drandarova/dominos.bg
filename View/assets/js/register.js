
function register(){


    var first_name = document.getElementById("f_name").value;
    var last_name = document.getElementById("l_name").value;
    var email = document.getElementById("email").value;
    var pass = document.getElementById("pass").value;
    var rpass = document.getElementById("rpass").value;
    var request = new XMLHttpRequest();
    request.open("POST", "../Controller/registerController.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function(){
        if(this.readyState === 4 && this.status === 200){
            var response =  JSON.parse(this.responseText);
            console.log(response);
            var containerId = "err-list";
            generateErrList(response, containerId);
            // var url = '../Controller/indexController.php?page=login';
            // window.location.replace(url);

        }
    };

    request.send("f_name=" +  first_name + "&l_name=" + last_name +
    "&email=" + email + "&password=" + pass + "&rpassword=" + rpass);


}
//index.php?name=viki&pass=123

function generateErrList(err, containerId){
    var container = document.getElementById(containerId);
    container.innerHTML = "";
    // for(var i in err){
        var errName = document.createElement("h4");
        errName.setAttribute("id", "logErr");
        errName.innerHTML = err;

        container.appendChild(errName);

    // }

}

