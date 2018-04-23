<?php

require_once "../config/dbConfig.php";

/**
 * Description of dbConnection
 *
 * @author denis
 */
class dbConnection {

    public function getConnection() {

        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ":" . DB_PORT . ";dbname=" . DB_NAME, USER, PASS, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'']);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $exp) {
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

