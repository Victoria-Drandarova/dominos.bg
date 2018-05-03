<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 25.4.2018 г.
 * Time: 14:15
 */





namespace Controller;
require_once '../Model/User.php';
require_once '../Model/Dao/UsersDao.php';

session_start();


$error = '';
$GLOBALS['error'];

function __autoload($class) {
    $class = "..\\Model\\" . $class;
    require_once str_replace("\\", "/", $class) .".php";
}


//if(isset($_POST['register'])); {


    $firstName =trim(htmlentities( $_POST['f_name']));
    $lastName = trim(htmlentities($_POST['l_name']));
    $email = trim(htmlentities($_POST['email']));
    $pass = trim(htmlentities($_POST['password']));
    $repeatPass = trim(htmlentities($_POST['rpassword']));

    if(checkEmptyFields($firstName, $lastName, $email, $pass, $repeatPass)
        && checkTextLength($email, $pass, $firstName, $lastName)
        && checkEmail($email) && checkPasswords($pass, $repeatPass))
    {
        $dao = new\Model\Dao\UsersDao();
        $user = new\Model\User($email, sha1($pass), $firstName, $lastName);
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
//    }
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



