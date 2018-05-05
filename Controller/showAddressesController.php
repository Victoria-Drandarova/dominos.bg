<?php

namespace Controller;
require_once  '../Model/User.php';
require_once '../Model/Dao/UsersDao.php';


session_start();

function __autoload($class) {
    $class = "..\\" . $class;
    require_once str_replace("\\", "/", $class) . ".php";
}
$userId = $_SESSION['userId'];
$pdo = new \Model\Dao\UsersDao();
$user = new\Model\ User(null, null, null, null, $userId);
$addressIds = $pdo->getAddressIdFromLinkTable($user);

foreach($addressIds as $id) {
    $user->setAddressId($id);
    $result = $pdo->getAddressDetails($user);
    $addressDetails[] = $result;
}
echo json_encode($addressDetails);


