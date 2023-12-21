<?php

// read.php
include_once '../backend/config/Utility.php';

// Register the autoloader
\Config\Utility::registerAutoloader();

// Call the setCorsHeaders function
\Config\Utility::setCorsHeaders();

// Now you can use Config\Database
$database = new \Config\Database();


$database = new \config\Database();
$db = $database->getConnection();

// Create an instance of the Book class
$book = new \product\Book($db);
$dvd = new \product\Dvd($db);
$fur = new \product\Furniture($db);

// Call the read method to fetch and output all books as JSON
$book->read();
$result1 = var_dump($book);
$dvd->read();
$result2 = var_dump($dvd);
$fur->read();
$result3 = var_dump($fur);

?>
