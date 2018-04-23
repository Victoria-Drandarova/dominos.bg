<?php

spl_autoload_register(function ($class) {
    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR ."Model" 
    . DIRECTORY_SEPARATOR . $c . ".php";
});
use Model\dao\ProductsDao;
//require_once '../Model/ProductsDao.php';

/**
 * Description of ProductsController
 *
 * @author denis
 */
class ProductsController {

    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new ProductsController();
        }
        return self::$instance;
    }

    public function getPizza() {

        try {
            $productsDao = new ProductsDao();
            return $productsDao->getAllPizza();
            
        } catch (Exception $exp) {

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
    
    public function getInfoProduct($productId) {

        try {

            return $this->getProductInfo($productId);
            
        } catch (Exception $exp) {

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
    
    public function getIngCategory() {

        try {

            return $this->getIngredientsCategory();
            
        } catch (Exception $exp) {

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
    
    public function getIngByCategory($categoryId) {

        try {

            return $this->getIngredientsByCategory($categoryId);
            
        } catch (Exception $exp) {

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

/* retrun jason with all pizzas */
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["pizza"])) {
    $products = ProductsController::getInstance();
    echo json_encode($products->getPizza());
}
/* retrun selected pizza with content of  the  pizza */
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["productId"])) {
    $products = ProductsController::getInstance();
    $productId = trim(htmlentities($_GET["productId"]));
    echo json_encode($products->getInfoProduct($productId));
}
/* retrun jason with all ingredients categories */
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["categories"])) {
    $products = ProductsController::getInstance();
    echo json_encode($products->getIngCategory());
}
/* return list of ingredients by category ID */
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["categoriesId"])) {
    $products = ProductsController::getInstance();
    $categoriesId = trim(htmlentities($_GET["categoriesId"]));
    echo json_encode($products->getIngByCategory($categoriesId));
}
