<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include Utility file first
include_once '../backend/config/Utility.php';

// Call the setCorsHeaders function
\Config\Utility::setCorsHeaders();

// Check if the class exists before attempting to register the autoloader
if (!class_exists('Config\Utility')) {
    \Config\Utility::registerAutoloader();
}

$database = new \config\Database();
$db = $database->getConnection();

// Create an instance of the Book class
$book = new \product\Book($db);
$dvd = new \product\Dvd($db);
$fur = new \product\Furniture($db);

// Call the read method to fetch and output all books as JSON
$book->read();
$dvd->read();
$fur->read();

?>
