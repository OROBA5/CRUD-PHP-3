<?php

namespace Product;

class ProductType
{
    public static function create($data, $conn)
    {

        if (!$data || !isset($data['product_type'])) {
            return array('error' => 'Invalid JSON data or missing product_type');
        }

        $productType = $data['product_type'];

        $className = 'Product\\' . ucfirst($productType);

        if (class_exists($className)) {
            $product = new $className($conn);


        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key);

            // Check if the method exists and is callable
            if (method_exists($product, $setter) && is_callable([$product, $setter])) {
                // Call the setter method to set the property value
                $product->$setter($value);
            }
        }


            if (property_exists($product, 'product_type')) {
                $product->product_type = $productType;
            }

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

            if (property_exists($product, 'id')) {
                $product->id = $productId;
            }

            if (method_exists($product, 'delete')) {
                return $product->delete($conn);
            } else {
                return array('error' => 'Delete method not implemented in ' . $productType);
            }
        } else {
            return array('error' => 'Unsupported product type');
        }
    }

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

        //ReflectionClass to instantiate the class
        $reflectionClass = new \ReflectionClass($productClassName);

        // Create a new instance of the product class
        $product = $reflectionClass->newInstanceArgs([$conn]);

        $result = $product->read();

        // Merge the results into the $products array
        $products = array_merge($products, $result);
    }

    // Remove duplicates based on id
    $uniqueProducts = array_map("unserialize", array_unique(array_map("serialize", $products)));

    // Sort the array
    usort($uniqueProducts, function ($a, $b) {
        return $a['id'] - $b['id'];
    });

    echo json_encode($uniqueProducts);

    return $uniqueProducts;
}



}
