<?php

/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 24.4.2018 г.
 * Time: 19:05
 */

namespace Controller;
namespace Model;
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../Model/User.php';
include '../Model/Dao/UsersDao.php';



function __autoload($class)
{
    $class = "..\\" . $class;
    require_once str_replace("\\", "/", $class) . ".php";
}



if(isset($_POST['login'])) {

    try {
        $email = trim(htmlentities($_POST['email']));
        $pass = trim(htmlentities($_POST['password']));
        $user = new User($email, sha1($pass));
        $pdo = new UserDao();
//        setcookie("email", $email);

        if(empty($email) || empty($pass)) {
            header("Location: ../Controller/indexController.php?page=loginFailed");
        }
        else {
            $result = $pdo->checkUserLogin($user);
//            catch (\PDOException $e){
//                //TODO redirect to error page
//                header("Location: ");
//            }
            if($result) {
                /* dolnite 3 reda moje  da gi iztriesh. PS: ako iskash de */
                $id = $pdo->getUserId($user);
                $user->setId($id);
                $details = $pdo->getUserDetailsById($user);
                $_SESSION['userDetails'] = [];
                
                /*moje  da iztriesh i $new  masiva*/
                $new =      ["fName" => $details['first_name'],
                             "lName" => $details['last_name'],
                              "email" => $details['email']
                                                            ];
                $_SESSION['userDetails'] = $result;
                $_SESSION["logged_user"] = true;

                header("Location:  ../Controller/indexController.php?page=main");


            }
            else {
//            echo 'Your email or password is wrong';
                header("Location: ../Controller/indexController.php?page=loginFailed");


//                    echo 'Email is '.$text=$user->getEmail().' and Password is '.$text=$user->getPassword();
            }

        }


    }
    catch(\Exception $e) {
        echo 'The following error has occured: '.$e;
    }
}




