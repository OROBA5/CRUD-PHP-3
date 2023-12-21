<?php

namespace Product;

class Furniture extends Product {
    // Declare furniture specific fields
    public $height;
    public $width;
    public $length;
    private $conn;

    // Declare constructor for the Furniture class
    public function __construct($db)
    {
        parent::__construct($db);
        $this->conn = $db;
    }

    // Setters and getters for the class specific fields
    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function setLength($length)
    {
        $this->length = $length;
    }

    // Inside the Furniture class
public function create()
{
    // Insert data into the "product" table
    $productStmt = $this->conn->prepare("
        INSERT INTO product(`sku`, `name`, `price`, `product_type`)
        VALUES(?, ?, ?, ?)");

    $sku = $this->getSku();
    $name = $this->getName();
    $price = $this->getPrice();
    $productType = $this->getProductType();

    $sku = htmlspecialchars(strip_tags($sku));
    $name = htmlspecialchars(strip_tags($name));
    $price = htmlspecialchars(strip_tags($price));
    $productType = htmlspecialchars(strip_tags($productType));

    $productStmt->bind_param("ssis", $sku, $name, $price, $productType);

    // Insert data into the "product" table first
    if ($productStmt->execute()) {
        // Get the generated product ID
        $product_id = $this->conn->insert_id; // Explicitly define product_id
        $this->id = $product_id; // Update this line
        $productStmt->close();

        // Insert data into the "furniture" table
        $furnitureStmt = $this->conn->prepare("
            INSERT INTO furniture(`id`, `product_id`, `height`, `width`, `length`)
            VALUES(?, ?, ?, ?, ?)");

        $height = $this->getHeight();
        $width = $this->getWidth();
        $length = $this->getLength();

        // Use explicitly defined $product_id as both id and product_id
        $furnitureStmt->bind_param("iiiii", $product_id, $product_id, $height, $width, $length);

        // Execute the furniture query
        if ($furnitureStmt->execute()) {
            $furnitureStmt->close();

            // Set the product_type to 'furniture'
            $this->setProductType('furniture');

            // Return true if the furniture creation is successful
            return true;
        } else {
            // If execution fails, capture the MySQL error message
            $error = $this->conn->error;

            // Log the error for further analysis
            error_log("Failed to create furniture. MySQL error: " . $error);

            // Return the error message
            return array('error' => $error);
        }
    } else {
        // If execution fails for the product query, capture the MySQL error message
        $error = $this->conn->error;

        // Log the error for further analysis
        error_log("Failed to create product. MySQL error: " . $error);

        // Return the error message
        return array('error' => $error);
    }
}


    // Read function specific to Furniture
    // Read function specific to Furniture
    public function read() {
        $stmt = $this->conn->prepare("
            SELECT f.*, p.sku, p.name, p.price, p.product_type
            FROM furniture f
            INNER JOIN product p ON f.product_id = p.id
            WHERE p.product_type = 'furniture'
        ");

        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();

        // Check if there are rows in the result set
        if ($result->num_rows > 0) {
            // Fetch all data from the result set
            $furnitureData = $result->fetch_all(MYSQLI_ASSOC);

            // Output Furniture data as JSON
            http_response_code(200);
            echo json_encode($furnitureData);
        } else {
            // No rows found
            http_response_code(404);
            echo json_encode(array());
        }
    }


    
    function delete($conn) {
        try {
            $productId = $this->getId();
        
            // Delete the furniture entry
            $deleteFurnitureStmt = $conn->prepare("DELETE FROM furniture WHERE product_id = ?");
            $deleteFurnitureStmt->bind_param("i", $productId);
            $deleteFurnitureStmt->execute();
    
            if ($deleteFurnitureStmt->error) {
                throw new Exception("Error deleting furniture. MySQL error: " . $deleteFurnitureStmt->error);
            }
    
            $deleteFurnitureStmt->close();
    
            // Call the delete() method of the parent class (Product)
            parent::delete($conn);
    
        } catch (Exception $e) {
            // Log the error for further analysis
            error_log($e);
    
            // Re-throw the exception to propagate it up the call stack
            throw $e;
        }
    }

    
}

