<?php
namespace Controller;

//include '../Model/User.php';
//include '../Model/Dao/UsersDao.php';
include '../Controller/loginController.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$details = $_SESSION['userDetails'];

function __autoload($class) {
    $class = "..\\" . $class;
    require_once str_replace("\\", "/", $class) . ".php";
}
echo json_encode($_SESSION['userDetails']);
//$user = new User();
//$userEmail = $userDetailsArray['email'];
//$userFirstName = $userDetailsArray['first_name'];
//$userLastName = $userDetailsArray['last_name'];

//$userDetails = [
//                      'email' => $userEmail,
//                      'fName' => $userFirstName,
//                      'lName' => $userLastName,
//                                                ];

//$test = ["test"];

//$email = $_COOKIE["email"];
//$user = new User($email);
//$pdo = new UserDao();
//$details = array();
//$details = $pdo->getUserDetailsByEmail($user);

//$userFirstName = $details['fName'];
//$userLastName = $details['lName'];
//$userEmail = $details['email'];
//
//if(strlen($userEmail) == 0 || strlen($userFirstName) == 0 || strlen($userLastName) == 0) {
//    $errorMessage = "Имаше проблем със изписването на вашите данни!";
//    echo json_encode($errorMessage);
//
//}
//else {
//    echo json_encode($details);
//}



