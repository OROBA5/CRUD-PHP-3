<?php

namespace Config;

class Utility {
    // Autoloader function
    public static function autoloader($class) {
        // Convert namespace separators to directory separators
        $classFile = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
        
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
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, DELETE");
        header("Content-Type: application/json; charset=UTF-8");
    }

}

// Register the autoloader
Utility::registerAutoloader();


?>

