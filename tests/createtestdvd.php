<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../backend/config/Database.php';
include_once '../backend/product/ProductBlueprint.php';
include_once '../backend/product/Product.php';
include_once '../backend/product/Book.php';
include_once '../backend/product/Dvd.php';
include_once '../backend/product/Furniture.php';

$database = new Database();
$conn = $database->getConnection();

// Simulate receiving data in JSON format
$jsonData = '{"weight":300,"sku":"Demotest003","name":"Sample dvd 1","price":"14.99","product_type":"dvd", "size":4, "height":40,"width":60,"length":120 }';

// Decode JSON data
$data = json_decode($jsonData, true);

// Create a new instance of the Book class
$book = new DVD($conn);

// Set book data from the received JSON
$book->setSku($data['sku']);
$book->setName($data['name']);
$book->setPrice($data['price']);
$book->setSize($data['size']);
$book->setProductType($data['product_type']);


try {
    // Call the create function to insert the book into the database
    if ($bookDetails = $book->create()) {
        echo json_encode(array('message' => "Book with SKU " . $book->getSku() . " created successfully!"));
    } else {
        // Capture and display the database error message and bound parameters, if available
        $databaseError = $conn->error;
        $boundParams = isset($bookStmt) ? $bookStmt->debugDumpParams() : null;

        echo json_encode(array(
            'error' => $databaseError,
            'bound_params' => $boundParams
        ));
    }
} catch (Exception $e) {
    // If an exception occurs, you can log it for further analysis
    error_log($e);
} finally {
    // Close the database connection
    $conn->close();
}
