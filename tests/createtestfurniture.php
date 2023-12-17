<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Call the setCorsHeaders function
Utility::setCorsHeaders();

include_once '../backend/config/Utility.php';


$database = new Database();
$conn = $database->getConnection();

// Simulate receiving data in JSON format
$jsonData = '{"weight":300,"sku":"Demotest003","name":"Sample Book 1","price":"14.99","product_type":"furniture", "size":4, "height":40,"width":60,"length":120 }';

// Decode JSON data
$data = json_decode($jsonData, true);

// Create a new instance of the Book class
$book = new Furniture($conn);

// Set book data from the received JSON
$book->setSku($data['sku']);
$book->setName($data['name']);
$book->setPrice($data['price']);
$book->setHeight($data['height']);
$book->setWidth($data['width']);
$book->setLength($data['length']);
$book->setProductType($data['product_type']);


try {
    // Call the create function to insert the book into the database
    if ($bookDetails = $book->create()) {
        echo json_encode(array('message' => "Furniture with SKU " . $book->getSku() . " created successfully!"));
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

