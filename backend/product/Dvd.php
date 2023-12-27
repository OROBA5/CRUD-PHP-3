<?php

namespace Product;

// DVD subclass for Product
class Dvd extends Product {
    // Declare DVD specific fields
    public $size;
    private $conn;

    // Declare constructor for the DVD class
    public function __construct($db)
    {
        parent::__construct($db);
        $this->conn = $db;
    }
    
    
    // Setters and getters for the class specific fields
    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
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
        $this->id = $product_id;
        $productStmt->close();

        // Insert data into the "dvd" table
        $dvdStmt = $this->conn->prepare("
            INSERT INTO dvd(`id`, `product_id`, `size`)
            VALUES(?, ?, ?)");

        $size = $this->getSize();

        // Use defined $product_id as both id and product_id
        $dvdStmt->bind_param("iii", $product_id, $product_id, $size);

        // Execute the dvd query
        if ($dvdStmt->execute()) {
            $dvdStmt->close();

            // Set the product_type to 'dvd'
            $this->setProductType('dvd');

            // Return true if the dvd creation is successful
            return true;
        } else {
            // If fails, capture the MySQL error message
            $error = $this->conn->error;

            error_log("Failed to create dvd. MySQL error: " . $error);

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

    public function read() {
        $stmt = $this->conn->prepare("
        SELECT d.*, p.sku, p.name, p.price, p.product_type
        FROM dvd d
        INNER JOIN product p ON d.product_id = p.id
        WHERE p.product_type = 'dvd'
    ");

    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    // Check if there are rows in the result set
    if ($result->num_rows > 0) {
        // Fetch all data from the result set
        $dvdsData = $result->fetch_all(MYSQLI_ASSOC);

        return $dvdsData;
    } else {
        // No rows found
        return array();
    }
    }

    public function delete($conn) {
        try {
            $productId = $this->getId();
        
            // Delete the dvd entry
            $deleteDvdStmt = $conn->prepare("DELETE FROM dvd WHERE product_id = ?");
            $deleteDvdStmt->bind_param("i", $productId);
            $deleteDvdStmt->execute();

            if ($deleteDvdStmt->error) {
                throw new \Exception("Error deleting dvd. MySQL error: " . $deleteDvdStmt->error);
            }

            $deleteDvdStmt->close();

            // Delete the product entry
            parent::delete($conn);

        } catch (\Exception $e) {
            error_log($e);

            throw $e;
        }
    }
}

