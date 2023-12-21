<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Include Utility file first
include_once '../backend/config/Utility.php';

// Call the setCorsHeaders function
\Config\Utility::setCorsHeaders();

// Check if the class exists before attempting to register the autoloader
if (!class_exists('Config\Utility')) {
    \Config\Utility::registerAutoloader();
}


$database = new \Config\Database();
$conn = $database->getConnection();

// Simulate receiving data in JSON format
$jsonData = '{"weight":300,"sku":"fur4","name":"fur4","price":"14.99","product_type":"furniture", "size":4, "height":40,"width":60,"length":120 }';

try {

    // Create a product using the ProductFactory
    $result = \Product\ProductType::create($jsonData, $conn);

    // Output the result
    echo json_encode($result);
} catch (Exception $e) {
    // Log the detailed error message including the file and line number
    error_log("Error in endpoint: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());

    // If an exception occurs, you can log it for further analysis
    error_log($e);
} finally {
    // Close the database connection
    $conn->close();
}
?>
