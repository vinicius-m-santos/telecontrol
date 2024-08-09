<?php

require_once '../../autoload.php';

use App\Models\Product;


try {
    if (!isset($_POST['action'])) {
        throw new Exception("Action is required", 400);
    }

    $action = json_decode($_POST['action']);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage(),
        "code" => $e->getCode() ?: 400
    ]);

    exit;
}

switch ($action) {
    case 'save': 
        try {
            $response = [
                "success" => true,
                "code" => 200
            ];

            if (!isset($_POST['params'])) {
                throw new Exception("Params are required", 400);
            }
            $params = json_decode($_POST['params'], true);
            
            validateProductData($params);
            $product = new Product();
            if (isset($params['productId'])) {
                $product->setId($params['productId']);
            }
            $product->setCode($params['code']);
            $product->setDescription($params['description']);
            $product->setStatus($params['status']);
            $product->setWarrantyTime($params['warrantyTime']);
            
            $result = $product->save();
            if (!$result) {
                throw new Exception("Failed to save product", 400);
            }
        } catch (Exception $e) {
            $response["success"] = false;
            $response["message"] = $e->getMessage();
            $response["code"] = $e->getCode() ?: 400;
        } finally {
            echo json_encode($response);
        }
    break;
    case 'delete':
    break;
    case 'load':
        try {
            $response = [
                "success" => true,
                "code" => 200
            ];

            if (!isset($_POST['params'])) {
                throw new Exception("Params are required", 400);
            }
    
            $params = json_decode($_POST['params'], true);
            if (!isset($params['id'])) {
                throw new Exception("Product id is required", 400);
            }
    
            $product = new Product();
            $response["product"] = $product->load($params['id'])->toArray();
        } catch (Exception $e) {
            $response["success"] = false;
            $response["message"] = $e->getMessage();
            $response["code"] = $e->getCode() ?: 400;
        } finally {
            echo json_encode($response);
        }
    break;
    case 'default':
        throw new Exception('Action is required', 400);
    break;
}

function validateProductData($data)
{
    if (!isset($data['code'])) {
        throw new Exception ("Code is required", 400);
    }
    if (!isset($data['description'])) {
        throw new Exception ("Description is required", 400);
    }
    if (!isset($data['status'])) {
        throw new Exception ("Status is required", 400);
    }
    if (!isset($data['warrantyTime'])) {
        throw new Exception ("Warranty Time is required", 400);
    }

    return true;
}