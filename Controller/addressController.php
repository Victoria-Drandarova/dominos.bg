<?php

namespace Controller;
namespace Model;
include '../Model/Dao/UsersDao.php';
include '../Model/User.php';

session_start();

$error = '';
$GLOBALS['error'];
function __autoload($class) {
    $class = "..\\" . $class;
    require_once str_replace("\\", "/", $class) . ".php";
}

//add new address

//if(isset($_POST['addAddress']));


    $city = $_POST['city'];
    $hood = $_POST['hood'];
    $blok = $_POST['blok'];
    $entrance = $_POST['entrance'];

    if (checkEmptyFields($city, $hood, $blok, $entrance) && checkTextLength($city, $hood, $blok, $entrance)) {

        $userAddress = new User(null, null, null, null,null, $city, $hood, $blok, $entrance);
        $pdo = new UserDao();
        $lastId = $pdo->insertAddress($userAddress);
        $userId = $_SESSION["userId"];
        $userAddress->setId($userId);
        $userAddress->setAddressId($lastId);
        $pdo->insertIntoLinkTable($userAddress);

        $addressSuccess = 'Вие успешно добавихте нов адрес.';
        echo json_encode($addressSuccess);

    } else {
        echo json_encode($GLOBALS['error']);
    }



function checkEmptyFields($city, $hood, $blok, $entrance) {
    if (empty($city) || empty($hood) || empty($blok) || empty($entrance)) {
        $GLOBALS['error'] = 'Моля попълнете всички полета!';
        return false;
    } else {
        return true;
    }
}

function checkTextLength($city, $hood, $blok, $entrance) {
    if(strlen($city) > 20 || strlen($hood) > 30 || strlen($blok) > 3 || strlen($entrance) > 2
        || strlen($city) < 3 || strlen($hood) < 5) {
        $GLOBALS['error'] = 'Въведените данни са твърде дълги или твърде къси или сте въвели грешен номер на блок!';
        return false;
    }
    else {
        return true;
    }

}





