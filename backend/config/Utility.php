<?php

namespace Config;

class Utility {
    public static function autoloader($class) {
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $classFile = __DIR__ . '/../' . $class . '.php';

        if (file_exists($classFile)) {
            include $classFile;
        }
    }

    public static function registerAutoloader() {
        spl_autoload_register(function ($class) {
            self::autoloader($class);
        });
    }
    

    public static function setCorsHeaders() {
        // Allow specified methods (GET, POST, DELETE, OPTIONS)
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
        header("Content-Type: application/json; charset=UTF-8");
    }

}


?>

