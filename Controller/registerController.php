<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 25.4.2018 г.
 * Time: 14:15
 */

//namespace Controller;



namespace Controller;
namespace Model;
include '../Model/User.php';
include '../Model/Dao/UsersDao.php';

session_start();

//Use Dao\UserDao;
//Use Model\User;

$error = '';
$GLOBALS['error'];

function __autoload($class) {
    $class = "..\\Model\\" . $class;
    require_once str_replace("\\", "/", $class) .".php";
}

//spl_autoload_register(
//    function($className)
//    {
//        $className = str_replace("_", "\\", $className);
//        $className = ltrim($className, '\\');
//        $fileName = '';
//        $namespace = '';
//        if ($lastNsPos = strripos($className, '\\'))
//        {
//            $namespace = substr($className, 0, $lastNsPos);
//            $className = substr($className, $lastNsPos + 1);
//            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
//        }
//        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
//
//        require $fileName;
//    }
//);

if(isset($_POST['register'])); {

    $firstName = trim(htmlentities($_POST['f_name']));
    $lastName = trim(htmlentities($_POST['l_name']));
    $email = trim(htmlentities($_POST['email']));
    $pass = trim(htmlentities($_POST['password']));
    $repeatPass = trim(htmlentities($_POST['rpassword']));

    function checkEmptyFields($firstName, $lastName, $email, $pass, $repeatPass){
        if(empty($firstName) || empty($lastName) || empty($email) || empty($pass) || empty($repeatPass)) {
            $GLOBALS['error'] = 'Моля попълнете всички полета!';
            return false;
        }
        else {
            return true;
        }
    }

    function checkTextLength($email, $pass, $firstName, $lastName) {
        if(strlen($email) > 50 || strlen($pass) > 50 || strlen($firstName) > 50 || strlen($lastName) > 50
            || strlen($pass) < 6 || strlen($firstName) < 2 || strlen($lastName) < 5) {
            $GLOBALS['error'] =  'Въведените данни са твърде дълги или твърде къси!';
            return false;
        }
        else {
            return true;
        }

    }

    function checkEmail($email) {
        if(strpos($email, "@") === false || strpos($email, ".") === false) {
            $GLOBALS['error'] =  'Невалиден емайл адрес.';
            return false;
        }
        else {
            return true;
        }
    }

    function checkPasswords($pass, $repeatPass) {
        if($pass != $repeatPass) {
            $GLOBALS['error'] =  'Паролите не съвпадат.';
            return false;
        }
        else {
            return true;
        }
    }

    if(checkEmptyFields($firstName, $lastName, $email, $pass, $repeatPass)
        && checkTextLength($email, $pass, $firstName, $lastName)
        && checkEmail($email) && checkPasswords($pass, $repeatPass))
    {
        $dao = new UserDao();
        $user = new User($email, sha1($pass), $firstName, $lastName);
        $result = $dao->checkIfUserEmailExists($user);
        if($result) {
            $dao->registerUser($user);
            header("Location:  ../Controller/indexController.php?page=login");
        }
        else {
            echo 'Този емайл вече същесвува';
        }

    }
    else {
        echo 'Имате следната грешка '.$error;


    }

}