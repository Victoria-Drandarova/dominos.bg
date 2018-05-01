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
 * User: Viktoria Drandarova
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
                return true;
            } else {
                return false;
            }
        }
        catch(\Exception $e) {

        }
    }

    public function registerUser(User $user) {
            $query = $this->pdo->prepare("INSERT INTO users (first_name, last_name, email, password)
                                                                      VALUES (?, ?, ?, ?)");
            $query->execute(array($user->getFirstName(),
                    $user->getLastName(),
                    $user->getEmail(),
                    $user->getPassword())
            );

    }

    public function editUserProfile(User $user) {
//            $id = $user->getId();
//            $query = $this->pdo->prepare("UPDATE users SET (first_name, last_name, email, password)
//                                                                      VALUES (?, ?, ?, ?) WHERE id=?");
            $query = $this->pdo->prepare("UPDATE users SET first_name=?, last_name=?, email=?, password=? WHERE id=?");
            $query->execute(array($user->getFirstName(),
                                  $user->getLastName(),
                                  $user->getEmail(),
                                  $user->getPassword(),
                                  $user->getId()));


    }

    public function getUserDetailsById(User $user) {

            $query = $this->pdo->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
            $query->execute(array($user->getId()));
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            return $result;


    }

    public function getUserDetailsByEmail(User $user) {

            $query = $this->pdo->prepare("SELECT first_name, last_name, email FROM users WHERE email = ?");
            $query->execute(array($user->getEmail()));
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            return $result;

    }

    public function getUserId(User $user) {

            $query = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $query->execute(array($user->getEmail()));
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            $id = $result['id'];
            return $id;
//

    }

    public function getPassword(User $user) {

        $query =$this->pdo->prepare("SELECT password FROM users WHERE email = ?");
        $query->execute(array($user->getEmail()));
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        if($result) {
            return $result['password'];
        }
        else {
            return false;
        }
    }

    public function insertAddress(User $user) {

        $query =$this->pdo->prepare("INSERT INTO adress (town, hood, bl, entrance) VALUES (?, ?, ?, ?)");
        $query->execute(array($user->getCity(),
                              $user->getNeighborhood(),
                              $user->getBlok(),
                              $user->getEntrance()));
        $lastId = $this->pdo->lastInsertId();
        return $lastId;

//        $result = $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function insertIntoLinkTable(User $user) {

        $query =$this->pdo->prepare("INSERT INTO user_addresses (user_id, address_id) VALUES (?, ?)");
        $query->execute(array($user->getId(), $user->getAddressId()));

    }


    public function getAddressIdFromLinkTable(User $user) {

        $query =$this->pdo->prepare("SELECT address_id FROM user_addresses WHERE user_id = ?");
        $query->execute(array($user->getId()));
        while($resultRow = $query->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $resultRow['address_id'];
        }
        return $result;

    }

    public function getAddressDetails(User $user) {

        $query =$this->pdo->prepare("SELECT * FROM adress WHERE id = ?");
        $query->execute(array($user->getAddressId()));
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function deleteUserAddress(User $user) {
        try {

            $id = $user->getAddressId();

            $this->pdo->beginTransaction();
            $this->pdo->query("DELETE FROM adress WHERE id = '$id'");
//            $this->pdo->query("DELETE FROM user_addresses WHERE address_id = '$id'");

//            $query -> execute(array($user->getAddressId()));
//            $query2 -> execute(array($user->getAddressId()));
            $this->pdo->commit();

        }
        catch(\PDOException $e) {
            echo $e;
            $this->pdo->rollBack();

        }
    }

}







