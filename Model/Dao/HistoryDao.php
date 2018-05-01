<?php
namespace Model\Dao;

spl_autoload_register(function ($class) {
    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once __DIR__ . DIRECTORY_SEPARATOR . $c . ".php";
});

use Model\Dao\DbConnection;

/**
 * Description of HistoryDao
 *
 * @author denis
 */
class HistoryDao extends DbConnection{
    
    public function historyList($userId) {
        
            $query = "SELECT user_order_id, date FROM orders WHERE user_id = ?";
            $stmt = $this->getConnection()->prepare($query);
            $param = [$userId];
            $stmt->execute($param);
            
            $listOfHistory = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                $listOfHistory[] = $row;
            }
            return $listOfHistory ? $listOfHistory : false;
             
    }
    
    public function getHistory($orderId, $userId) {
        
            $query = "SELECT p.name , p.price,p.id,
                    fio.quantity, i.name as in_name, i.price as in_price
                    FROM products as p
                    JOIN foods_in_order as fio
                    ON p.id = fio.product_id
                    JOIN orders as ord
                    ON ord.user_order_id = fio.order_id
                    AND fio.order_id = ?
                    JOIN added_ingredients as ai
                    ON ai.ord_id = ord.user_order_id
                    AND ai.product_id = p.id
                    JOIN ingredients as i
                    ON i.id = ai.ingredient_id
                    JOIN users as u
                    ON u.id = ?
                    GROUP BY ai.ingredient_id";
            $stmt = $this->getConnection()->prepare($query);
            $param = [$orderId, $userId];
            $stmt->execute($param);
            
            $history = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                $history[] = $row;
            }
            return  $history ? $history : false; 
       
    }
    
    public function getExtraIngr($productId, $orderId) {
        
        $query = "SELECT i.name, i.price
                 FROM ingredients as i 
                 JOIN added_ingredients as a_i
                 ON a_i.product_id = ?
                 AND a_i.ingredient_id = i.id
                 AND a_i.ord_id = ?
                 GROUP BY i.name";
        $stmt = $this->getConnection()->prepare($query);
        $params = [$productId, $orderId];
        
        $stmt->execute($params);
        
        $extraIngr = [];
        
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            $extraIngr[] = $row;
        }
        return $extraIngr ? $extraIngr : false;
    }
}