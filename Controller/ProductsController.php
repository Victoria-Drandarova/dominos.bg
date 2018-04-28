<?php

namespace Controller;

spl_autoload_register(function ($class) {

    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . $c . '.php';
});

use Model\dao\ProductsDao;
use Model\ProductsModel;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ProductsController {

    private static $instance;

//    private function __construct() {}

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new ProductsController();
        }
        return self::$instance;
    }

    public function addProductToCart($productId) {
        $singleProduct = new ProductsDao();
        $success = $singleProduct->getSingleProduct($productId);
        if ($success) {
            if (!in_array($success["id"], array_column($_SESSION["cart"], "id"))) {
                /* set default quantity  to  1 */
                $success["quantity"] = 1;
//                $success["extraIng"] = [];
                $_SESSION["cart"][$success["id"]] = $success;
                echo "You added " . $success['name'] . " in  your cart!";
            } else {
                echo "You allready have this food in your cart :)";
            }

            header("Location: ../View/some.php");
        } else {
            //todo return err msg
        }
    }

    public function addExtraIngToProd($ingId, $prdId) {
        try {
            $ingredients = new ProductsDao();
            $r = $ingredients->getIngrById($ingId);

            if (isset($_SESSION["cart"])) {

                if (in_array($prdId, array_column($_SESSION["cart"], "id"))) {
                    $_SESSION["cart"][$prdId]["extraIng"][] = $r["id"];

                    echo $r["price"];
//                    header("Location: ../View/some.php");
                } else {
                    //todo return err msg
                }
            } else {
                //todo return err msg
            }
        } catch (PDOException $exp) {

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

    public function removeExtraIngFromProd($ingId, $prdId) {
        try {
            $ingredients = new ProductsDao();
            $r = $ingredients->getIngrById($ingId);

            if (isset($_SESSION["cart"])) {

                if (in_array($prdId, array_column($_SESSION["cart"], "id"))) {
//                    $_SESSION["cart"][$prdId]["extraIng"][] = $r["id"];
                    array_values('array_values', $_SESSION["cart"][$prdId]["extraIng"]);
                    $cnt = count($_SESSION["cart"][$prdId]["extraIng"]);
                    for ($i = 0; $i < $cnt; $i++) {
                        if ($_SESSION["cart"][$prdId]["extraIng"][$i] == $r["id"]) {
                            /* this is under counstruct */
                            unset($_SESSION["cart"][$prdId]["extraIng"][$i]);
                            break;
                        }
                    }
                    echo $r["price"];
//                    header("Location: ../View/some.php");
                } else {
                    //todo return err msg
                }
            } else {
                //todo return err msg
            }
        } catch (PDOException $exp) {

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

    public function plusQuantity($productId) {
        // if(isset($_SESSION["logged_user"]) && isset($_SESSION["cart"]))
        if (isset($_SESSION["cart"])) {

            if (in_array($productId, array_column($_SESSION["cart"], "id"))) {
                $q = $_SESSION["cart"][$productId]["quantity"] = $_SESSION["cart"][$productId]["quantity"] + 1;
                return $_SESSION["cart"][$productId]["quantity"];
            } else {
                //todo return err msg
            }
        } else {
            //todo return err msg
        }
    }

    public function minusQunatity($productId) {
        // if(isset($_SESSION["logged_user"]) && isset($_SESSION["cart"]))
        if (isset($_SESSION["cart"])) {

            if (in_array($productId, array_column($_SESSION["cart"], "id"))) {
                if ($_SESSION["cart"][$productId]["quantity"] == 1) {
                    unset($_SESSION["cart"][$productId]);
                    return 0;
                }
                $q = $_SESSION["cart"][$productId]["quantity"] = $_SESSION["cart"][$productId]["quantity"] - 1;

                return $_SESSION["cart"][$productId]["quantity"];
            } else {
                //to do return  err msg
            }
        } else {
            //todo return err msg
        }
    }

    public function getExtraIng($extraIngId) {
        if (isset($_SESSION["cart"])) {
            $ingredients = new ProductsDao();
            $r = $ingredients->getIngrById($extraIngId);
            echo json_encode($r);
        } else {
            //todo return err msg
        }
    }

    public function getCartContent() {
        // if(isset($_SESSION["logged_user"]) && isset($_SESSION["cart"]))
        if (isset($_SESSION["cart"])) {

            return json_encode($_SESSION["cart"]);
        } else {
            //todo return err msg
        }
    }

    public function getPizza() {

        try {
            $pizzaList = new ProductsDao();
            return $pizzaList->getAllPizza();
        } catch (PDOException $exp) {

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

            $info = new ProductsDao();
            return $info->getProductInfo($productId);
        } catch (PDOException $exp) {

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

            $ingCategory = new ProductsDao();
            return $ingCategory->getIngredientsCategory();
        } catch (PDOException $exp) {

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
    
    public function isInExtraList($ingrId, $productId) {
        $proDao = new ProductsDao();
        $ingrData = $proDao->getIngrById($ingrId);
        $empty = true;
        foreach ($_SESSION["cart"][$productId]["extraIng"] as  &$inId) {
            if ($ingrData["id"] == $inId) {
                echo $ingrData["price"];
                $empty = false;
            }
        }
        if ($empty) {
            echo 0;
        }
    }

    public function getIngByCategory($categoryId) {

        try {

            $getIngredientsByCategory = new ProductsDao();
            return json_encode($getIngredientsByCategory->getIngredientsByCategory($categoryId));
        } catch (PDOException $exp) {

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
/* retrun selected pizza with content of the pizza */
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
    echo $products->getIngByCategory($categoriesId);
}
/* insert new prduct to cart */
if (isset($_POST["proId"])) {
    $products = ProductsController::getInstance();
    $prodId = trim(htmlentities($_POST["proId"]));
    $products->addProductToCart($prodId);
}
/* return  cart content */
if (isset($_POST["cart"])) {
    $products = ProductsController::getInstance();
    echo $products->getCartContent();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["prId"])) {
    $products = ProductsController::getInstance();
    $prId = trim(htmlentities($_GET["prId"]));
    echo $products->plusQuantity($prId);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["minusId"])) {
    $products = ProductsController::getInstance();
    $minusId = trim(htmlentities($_GET["minusId"]));
    echo $products->minusQunatity($minusId);
}

if (isset($_POST["ingId"]) && isset($_POST["prdId"])) {
    $products = ProductsController::getInstance();
    $ingId = trim(htmlentities($_POST["ingId"]));
    $prodId = trim(htmlentities($_POST["prdId"]));
    echo $products->addExtraIngToProd($ingId, $prodId);
}


if (isset($_POST["minusIngId"]) && isset($_POST["minusPrdId"])) {
    $products = ProductsController::getInstance();
    $ingId = trim(htmlentities($_POST["minusIngId"]));
    $prodId = trim(htmlentities($_POST["minusPrdId"]));
    echo $products->removeExtraIngFromProd($ingId, $prodId);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["extraIngId"])) {
    $products = ProductsController::getInstance();
    $ingId = trim(htmlentities($_GET["extraIngId"]));
    echo $products->getExtraIng($ingId);
}

if (isset($_GET["iId"]) && isset($_GET["proId"])) {
    $products = ProductsController::getInstance();
    $ingId = trim(htmlentities($_GET["iId"]));
    $proId = trim(htmlentities($_GET["proId"]));
    return $products->isInExtraList($ingId, $proId);
}