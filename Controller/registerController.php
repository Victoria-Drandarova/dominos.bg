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


if(isset($_POST['register'])); {


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
            $GLOBALS['error'] = 'Успешно се регистрирахте.';
            echo json_encode($GLOBALS['error']);

//            $GLOBALS['link'] = '../Controller/indexController.php?page=login';
//            echo json_encode($GLOBALS['link']);
//            return $_POST['regResult'] = true;
//            header("Location:  ../Controller/indexController.php?page=login");
        }
        else {
            $GLOBALS['error'] = 'Вече е регистриран потребител с този емайл.';
            echo json_encode($GLOBALS['error']);
//            return $_POST['regResult'] = false;
        }
    }
    else {
        echo json_encode($GLOBALS['error']);
    }
}

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

function checkPasswords($pass, $repeatPass)
{
    if ($pass != $repeatPass) {
        $GLOBALS['error'] = 'Паролите не съвпадат.';
        return false;
    } else {
        return true;
    }
}



