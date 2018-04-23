<?php
require_once 'dbConnection.php';

namespace Model\dao;

spl_autoload_register(function ($class) {
    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once __DIR__ . DIRECTORY_SEPARATOR . $c . ".php";
});

use Model\ProductsModel;

/**
 * Description of FoodsDao
 *
 * @author denis
 */
class ProductsDao extends DbConnection {

    public function getAllProducts() {

        $query = "SELECT id, name, price FROM products;";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute();
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row["id"];
            $result[] = $row["name"];
            $result[] = $row["price"];
            $result[] = $row["img_url"];
        }

        return $result;
    }

    public function getAllPizza() {

        $query = "SELECT id, name, price, img_url FROM products WHERE category_id = 1;";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute();
        $arrayOfPizzas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $singlePizza = new ProductsModel(
                    $row["id"],
                    $row["name"],
                    $row["price"],
                    $row["img_url"]
                    );
            $arrayOfPizzas[] = $singlePizza;
        }

        return $arrayOfPizzas;
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
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

    public function getProductInfo($productId) {

        $query = "SELECT i.name, i.price, p.name as product_name,
                p.img_url, p.price as product_price
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
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

    public function getIngredientsCategory() {

        $query = "SELECT id, name FROM ingredients_category";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute();
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

}
