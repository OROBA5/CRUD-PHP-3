<?php

namespace Config;

class Utility {
    // Autoloader function
    public static function autoloader($class) {
        // Convert namespace separators to directory separators and load the class file
        $classFileConfig = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
        $classFileProductDirectory = dirname(__DIR__) . '/product/' . str_replace('\\', '/', $class) . '.php';
        $classFileControllertDirectory = dirname(__DIR__) . '/controller/' . str_replace('\\', '/', $class) . '.php';

        if (file_exists($classFileConfig)) {
            include $classFileConfig;
        } elseif (file_exists($classFileProductDirectory)) {
            include $classFileProductDirectory;
        } elseif (file_exists($classFileControllertDirectory)) {
            include $classFileControllertDirectory;
        } 
    }

    // Register the autoloader function
    public static function registerAutoloader() {
        spl_autoload_register(array('Utility', 'autoloader'));
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

