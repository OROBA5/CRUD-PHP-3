<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Call the setCorsHeaders function
Utility::setCorsHeaders();

include_once '../backend/config/Utility.php';

$database = new Database();
$conn = $database->getConnection();

// Sample JSON data
$sampleJsonData = '{"id":277,"product_id":267,"weight":300,"sku":"Demotest001","name":"Sample Book 1","price":"14.00","product_type":"book"}';

// Decode the sample JSON data
$input = json_decode($sampleJsonData, true);

// Check if the required fields are present in the JSON data
if (isset($input['id']) && isset($input['product_id']) && isset($input['weight'])
    && isset($input['sku']) && isset($input['name']) && isset($input['price']) && isset($input['product_type'])) {

    // Create a new instance of the Book class
    $book = new Book($conn);

    // Set the book data from the JSON input
    $book->setId($input['id']);
    $book->setSku($input['sku']);
    $book->setName($input['name']);
    $book->setPrice($input['price']);
    $book->setWeight($input['weight']);
    $book->setProductType($input['product_type']);

    try {
        // Call the delete function to delete the book from the database
        $book->delete($conn);
    
        echo json_encode(array('message' => "Book with ID {$input['id']} deleted successfully!"));
    } catch (Exception $e) {
        $error = $e->getMessage();
    
        // Log the MySQL error for further analysis
        error_log("Error deleting book. Message: " . $error);
    
        // Display the error message
        echo json_encode(array('error' => $error));
    } catch (Error $e) {
        $error = $e->getMessage();
    
        // Log the MySQL error for further analysis
        error_log("Fatal error deleting book. Message: " . $error);
    
        // Display a generic error message
        echo json_encode(array('error' => 'An internal error occurred.'));
    } finally {
        // Close the database connection
        $conn->close();
    }

    
    
}

?>
