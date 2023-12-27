<?php

namespace Product;

//book subclass for product
class Book extends Product {
    // declare book specific field
    public $weight;
    private $conn;

    //declares constructor for the book class
    public function __construct($db)
    {
        parent::__construct($db);
        $this->conn = $db;
    }
    
    
    //setters and getters for the class specific fields
    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

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
        $product_id = $this->conn->insert_id; //  define product_id
        $this->id = $this->conn->insert_id; 
        $productStmt->close();

        // Insert data into the "book" table
        $bookStmt = $this->conn->prepare("
            INSERT INTO book(`id`, `product_id`, `weight`)
            VALUES(?, ?, ?)");

        $weight = $this->getWeight();

        // Use  defined $product_id as both id and product_id
        $bookStmt->bind_param("iid", $product_id, $product_id, $weight);

        // Execute the book query
        if ($bookStmt->execute()) {
            $bookStmt->close();

            // Set the 'id' property to be the same as 'product_id'
            $this->id = $product_id;

            // Set the product_type to 'book'
            $this->setProductType('book');

            // Return true if the book creation is successful
            return true;
        } else {
            // If fails, capture the MySQL error message
            $error = $this->conn->error;

            error_log("Failed to create book. MySQL error: " . $error);

            // Return the error message
            return array('error' => $error);
        }
    } else {
        // If execution fails for the query, capture the MySQL error message
        $error = $this->conn->error;

        error_log("Failed to create product. MySQL error: " . $error);

        // Return the error message
        return array('error' => $error);
    }
}

    
    

    
    

    function read() {
        $stmt = $this->conn->prepare("
            SELECT b.*, p.sku, p.name, p.price, p.product_type
            FROM book b
            INNER JOIN product p ON b.product_id = p.id
            WHERE p.product_type = 'book'
        ");

        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();

        // Check if there are rows in the result set
        if ($result->num_rows > 0) {
            // Fetch all data from the result set
            $booksData = $result->fetch_all(MYSQLI_ASSOC);

            return $booksData;
        } else {
            // No rows found
            return array();
        }
    }

function delete($conn) {
    try {
        $productId = $this->getId();
    
        // Delete the book entry
        $deleteBookStmt = $conn->prepare("DELETE FROM book WHERE product_id = ?");
        $deleteBookStmt->bind_param("i", $productId);
        $deleteBookStmt->execute();

        if ($deleteBookStmt->error) {
            throw new Exception("Error deleting book. MySQL error: " . $deleteBookStmt->error);
        }

        $deleteBookStmt->close();

        // Call the delete() method of the parent class (Product)
        parent::delete($conn);

    } catch (Exception $e) {

        error_log($e);

        throw $e;
    }
}




    
    
}



