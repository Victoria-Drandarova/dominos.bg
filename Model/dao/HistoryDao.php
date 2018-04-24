<?php
namespace Model\dao;
spl_autoload_register(function ($class) {
    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once __DIR__ . DIRECTORY_SEPARATOR . $class . ".php";
});

/**
 * Description of HistoryDao
 *
 * @author denis
 */
class HistoryDao extends dbConnection{
    
    public function historyList($userId) {
        
            $query = "SELECT date FROM orders WHERE user_id = ?";
            $stmt = $this->getConnection()->prepare($query);
            $param = [$userId];
            $stmt->execute($param);
            
            $result = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[] = $row;
            }
            return  $result;
             
    }
    
    public function getHistory($orderId, $userId) {
        
            $query = "SELECT p.name, p.price, fio.quantity, ord.date
                    FROM products as p
                    JOIN foods_in_order as fio
                    ON fio.product_id = p.id
                    JOIN orders as ord      
                    ON ord.user_order_id = fio.order_id 
                    AND ord.user_order_id = ?
                    JOIN users as u
                    ON u.id = ?";
            $stmt = $this->getConnection()->prepare($query);
            $param = [$orderId, $userId];
            $stmt->execute($param);
            
            $result = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[] = $row;
            }
            return  $result; 
       
    }
    
}
$h = new HistoryDao();
var_dump($h->historyList(1));