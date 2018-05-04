<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../Model/User.php';
include '../Model/Dao/UsersDao.php';

if(isset($_POST['login'])) {
    try {
        $email = trim(htmlentities($_POST['email']));
        $pass = trim(htmlentities($_POST['password']));
        $user = new \Model\User($email, sha1($pass));
        $pdo = new \Model\Dao\UsersDao();

        if(empty($email) || empty($pass)) {
            header("Location: ../Controller/indexController.php?page=loginFailed");
        }
        else {
            try {
                $result = $pdo->checkUserLogin($user);
            }
            catch (\PDOException $e){

                header("Location: ../Controller/indexController.php?page=loginFailed ");

            }
            if($result) {
                $id = $pdo->getUserId($user);
                $_SESSION["userId"] = $id;
                $_SESSION["userDetails"]["email"] = $email;
                $_SESSION["cart"] = [];
                $_SESSION["logged_user"] = true;
                header("Location:  ../Controller/indexController.php?page=main");
            }
            else {

                header("Location: ../Controller/indexController.php?page=loginFailed");

            }
        }
    }
    catch(\Exception $e) {
        echo 'The following error has occured: '.$e;
    }
}
