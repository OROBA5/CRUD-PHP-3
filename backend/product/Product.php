<?php

class Product extends ProductBlueprint {
    // declare primary product fields
    protected $id;
    protected $sku;
    protected $name;
    protected $price;
    protected $product_type;
    private $conn;

    //declares constructor for products
    public function __construct($db)
    { 
        $this->conn = $db;
    }

    //setters and getters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getProductType()
    {
        return $this->product_type;
    }

    public function setProductType($product_type)
    {
        $this->product_type = $product_type;
    }

    function create() {
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
        
        $productStmt->bind_param("ssii", $sku, $name, $price, $productType);
        
 // Insert data into the "product" table first
 if ($productStmt->execute()) {
    // Get the generated product ID
    $product_id = $productStmt->insert_id;
    $productStmt->close();
}; 
    }


    function read() {
        $stmt = $this->conn->prepare("
            SELECT p.id, p.sku, p.name, p.price, p.product_type
            FROM product p
            WHERE p.id = ?
        ");
    
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $productData = $result->fetch_assoc();
    
        // Check if $productData is not null before accessing its elements
        if ($productData !== null) {
            $this->id = $productData['id'];
            $this->sku = $productData['sku'];
            $this->name = $productData['name'];
            $this->price = $productData['price'];
            $this->product_type = $productData['product_type'];
        }
    
        return $result; // Return the result if needed
    }
    
    

    //utilises delete funcion for deleting a product
    function delete($conn) {
        $productId = $this->getId();
    
        // Delete the product entry
        $deleteProductStmt = $conn->prepare("DELETE FROM product WHERE id = ?");
        $deleteProductStmt->bind_param("i", $productId);
        $deleteProductStmt->execute();
        $deleteProductStmt->close();
    }

}