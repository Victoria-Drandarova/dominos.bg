<?php
spl_autoload_register(function ($class) {
    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once __DIR__ . DIRECTORY_SEPARATOR . $class . ".php";
});

/**
 * Description of PurchaseDao
 *
 * @author denis
 */
class PurchaseDao extends dbConnection{
    
    public function insertPurchase($userId) {
        try {
            $query = "SELECT date FROM orders WHERE user_id = ?";
            $stmt = $this->getConnection()->prepbare($query);
            $param = [$userId];
            $stmt->execute($param);
            
            $result = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[] = $row;
            }
            return  $result;
               
        } catch (PDOException $exp) {
            $path = dirname(__DIR__);
            $path .= "/log/PDOExeption.txt";
            $errFile = fopen($path, "a+");
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
