<?php

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

    function create(){

        // Call create method of the parent class to insert data into the "product" table
        if (parent::create()) {
            // Get the generated product ID from the parent class
            $product_id = $this->getId();

            // Insert data into the "book" table
            $bookStmt = $this->conn->prepare("
                INSERT INTO book(`product_id`, `weight`)
                VALUES(?, ?)");

            $weight = $this->getWeight();
            $bookStmt->bind_param("id", $product_id, $weight);

            // Execute the book query
            if ($bookStmt->execute()) {
                $bookStmt->close();

                // Retrieve the book details
                $bookDetails = array(
                    'product_id' => $product_id,
                    'weight' => $weight
                );

                // Close the database connection
                $this->conn->close();

                // Return the book details
                return $bookDetails;
            }

            $bookStmt->close();
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

            // Output books data as JSON
            http_response_code(200);
            echo json_encode($booksData);
        } else {
            // No rows found
            http_response_code(404);
            echo json_encode(array("message" => "No books found."));
        }
    }

    function delete($conn) {
        $productId = $this->getId();

        // Delete the book entry
        $deleteBookStmt = $conn->prepare("DELETE FROM book WHERE product_id = ?");
        $deleteBookStmt->bind_param("i", $productId);
        $deleteBookStmt->execute();
        $deleteBookStmt->close();

        // Call the delete() method of the parent class (Product)
        parent::delete($conn);

    }
    
}



