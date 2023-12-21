<?php

// read.php
include_once '../backend/config/Utility.php';

// Register the autoloader
\Config\Utility::registerAutoloader();

// Call the setCorsHeaders function
\Config\Utility::setCorsHeaders();

// Now you can use Config\Database
$database = new \Config\Database();
$conn = $database->getConnection();

// Create an instance of the ProductType class
$productType = new \Product\ProductType();

// Call the read method to fetch and output all products as JSON
$read = $productType->read($conn);

?>
