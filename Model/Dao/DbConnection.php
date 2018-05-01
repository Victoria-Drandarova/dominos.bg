<?php

namespace Model\Dao;
//use \PDO;
class DbConnection {

    const DB_HOST = "127.0.0.1";
    const DB_PORT = "3306";
    const DB_NAME = "dominos";
    const USER = "root";
    const PASS = "";

    public function getConnection() {

        try {
            $pdo = new \PDO("mysql:host=" . self::DB_HOST . ":" . self::DB_PORT .
            ";dbname=" . self::DB_NAME, self::USER, self::PASS, 
            [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'']);
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, FALSE);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $exp) {
            $errFile = fopen("../log/PDOExeption.txt", "a+");
            if (is_writable($errFile)) {
                fwrite($errFile, $exp->getMessage() . '. Date -->> ' . date('l jS \of F Y h:i:s A'));
                fclose($errFile);
            } else {
                fclose($errFile);
            }
            header("Location: ../index.php?page=errpage");
        }
    }

}
