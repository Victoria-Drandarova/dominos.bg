<?php

namespace Controller;
include  '../Model/User.php';
include '../Model/Dao/UsersDao.php';
use \Model\User;
use \Model\UserDao;

session_start();

function __autoload($class) {
    $class = "..\\" . $class;
    require_once str_replace("\\", "/", $class) . ".php";
}

//class showAddresses {
//
//    private $addressDetails = array();
//    private $addressIds = array();
//
//    public function __construct() {

        $userId = $_SESSION['userId'];
        $pdo = new UserDao();
        $user = new User(null, null, null, null, $userId);
        $addressIds = $pdo->getAddressIdFromLinkTable($user);
        //echo json_encode($addressIds);

        foreach($addressIds as $id) {
            $user->setAddressId($id);
            $result = $pdo->getAddressDetails($user);
            $addressDetails[] = $result;
        }
        echo json_encode($addressDetails);


//    }
//
//    public function getAddressDetails() {
//        return $this->addressDetails;
//    }
//
//}


//$pdo = null;
//
//
//const DB_NAME = "dominos";
//const DB_IP = "127.0.0.1";
//const DB_PORT = "3306";
//const DB_USER = "root";
//const DB_PASS = "";
//
//try {
//    $pdo = new PDO("mysql:host=" . DB_IP . ":" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
//    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
//
//}
//catch (PDOException $e){
//    echo "Problem with db query  - " . $e->getMessage();
//}
//
//$statement = $pdo->prepare("SELECT *  FROM adress WHERE town='Sofia'");
//$statement->execute();
//$fetch = array();
//while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
//$data[] = $row;
//
//}
//echo json_encode($data);
//if  (isset($_POST["delete_address"])){
//    $delete_id=$_POST["id"];
//    echo $delete_id;
//}
//echo $delete_id;