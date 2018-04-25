<?php
//session_start();
//error_reporting(E_ALL ^ E_NOTICE);
//spl_autoload_register(function ($class) {
//    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
//    require_once "../Model" . DIRECTORY_SEPARATOR . $class . ".php";
//});
//
//class UserController extends UsersDao {
//
//    private $firstName;
//    private $lastName;
//    private $email;
//    private $password;
//    private $confirmPassword;
//    private $registerErr = [];
//    private $userData = [];
//
//    public function __construct() {
//        $this->firstName = $_POST["f_name"];
//        $this->lastName = $_POST["l_name"];
//        $this->email = $_POST["email"];
//        $this->password = $_POST["password"];
//        $this->confirmPassword = $_POST["rpassword"];
//    }
//
//    public function login() {
//        $this->setEmail($this->email);
//        $this->setPassword($this->password);
//
//        if ($this->registerErr) {
//            $_SESSION["regiserErr"] = $this->registerErr;
//        }else{
//            $loginResult = $this->getUserData($this->getPassword(), $this->getEmail());
//
//            if ($loginResult) {
//
////               var_dump($loginResult);
//                $_SESSION["user_data"] = $loginResult;
//                $_SESSION["logged_user"] = true;
//                header("Location:  ../Controller/indexController.php?page=main");
//            }else{
////               var_dump($loginResult);
//                $this->registerErr[] = "Invalid Email or Password";
//                $_SESSION["regiserErr"] = $this->registerErr;
//                $_SESSION["logged_user"] = true;
//                header("Location:  ../Controller/indexController.php?page=login");
//            }
//        }
//    }
//
//    public function register() {
//        $this->setFirstName($this->firstName);
//        $this->setLastName($this->lastName);
//        $this->setEmail($this->email);
//        $this->setPassword($this->password);
//        $this->setConfirmPassword($this->confirmPassword);
//
//        $this->registerErr = [];
//
//        if ($this->password != $this->confirmPassword) {
//            $this->registerErr[] = "Password Dismatch!";
//        } else {
//            if ($this->registerErr) {
//                $_SESSION["regiserErr"] = $this->registerErr;
//            } else {
//                $this->userData["first_name"] = $this->getFirstName();
//                $this->userData["last_name"] = $this->getLastName();
//                $this->userData["email"] = $this->getEmail();
//                $this->userData["password"] = sha1($this->getPassword());
//
//                $result = $this->createNewUser($this->userData);
//
//                if ($result) {
////                        var_dump($result);
//                    header("Location: ../Controller/indexController.php?page=login");
//                } else {
////                        var_dump($result);
//                    header("Location: ../Controller/indexController.php?page=register");
//                }
//            }
//        }
//
//    }
//
//    function getFirstName() {
//        return $this->firstName;
//    }
//
//    function getLastName() {
//        return $this->lastName;
//    }
//
//    function getEmail() {
//        return $this->email;
//    }
//
//    function getPassword() {
//        return $this->password;
//    }
//
//    function getConfirmPassword() {
//        return $this->confirmPassword;
//    }
//
//    function setFirstName($firstName) {
//        if (mb_strlen($firstName) < 2 || empty($firstName)) {
//            $this->registerErr[] = "First name  min length is 2 chars";
//        } else {
//            $this->firstName = trim(htmlentities($firstName));
//        }
//    }
//
//    function setLastName($lastName) {
//        if (mb_strlen($lastName) < 2 || empty($lastName)) {
//            $this->registerErr[] = "Last name min length is 2 chars";
//        } else {
//            $this->lastName = trim(htmlentities($lastName));
//        }
//    }
//
//    function setEmail($email) {
//        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//            $this->registerErr[] = "Invalid  email addres!";
//        } else {
//            $this->email = trim(htmlentities($email));
//        }
//    }
//
//    function setPassword($password) {
//
//        if (mb_strlen($password) < 5 || empty($password)) {
//            $this->registerErr[] = "Password min length is 5 chars";
//        } else {
//            $this->password = trim(htmlentities($password));
//        }
//    }
//
//    function setConfirmPassword($confirmPassword) {
//
//        if (mb_strlen($confirmPassword) < 5 || empty($confirmPassword)) {
//            $this->registerErr[] = "Password min length is 5 chars";
//        } else {
//            $this->confirmPassword = trim(htmlentities($confirmPassword));
//        }
//    }
//
//}
//
//if (isset($_POST["register"])) {
//
//    $reg = new UserController();
//
//    $reg->register();
//} elseif(isset($_POST["login"])) {
//    $log = new UserController();
//
//    $log->login();
//}else{
//    echo "Some err to handle";
//}
