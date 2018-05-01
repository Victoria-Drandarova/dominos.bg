<?php

namespace Model\Dao;

spl_autoload_register(function ($class) {
    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once $c . '.php';
});

use Model\PurchaseModel;
use Model\Dao\DbConnection;

/**
 * Purchase dao that  hold  the  queries that work  with  
 * users history and  insert of purchase
 *
 * @author denis
 */
class PurchaseDao extends DbConnection {

    public function insertOrder($userId) {
        $connection = $this->getConnection();
        try {
            $connection->beginTransaction();
            $orderQuery = "INSERT INTO orders(user_id, date)
                       VALUES(?, now())";
            $stmt = $connection->prepare($orderQuery);
            $param = [$userId];
            $stmt->execute($param);

            /* взимаме id на последния запис */
            $orderId = $connection->lastInsertId();

            $connection->commit();
            return $orderId;
        } catch (\PDOException $exp) {
            $connection->rollBack();
            return $exp->getMessage();
        }
    }

    public function insertHistory(PurchaseModel $ordParams) {
        $connection = $this->getConnection();
        try {
            $connection->beginTransaction();
            $foodInOrderQuery = "INSERT INTO foods_in_order(order_id, product_id, quantity) 
                VALUES(?,?,?)";
            $stmt = $connection->prepare($foodInOrderQuery);
            
            $params = [
                       $ordParams->getOrderId(),
                       $ordParams->getProductId(),
                       $ordParams->getQuantity()
                      ];
            $stmt->execute($params);
            $connection->commit();
            
        } catch (\PDOException $exp) {
            $connection->rollBack();
            echo $exp->getMessage();
            //TODO  redirect or err msg
        }
    }
    
    public function extraIngrToProduct($ordId, $prodId, $ingId) {
        $connection = $this->getConnection();
        try {
            $connection->beginTransaction();
            $ingrInProduct = "INSERT INTO added_ingredients(ord_id, product_id, ingredient_id)
                VALUES(?,?,?)";
            $stmt = $connection->prepare($ingrInProduct);
            $params = [$ordId, $prodId, $ingId];
            $stmt->execute($params);
            $connection->commit();
            
        } catch (\PDOException $exp) {
            $connection->rollBack();
            return $exp->getMessage();
            //TODO  redirect or err msg
        }
    }

}
