<?php

spl_autoload_register(function ($class) {
    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once __DIR__ . DIRECTORY_SEPARATOR . $class . ".php";
});

//require_once './dbConnection.php';
/**
 * Description of FoodsDao
 *
 * @author denis
 */
class ProductsDao extends DbConnection {

    public function getAllProducts() {
        
            $query = "SELECT name, price FROM products;";
            $stmt = $this->getConnection()->prepare($query);
            $stmt->execute();
            $result = [];
            while ($row = $stmt->fetch()) {
                $result[] = $row;
            }

            return $result;
    }

    public function getProductById($id) {
        
            $query = "SELECT p.name, p.price FROM products as p WHERE p.id = ?;";
            $stmt = $this->getConnection()->prepare($query);
            $param = [$id];
            $stmt->execute($param);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                return $product;
            }
        
    }

    public function getProductIngredients($productId) {
        
            $query = "SELECT i.name
                    FROM ingredients as i
                    JOIN recipes as r
                    ON r.ingredient_id = i.id
                    AND r.product_id = ?";
            $stmt = $this->getConnection()->prepare($query);
            $param = [$productId];
            $stmt->execute($param);
            $result = [];
            while ($row =  $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[] = $row;
            }
            return  $result;
    }
    
    public function getIngredientsCategory() {
        
            $query = "SELECT name FROM ingredients_category";
            $stmt = $this->getConnection()->prepare($query);
            $stmt->execute();
            $result = [];
            while ($row =  $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[] = $row;
            }
            return  $result;
            
        
    }
    
    public function getIngredientsByCategory($categoryId) {
        
            $query = "SELECT distinct(i.name) 
                    FROM ingredients as i
                    JOIN ingredients_category as ig
                    ON i.category_id = ?;";
            $stmt = $this->getConnection()->prepare($query);
            $param = [$categoryId];
            $stmt->execute($param);
            $result = [];
            while ($row =  $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[] = $row;
            }
            return  $result;
    }
  
}

$a = new ProductsDao();

var_dump($a->getIngredientsByCategory(1));
