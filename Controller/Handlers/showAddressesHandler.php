<?php

namespace Controller;

require_once '../showAddressesController.php';

//function __autoload($class) {
//    $class = "..\\" . $class;
//    require_once str_replace("\\", "/", $class) . ".php";
//}

$showAddressesObj = new showAddresses();
$result = $showAddressesObj->getAddressDetails();
echo json_encode($result);
