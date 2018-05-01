<?php
namespace Model;
include '../Model/User.php';
include '../Model/Dao/UsersDao.php';


if  (isset($_POST["delete_address"])){

    $user = new User();
    $delete_id=$_POST['hidden_id'];
    $user->setAddressId($delete_id);

    $pdo = new UserDao();
    $pdo->deleteUserAddress($user);

    header("Location: ../Controller/indexController.php?page=myAddresses");

}
