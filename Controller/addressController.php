<?php

namespace Controller;
namespace Model;
include '../Model/Dao/UsersDao.php';
include '../Model/User.php';

session_start();

$GLOBALS['addressError'];
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
        $pdo->insertAddress($userAddress);
        $GLOBALS['addressSuccess'] = 'Вие успешно добавихте нов адрес.';
        echo json_encode($GLOBALS['addressSuccess']);

    } else {
        echo json_encode($GLOBALS['addressError']);
    }



function checkEmptyFields($city, $hood, $blok, $entrance) {
    if (empty($city) || empty($hood) || empty($blok) || empty($entrance)) {
        $GLOBALS['addressError'] = 'Моля попълнете всички полета!';
        return false;
    } else {
        return true;
    }
}

function checkTextLength($city, $hood, $blok, $entrance) {
    if(strlen($city) > 20 || strlen($hood) > 30 || strlen($blok) > 3 || strlen($entrance) > 2
        || strlen($city) < 3 || strlen($hood) < 5) {
        $GLOBALS['addressError'] = 'Въведените данни са твърде дълги или твърде къси или сте въвели грешен номер на блок!';
        return false;
    }
    else {
        return true;
    }

}






