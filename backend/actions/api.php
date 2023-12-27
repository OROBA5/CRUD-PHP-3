<?php

include_once '../config/Utility.php';
\Config\Utility::registerAutoloader();
\Config\Utility::setCorsHeaders();
$database = new \Config\Database();
$conn = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

$productType = new \Product\ProductType();

switch ($method) {
    case 'GET':
        $products = $productType->read($conn);
        return true;
        break;
    case 'POST':
        // Check if it's a create or delete operation
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['action'])) {
            $action = $data['action'];
            switch ($action) {
                case 'create':
                    $created = $productType->create($data, $conn);
                    if ($created) {
                        http_response_code(201);
                    } else {
                        http_response_code(500);
                    }
                    break;
                case 'delete':
                    // Delete operation
                    $productId = isset($data['id']) ? $data['id'] : null;
                    if ($productId) {
                        $deleted = $productType->delete($conn, $productId);
                        if ($deleted) {
                            http_response_code(200);
                        } else {
                            http_response_code(500);
                        }
                    } else {
                        http_response_code(400);
                    }
                    break;
                default:
                    http_response_code(400);
                    break;
            }
        } else {
            http_response_code(400);
        }
        break;
    default:
        http_response_code(405);
        break;
}
?>
