<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../product/ProductBlueprint.php';
include_once '../product/Product.php';
include_once '../product/Book.php';

$database = new Database();
$db = $database->getConnection();

// Create an instance of the Book class
$book = new Book($db);

// Call the read method to fetch and output all books as JSON
$book->read();

?>
