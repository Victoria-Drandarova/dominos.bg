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


// if(isset($_POST['register']));
if(isset($_POST['register'])); {

    $GLOBALS = [];
    $firstName = $_POST['f_name'];
    $lastName = $_POST['l_name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $repeatPass = $_POST['rpassword'];

    if(checkEmptyFields($firstName, $lastName, $email, $pass, $repeatPass)
        && checkTextLength($email, $pass, $firstName, $lastName)
        && checkEmail($email) && checkPasswords($pass, $repeatPass))
    {
        $dao = new UserDao();
        $user = new User($email, sha1($pass), $firstName, $lastName);
        $result = $dao->checkIfUserEmailExists($user);
        if(!$result) {
            $dao->registerUser($user);
            return $_POST['regResult'] = true;
//            header("Location:  ../Controller/indexController.php?page=login");
        }
        else {
            echo json_encode($GLOBALS);
            return $_POST['regResult'] = false;
        }
    }
    else {
        echo json_encode($GLOBALS);
    }
}

function checkEmptyFields($firstName, $lastName, $email, $pass, $repeatPass){
    if(empty($firstName) || empty($lastName) || empty($email) || empty($pass) || empty($repeatPass)) {
        $GLOBALS[] = 'Моля попълнете всички полета!';
        return false;
    }
    else {
        return true;
    }
}

function checkTextLength($email, $pass, $firstName, $lastName) {
    if(strlen($email) > 50 || strlen($pass) > 50 || strlen($firstName) > 50 || strlen($lastName) > 50
        || strlen($pass) < 6 || strlen($firstName) < 2 || strlen($lastName) < 5) {
        $GLOBALS[] =  'Въведените данни са твърде дълги или твърде къси!';
        return false;
    }
    else {
        return true;
    }

}

function checkEmail($email) {
    if(strpos($email, "@") === false || strpos($email, ".") === false) {
        $GLOBALS[] =  'Невалиден емайл адрес.';
        return false;
    }
    else {
        return true;
    }
}

function checkPasswords($pass, $repeatPass)
{
    if ($pass != $repeatPass) {
        $GLOBALS[] = 'Паролите не съвпадат.';
        return false;
    } else {
        return true;
    }
}
