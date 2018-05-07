<?php

namespace Model\Dao;

spl_autoload_register(function ($class) {
    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once __DIR__ . DIRECTORY_SEPARATOR . $c . ".php";
});

use Model\Dao\DbConnection;
use Model\SizeModel;
/**
 * Description of SizeDao
 *
 * @author denis
 */
class SizeDao extends DbConnection{
    
    public function getSizeList() {
        $query = "SELECT id, size, cost FROM pizza_size";
        
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute();
        
        $arrayOfSizes = [];
        
        while ($sizeRow = $stmt->fetch(\PDO::FETCH_OBJ)){
            $sizeModel = new SizeModel(
                    $sizeRow->id,
                    $sizeRow->size,
                    $sizeRow->cost
                    );
            $arrayOfSizes[] = $sizeModel;
        }
        return $arrayOfSizes ? $arrayOfSizes : false;
    }
    
    /* връща информация за размера според ид-то му */
    public function getPrizeById($id) {
        $query = "SELECT ps.id, ps.size, ps.cost 
                FROM pizza_size as ps
                WHERE ps.id = ?;";
        
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([$id]);
        
        $sizeData = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return  $sizeData ? $sizeData : false;
        
    }
}
