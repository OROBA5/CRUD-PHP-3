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
}
