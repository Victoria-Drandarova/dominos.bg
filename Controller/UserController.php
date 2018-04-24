<?php
session_start();

spl_autoload_register(function ($class) {
    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once "../Model" . DIRECTORY_SEPARATOR . $c . ".php";
});
use Model\dao\UsersDao;

class UserController {
    
    public function register() {
        
    }
}

if (isset($_POST["register"])) {

    $reg = new UserController();

    $reg->register();
} elseif(isset($_POST["login"])) {
    $log = new UserController();

    $log->login();
}else{
    echo "Some err to handle";
}
