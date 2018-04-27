<?php
?>



<!DOCTYPE html>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../View/assets/loginRegistration.css" rel="stylesheet" type="text/css">

</head>
<body>
<section id="login">
    <!--<form action="../Controller/UserController.php" method="post" style="max-width:500px;margin:auto">-->
    <div style="max-width:500px;margin:auto">
        <h2>Промяна на профил</h2>
        <div class="input-container">
            <i class="fa fa-user icon"></i>
            <input class="input-field" id="f_name" type="text" name="f_name">
        </div>

        <div class="input-container">
            <i class="fa fa-user icon"></i>
            <input class="input-field" id="l_name" type="text" name="l_name">
        </div>

        <div class="input-container" style="color: maroon">
            <i class="fa fa-envelope icon"></i>
            <input class="input-field" id="email" type="email" name="email">
        </div>
        <div class="input-container" style="color: maroon">
            <i class="fa fa-key icon"></i>
            <input class="input-field" id="oldpass" type="password" placeholder="Настояща парола" name="oldpass">
        </div>

        <div class="input-container" style="color: maroon">
            <i class="fa fa-key icon"></i>
            <input class="input-field" id="pass" type="password" placeholder="Нова парола" name="password">
        </div>
        <div class="input-container" style="color: maroon">
            <i class="fa fa-key icon"></i>
            <input class="input-field" id="rpass" type="password" placeholder="Повторете паролата" name="rpassword">
        </div>
        <button type="submit" onclick="setDetails();" name="editProfile" class="btn">Запазете промените</button>
    </div>
    <!--</form>-->
    <div id="err-list">

    </div>

</section>

</body>
</html>

<script src="../View/assets/js/editProfile.js">

</script>


