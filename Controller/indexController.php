 <?php
session_start();
//var_dump($_SESSION["logged_user"]);
require_once "../View/header.html";

if (isset($_GET["page"]) && $_GET["page"] == "logout") { //ако е натиснат някой линк и линкът log out, унищожи сесията
    
    session_destroy();
    header("location:../Controller/indexController.php?page=main"); //пренасочи потребителя към log in
    die();   // прекрати изпълнението на какъвто и да е следващ скрипт, защото няма смисъл да продължава след като се е логаутнал
}

if (isset($error) && $error) { // $error e от  if (isset($_POST["register"]) този иф, ако е сетната и е true да изпишем ГРЕШКА
    echo "<h1>$error</h1>";    //и да си остане пак на регистър, докато се регистрира правилно
    require_once "../View/register.html";
}

if(isset($_GET["page"])){
    $page_name = $_GET["page"];
    if(isset($_SESSION["logged_user"])){
        require_once "../View/nav_logged.html";
        
        require_once "../View/$page_name.html";
    }else{
        require_once "../View/nav_not_logged.html";
        if($page_name=="pizzaList"){
            require_once "../View/$page_name.html";
        }elseif($page_name=="mapRestaurants"){
            require_once "../View/$page_name.html";
        } elseif($page_name=="contact"){
            require_once "../View/$page_name.html";
        } elseif($page_name=="loginFailed"){
            require_once "../View/$page_name.html";
        }elseif($page_name=="register"){
            require_once "../View/$page_name.html";
        }elseif($page_name=="login"){
            require_once "../View/$page_name.html";
        }elseif($page_name=="main"){
            require_once "../View/$page_name.html";
        }elseif($page_name=="contactFailed"){
            require_once "../View/$page_name.html";
        }elseif($page_name=="jobsFailed"){
            require_once "../View/$page_name.html";
        }elseif($page_name=="termsOfUse") {
            require_once "../View/$page_name.html";

        }else{
            require_once "../View/login.html";
        }

    }
}else{
    require_once "../View/nav_not_logged.html";
    require_once "../View/main.html";
}
require_once "../View/footer.html";

