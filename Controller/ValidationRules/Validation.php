<?php
namespace Controller\ValidationRules;

/**
 * Description of Validation
 *
 * @author denis
 */
class Validation {
    
    public static function isEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return  false;
        }else{
            return  true;
        }
    }
    
    public static function clearData($var){
        
        $var = trim(htmlentities($var));
        return  $var;
    }
}
