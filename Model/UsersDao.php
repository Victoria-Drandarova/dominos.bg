<?php
spl_autoload_register(function ($class) {
    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once __DIR__ . DIRECTORY_SEPARATOR . $class . ".php";
});
/**
 * Description of UsersDao
 *
 * @author denis
 */
class UsersDao extends dbConnection {

    private $connection;

    public function __construct() {
        $this->connection = $this->getConnection();
    }
    public function getUserData($password, $email) {

        $query = "SELECT u.id, u.first_name, u.last_name 
                FROM users as u WHERE u.password = ?
                AND u.email = ?";
        $stmt = $this->getConnection()->prepare($query);
        $param = [sha1($password), $email];
        $stmt->execute($param);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        }else{
            return false;
        }

    }

    public function createNewUser($userData) {

        try {
            $this->connection->beginTransaction();
            $query = "INSERT INTO users(first_name, last_name, email, password)
            VALUES(?,?,?,?)";
            $stmt = $this->connection->prepare($query);

            $params = [$userData["first_name"], $userData["last_name"],
                $userData["email"],$userData["password"]];
            $stmt->execute($params);

            $this->connection->commit();
            return $stmt->execute($params) ? true : false;

        } catch (PDOException $exp) {
            $this->connection->rollBack();
            $path = dirname(__DIR__);
            $path .= "/log/PDOExeption.txt";
            $errFile = fopen($path, "a");
            if ($errFile) {
                fwrite($errFile, $exp->getMessage() . '. Date -->> ' . date('l jS \of F Y h:i:s A'));
                fclose($errFile);
            } else {
                fclose($errFile);
            }
            header("Location: ../index.php?page=errpage");
        }
    }

    public function redactUserData($userData) {
        try {
            $this->connection->beginTransaction();

            $query = "UPDATE users SET first_name = ?, last_name = ?, password = ?
            WHERE users.id = ?";

            $stmt = $this->connection->prepare($query);

            $params = [$userData["first_name"], $userData["last_name"],
                sha1($userData["password"]), $userData["id"]];
            $stmt->execute($params);

            $this->connection->commit();
            return $stmt->execute($params) ? true : false;

        } catch (PDOException $exp) {
            $this->connection->rollBack();
            $path = dirname(__DIR__);
            $path .= "/log/PDOExeption.txt";
            $errFile = fopen($path, "a");
            if ($errFile) {
                fwrite($errFile, $exp->getMessage() . '. Date -->> ' . date('l jS \of F Y h:i:s A'));
                fclose($errFile);
            } else {
                fclose($errFile);
            }
            header("Location: ../index.php?page=errpage");
        }
    }
}
$usr = new UsersDao();
//$userData = ["first_name" => "Stan", "last_name" => "Stanimirov",
//    "password" => "123456", "id" => 3];
var_dump($usr->redactUserData($userData));