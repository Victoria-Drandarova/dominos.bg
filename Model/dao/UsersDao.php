<?php
//<!--kod na Denis-->
//<!---->
//
//
//spl_autoload_register(function ($class) {
//    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
//    require_once __DIR__ . DIRECTORY_SEPARATOR . $class . ".php";
//});
//
///**
// * Description of UsersDao
// *
// * @author denis
// */
//class UsersDao extends dbConnection {
//
//    public function getUserData($password, $email) {
//
//        $query = "SELECT u.id, u.first_name, u.last_name
//                FROM users as u WHERE u.password = ?
//                AND u.email = ?";
//        $stmt = $this->getConnection()->prepare($query);
//        $param = [sha1($password), $email];
//        $stmt->execute($param);
//        $result = $stmt->fetch(PDO::FETCH_ASSOC);
//        if ($result) {
//            return $result;
//        }else{
//            return false;
//        }
//
//    }
//
//    public function createNewUser($userData) {
//
//        try {
////            $this->getConnection()->beginTransaction();
//            $query = "INSERT INTO users(first_name, last_name, email, password)
//            VALUES(?,?,?,?)";
//            $stmt = $this->getConnection()->prepare($query);
//
//            $params = [$userData["first_name"], $userData["last_name"],
//                $userData["email"],$userData["password"]];
//            $result = $stmt->execute($params);
//
////            $this->getConnection()->commit();
//            return $result ? $result : false;
//
//        } catch (PDOException $exp) {
//            echo  $exp->getLine();
//            echo "<br>";
//            echo  $exp->getFile();
//            echo "<br>";
//            echo  $exp->getMessage();
////            $this->getConnection()->rollBack();
////            $this->connection->rollBack();
////            $path = dirname(__DIR__);
////            $path .= "/log/PDOExeption.txt";
////            $errFile = fopen($path, "a");
////            if ($errFile) {
////                fwrite($errFile, $exp->getMessage() . '. Date -->> ' . date('l jS \of F Y h:i:s A'));
////                fclose($errFile);
////            } else {
////                fclose($errFile);
////            }
////            header("Location: ../index.php?page=errpage");
//        }
//    }
//
//    public function redactUserData($userData) {
//
//        try {
//            $this->connection->beginTransaction();
//
//            $query = "UPDATE users SET first_name = ?, last_name = ?, password = ?
//            WHERE users.id = ?";
//
//            $stmt = $this->connection->prepare($query);
//
//            $params = [$userData["first_name"], $userData["last_name"],
//                sha1($userData["password"]), $userData["id"]];
//            $stmt->execute($params);
//
//            $this->connection->commit();
//            return $stmt->execute($params) ? true : false;
//
//        } catch (PDOException $exp) {
//            $this->connection->rollBack();
//            $path = dirname(__DIR__);
//            $path .= "/log/PDOExeption.txt";
//            $errFile = fopen($path, "a");
//            if ($errFile) {
//                fwrite($errFile, $exp->getMessage() . '. Date -->> ' . date('l jS \of F Y h:i:s A'));
//                fclose($errFile);
//            } else {
//                fclose($errFile);
//            }
//            header("Location: ../index.php?page=errpage");
//        }
//    }
//}
//
//$usr = new UsersDao();
//$userData = ["first_name" => "Stan", "last_name" => "Stanimirov",
//    "password" => "123456", "id" => 3];
//var_dump($usr->redactUserData($userData));



/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 24.4.2018 г.
 * Time: 16:27
 */
namespace Model;



use Model\User;


class UserDao {

    const DB_IP = "127.0.0.1";
    const DB_PORT = "3306";
    const DB_NAME = "dominos";
    const USER = "root";
    const PASS = "";
    /* @var $pdo \PDO */
    private $pdo;

    public function __construct()    {

        try {
            $this->pdo = new \PDO("mysql:host=" . self::DB_IP . ":" . self::DB_PORT . ";dbname=" . self::DB_NAME, self::USER, self::PASS);
            $this->pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

        }
        catch (\PDOException $e){
            echo "Problem with db query  - " . $e->getMessage();
        }
    }

    public function checkUserLogin(User $user) {
        $query = $this->pdo->prepare("SELECT COUNT(*) as rows FROM users WHERE email = ? AND password = ?");
        $query->execute(array($user->getEmail(), $user->getPassword()));
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        if ($result['rows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkIfUserEmailExists(User $user) {
        try {
            $query = $this->pdo->prepare("SELECT COUNT(*) as rows FROM users WHERE email = ?");
            $query->execute(array($user->getEmail()));
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            if ($result['rows'] > 0) {
                $GLOBALS[] = 'Вече е регистриран потребител с този емайл.';
                return true;
            } else {
                return false;
            }
        }
        catch(\Exception $e) {

        }
        }

        public function registerUser(User $user) {
        try {
            $query = $this->pdo->prepare("INSERT INTO users (first_name, last_name, email, password)
                                                                      VALUES (?, ?, ?, ?)");
            $query->execute(array($user->getFirstName(),
                                  $user->getLastName(),
                                  $user->getEmail(),
                                  $user->getPassword())
            );
        }
        catch(\Exception $e) {

            }
        }

        public function editUserProfile(User $user) {
        try {
            $query = $this->pdo->prepare("UPDATE users SET (first_name, last_name, email, password) 
                                                                      VALUES (?, ?, ?, ?)");
            $query->execute(array($user->getFirstName(),
                    $user->getLastName(),
                    $user->getEmail(),
                    $user->getPassword())
            );
        }
        catch(\Exception $e) {
        }

        }

        public function getUserDetailsById(User $user) {
        try {
            $query = $this->pdo->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
            $query->execute(array($user->getId()));
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            return $result;
        }
        catch(\Exception $e) {

        }

        }

        public function getUserDetailsByEmail(User $user) {
            try {
                $query = $this->pdo->prepare("SELECT first_name, last_name, email FROM users WHERE email = ?");
                $query->execute(array($user->getEmail()));
                $result = $query->fetch(\PDO::FETCH_ASSOC);
                return $result;
            }
            catch(\Exception $e) {

            }

        }

        public function getUserId(User $user) {
        try {
            $query = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $query->execute(array($user->getEmail()));
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            return $result;
        }
        catch(\Exception $e) {

        }

        }

    }







