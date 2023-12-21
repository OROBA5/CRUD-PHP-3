<?php
// delete.php
include_once '../backend/config/Utility.php';

// Register the autoloader
\Config\Utility::registerAutoloader();

// Call the setCorsHeaders function
\Config\Utility::setCorsHeaders();

$database = new \Config\Database();
$conn = $database->getConnection();

// Simulate receiving data in JSON format
$jsonData = '{"id": 301, "product_type": "dvd" }';

try {
    // Delete a product using the ProductFactory
    $result = \Product\ProductType::delete($jsonData, $conn);

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
