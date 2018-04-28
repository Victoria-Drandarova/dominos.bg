<?php

namespace Model\dao;

spl_autoload_register(function ($class) {
    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once $c . '.php';
});

use Model\ProductsModel;
use Model\IngredientsModel;
use Model\dao\DbConnection;

/**
 * Description of FoodsDao
 *
 * @author denis
 */
class ProductsDao extends DbConnection {
    
    const PIZZA = 1;
    

    public function getSingleProduct($productId) {

        $query = "SELECT p.id, p.name, p.price, p.img_url
                 FROM products as p
                 WHERE p.id = ?;";
        $stmt = $this->getConnection()->prepare($query);
        $param = [$productId];
        $stmt->execute($param);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getIngrById($ingredientId) {
        $query = "SELECT i.id, i.price, i.name
        FROM ingredients as i WHERE i.id = ?;";
        
        $stmt = $this->getConnection()->prepare($query);
        $param = [$ingredientId];
        $stmt->execute($param);
        
         $singleIngr = $stmt->fetch(\PDO::FETCH_ASSOC);
         return $singleIngr;
    }

    public function getAllProducts() {

        $query = "SELECT id, name, price FROM products;";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute();
        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $row["id"];
            $result[] = $row["name"];
            $result[] = $row["price"];
            $result[] = $row["img_url"];
        }

        return $result;
    }

    public function getAllPizza() {

        $query = "SELECT id, name, price, img_url, category_id
                 FROM products WHERE category_id = ?;";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([self::PIZZA]);

        $arrayOfPizzas = [];
        while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
            $singlePizza = new ProductsModel(
                    $row->id, $row->name, $row->price, $row->img_url, $row->category_id
            );
            $arrayOfPizzas[] = $singlePizza;
        }

        return $arrayOfPizzas;
    }

    public function getProductById($id) {

        $query = "SELECT p.id, p.name, p.price, p.img_url
                FROM products as p
                WHERE p.id = ?;";
        $stmt = $this->getConnection()->prepare($query);
        $param = [$id];
        $stmt->execute($param);
        $product = $stmt->fetch(\PDO::FETCH_OBJ);

        $singlePizza = new ProductsModel(
                $product->id, $product->name, $product->price, $product->img_url
        );
        return $singlePizza;
    }

    public function getProductIngredients($productId) {

        $query = "SELECT i.id, i.name, i.price, i.category_id 
                    FROM ingredients as i
                    JOIN recipes as r
                    ON r.ingredient_id = i.id
                    AND r.product_id = ?";
        $stmt = $this->getConnection()->prepare($query);
        $param = [$productId];
        $stmt->execute($param);
        $ingredients = [];
        while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
            $singleIngredient = new IngredientsModel(
                    $row->id, $row->name, $row->price, $row->category_id
            );
            $ingredients[] = $singleIngredient;
        }
        return $ingredients;
    }

    public function getProductInfo($productId) {

        $query = "SELECT i.id, i.name, i.price, p.name as product_name,
                p.img_url, p.price as product_price, p.id
                FROM ingredients as i
                JOIN recipes as r
                ON r.ingredient_id = i.id
                JOIN products as p
                ON r.product_id = p.id
                AND p.id = ?";
        $stmt = $this->getConnection()->prepare($query);
        $param = [$productId];
        $stmt->execute($param);
        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
            $result[] = $row;
        }
        return $result;
    }

    public function getIngredientsCategory() {

        $query = "SELECT id, name FROM ingredients_category";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute();
        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

    public function getIngredientsByCategory($categoryId) {

        $query = "SELECT distinct(i.name), i.id, i.price
                    FROM ingredients as i
                    JOIN ingredients_category as ig
                    ON i.category_id = ?;";
        $stmt = $this->getConnection()->prepare($query);
        $param = [$categoryId];
        $stmt->execute($param);
        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

    public function getAllIngredients() {

        $query = "SELECT i.name as ing_name, i,orice as ing_price
                FROM ingredients
                ORDER BY category_id;";
        $stmt = $this->getConnection()->prepare($query);

        $stmt->execute();
        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

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
            return $exp->getMessage();
        }
    }

}
