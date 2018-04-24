<?php

namespace Model\dao;

spl_autoload_register(function ($class) {
    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once $c . '.php';
});

use Model\PurchaseModel;
use Model\dao\DbConnection;

/**
 * Purchase dao that  hold  the  queries that work  with  
 * users history 
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
        try {
            $this->getConnection()->beginTransaction();
            $foodInOrderQuery = "INSERT INTO foods_in_order(order_id, product_id, quantity) 
                VALUES(?,?,?)";
            $stmt = $this->getConnection()->prepare($foodInOrderQuery);
            
            $params = [
                       $ordParams->getOrderId(),
                       $ordParams->getProductId(),
                       $ordParams->getQuantity()
                      ];
            $stmt->execute($params);
            $this->getConnection()->commit();
        } catch (\PDOException $exp) {
            $this->getConnection()->rollBack();
            //TODO  redirect or err msg
        }
    }

}
