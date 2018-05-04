<?php

namespace Controller;
require_once '../Model/User.php';
require_once '../Model/Dao/UsersDao.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function __autoload($class) {
    $class = "..\\" . $class;
    require_once str_replace("\\", "/", $class) . ".php";
}
$email = $_SESSION["userDetails"]["email"];
$user = new \Model\User($email);
$pdo = new \Model\Dao\UsersDao();
$userDetails = $pdo->getUserDetailsByEmail($user);
$_SESSION["userDetails"] = $userDetails;
echo json_encode($_SESSION["userDetails"]);


