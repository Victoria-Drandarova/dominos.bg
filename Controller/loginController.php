<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 24.4.2018 г.
 * Time: 19:05
 */

namespace Controller;
namespace Model;
include '../Model/User.php';
include '../Model/Dao/UsersDao.php';

session_start();

//use Model\UserDao;
//use Model\User;



function __autoload($class)
{
    $class = "..\\" . $class;
    require_once str_replace("\\", "/", $class) . ".php";
}



if(isset($_POST['login'])) {

    try {
        $email = trim(htmlentities($_POST['email']));
        $pass = trim(htmlentities($_POST['password']));

        $user = new User($email, sha1($pass));
        $pdo = new UserDao();

        if(empty($email) || empty($pass)) {
            header("Location: ../Controller/indexController.php?page=loginFailed");
        }
        else {
            $result = $pdo->checkUserLogin($user);

            if($result) {

                $id = $pdo->getUserId($user);
                $user->setId($id);
                $details = $pdo->getUserDetailsById($user);
                $_SESSION['user'] = $details;
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




