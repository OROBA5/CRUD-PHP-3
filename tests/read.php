<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../backend/config/Database.php';
include_once '../backend/product/ProductBlueprint.php';
include_once '../backend/product/Product.php';
include_once '../backend/product/Book.php';
include_once '../backend/product/Dvd.php';
include_once '../backend/product/Furniture.php';

$database = new Database();
$db = $database->getConnection();

// Create an instance of the Book class
$book = new Book($db);
$dvd = new Dvd($db);
$fur = new Furniture($db);

// Call the read method to fetch and output all books as JSON
$book->read();
$dvd->read();
$fur->read();

?>
