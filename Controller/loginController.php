<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 24.4.2018 г.
 * Time: 19:05
 */
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
//        setcookie("email", $email);
        if(empty($email) || empty($pass)) {
            header("Location: ../Controller/indexController.php?page=loginFailed");
        }
        else {
            $result = $pdo->checkUserLogin($user);
//            catch (\PDOException $e){
//                //TODO redirect to error page
//                header("Location: ");
//            }
            if($result) {
                $id = $pdo->getUserId($user);
//                $user->setId($id);
//                $details = $pdo->getUserDetailsById($user);
                $_SESSION["userId"] = $id;
                $_SESSION["userDetails"] = [];
                $_SESSION["userDetails"]["email"] = $email;
                $_SESSION["logged_user"] = true;
                header("Location:  ../Controller/indexController.php?page=main");
            }
            else {
//            echo 'Your email or password is wrong';
                header("Location: ../Controller/indexController.php?page=loginFailed");
//                    echo 'Email is '.$text=$user->getEmail().' and Password is '.$text=$user->getPassword();
            }
        }
    }
    catch(\Exception $e) {
        echo 'The following error has occured: '.$e;
    }
}
