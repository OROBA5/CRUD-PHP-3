<?php

namespace Product;

class ProductType
{
    public static function create($json, $conn)
    {
        $data = json_decode($json, true);

        if (!$data || !isset($data['product_type'])) {
            return array('error' => 'Invalid JSON data or missing product_type');
        }

        $productType = $data['product_type'];

        $className = 'Product\\' . ucfirst($productType);

        if (class_exists($className)) {
            $product = new $className($conn);

            foreach ($data as $key => $value) {
                $setter = 'set' . ucfirst($key);
                if (method_exists($product, $setter)) {
                    $product->$setter($value);
                }
            }

            // Set the product_type property within the Product class
            if (property_exists($product, 'product_type')) {
                $product->product_type = $productType;
            }

            // Call the create method if it exists in the product class
            if (method_exists($product, 'create')) {
                return $product->create();
            } else {
                return array('error' => 'Create method not implemented in ' . $productType);
            }
        } else {
            return array('error' => 'Unsupported product type');
        }
    }

    public static function delete($json, $conn)
    {
        $data = json_decode($json, true);

        if (!$data || !isset($data['id']) || !isset($data['product_type'])) {
            return array('error' => 'Invalid JSON data or missing id or product_type');
        }

        $productId = $data['id'];
        $productType = $data['product_type'];

        $className = 'Product\\' . ucfirst($productType);

        if (class_exists($className)) {
            $product = new $className($conn);

            // Set the id property within the Product class
            if (property_exists($product, 'id')) {
                $product->id = $productId;
            }

            // Call the delete method if it exists in the product class
            if (method_exists($product, 'delete')) {
                return $product->delete($conn);
            } else {
                return array('error' => 'Delete method not implemented in ' . $productType);
            }
        } else {
            return array('error' => 'Unsupported product type');
        }
    }

// Inside the ProductType class
// Inside the ProductType class
public function read($conn)
{
    $products = [];

    // Fetch all product types
    $productTypeQuery = "SELECT * FROM product_type";
    $productTypeResult = $conn->query($productTypeQuery);

    if (!$productTypeResult) {
        throw new \Exception("Error executing product type query: " . $conn->error);
    }

    while ($productType = $productTypeResult->fetch_assoc()) {
        $productClassName = 'Product\\' . ucfirst($productType['name']);

        // Use ReflectionClass to instantiate the class with $conn parameter
        $reflectionClass = new \ReflectionClass($productClassName);

        // Create a new instance of the product class with $conn parameter
        $product = $reflectionClass->newInstanceArgs([$conn]);

        // Call the read method of the product object
        $result = $product->read();

        // Merge the results into the $products array
        $products = array_merge($products, $result);
    }

    // Remove duplicates based on the "id" field
    $uniqueProducts = array_map("unserialize", array_unique(array_map("serialize", $products)));

    // Sort the array by the "id" field in ascending order
    usort($uniqueProducts, function ($a, $b) {
        return $a['id'] - $b['id'];
    });

    // Output the sorted and unique results as JSON
    echo json_encode($uniqueProducts, JSON_PRETTY_PRINT);

    return $uniqueProducts;
}



}
