<?php
//namespace Controller;
//namespace Model;
//include '../Controller/loginController.php';
//include '../Model/User.php';
//include '../Model/Dao/UsersDao.php';
namespace Model;
include '../Model/User.php';
include '../Model/Dao/UsersDao.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function __autoload($class) {
    $class = "..\\" . $class;
    require_once str_replace("\\", "/", $class) . ".php";
}
$email = $_SESSION["userDetails"]["email"];
$user = new User($email);
$pdo = new UserDao();
$userDetails = $pdo->getUserDetailsByEmail($user);
$_SESSION["userDetails"] = $userDetails;
echo json_encode($_SESSION["userDetails"]);


