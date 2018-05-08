<?php

namespace Controller;
require_once  '../Model/User.php';
require_once '../Model/Dao/UsersDao.php';


session_start();

function __autoload($class) {
    $class = "..\\" . $class;
    require_once str_replace("\\", "/", $class) . ".php";
}

//Извикване на настоящите адреси по ID на потребителя
$userId = $_SESSION['userId'];
$pdo = new \Model\Dao\UsersDao();
$user = new\Model\ User(null, null, null, null, $userId);
$addressIds = $pdo->getAddressIdFromLinkTable($user);

//Попълване на масив с всичките адреси на потребителя
foreach($addressIds as $id) {
    $user->setAddressId($id);
    $result = $pdo->getAddressDetails($user);
    $addressDetails[] = $result;
}
echo json_encode($addressDetails);


