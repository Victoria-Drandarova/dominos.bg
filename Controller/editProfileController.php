<?php


namespace Controller;
require_once '../Model/User.php';
require_once '../Model/Dao/UsersDao.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$error="";
$GLOBALS['error'];
function __autoload($class) {
    $class = "..\\" . $class;
    require_once str_replace("\\", "/", $class) . ".php";
}


$firstName = trim(htmlentities($_POST['f_name']));
$lastName = trim(htmlentities($_POST['l_name']));
$email = trim(htmlentities($_POST['email']));
$oldPass = trim(htmlentities($_POST['oldpass']));
$password =trim(htmlentities( $_POST['password']));
$rPassword = trim(htmlentities($_POST['rpassword']));


if(checkEmptyFields($firstName, $lastName, $email, $oldPass,  $password, $rPassword)
    && checkTextLength($email, $password, $firstName, $lastName)
    && checkEmail($email) && checkOldPass($oldPass) && checkPasswords($password, $rPassword)) {

    $pdo = new\Model\Dao\UsersDao();
    $userUpdate = new\Model\User($email, sha1($password), $firstName, $lastName);

    $id = $_SESSION["userId"];
    $userUpdate->setId($id);
    $pdo->editUserProfile($userUpdate);
    $_SESSION["userDetails"]["email"] = $email;
    $GLOBALS['success'] = 'Успешно редактирахте профила си.';
    echo json_encode($GLOBALS['success']);


}
else {
    echo json_encode($GLOBALS['error']);
}




function checkEmptyFields($firstName, $lastName, $email, $oldPass,  $password, $rPassword) {
    if (strlen($firstName) == 0 || strlen($lastName) == 0 || strlen($email) == 0
        || strlen($oldPass) == 0 || strlen($password) == 0 || strlen($rPassword) == 0) {
        $GLOBALS['error'] = 'Моля попълнете всички полета!';
        return false;
    } else {
        return true;
    }
}

function checkTextLength($email, $password, $firstName, $lastName) {
    if(strlen($email) > 50 || strlen($password) > 50 || strlen($firstName) > 50 || strlen($lastName) > 50
        || strlen($password) < 6 || strlen($firstName) < 2 || strlen($lastName) < 5) {
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

function checkPasswords($password, $rPassword) {
    if ($password != $rPassword) {
        $GLOBALS['error'] = 'Паролите не съвпадат.';
        return false;
    } else {
        return true;
    }
}

function checkOldPass($oldPass) {
    $user = new\Model\User($_SESSION["userDetails"]["email"]);
    $pdo = new\Model\Dao\UsersDao();
    $result = $pdo->getPassword($user);

    if (sha1($oldPass) != $result) {
        $GLOBALS['error'] = 'Въведената стара парола не е вярна!';
        return false;
    } else {
        return true;
    }
}








