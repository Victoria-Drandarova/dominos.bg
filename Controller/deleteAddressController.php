<?php
//namespace Model;
include '../Model/User.php';
include '../Model/Dao/UsersDao.php';


if  (isset($_POST["delete_address"])){

    $user = new\Model\User();
    $delete_id=$_POST['hidden_id'];
    $user->setAddressId($delete_id);

    $pdo = new\Model\Dao\UsersDao();
    $pdo->deleteUserAddress($user);

    header("Location: ../Controller/indexController.php?page=myAddresses");

}
