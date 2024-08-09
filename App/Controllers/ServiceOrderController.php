<?php

require_once '../../autoload.php';

use App\Models\Product;
use App\Models\ServiceOrder;


try {
    if (!isset($_POST['params'])) {
        throw new Exception("Params are required", 400);
    }
    
    if (!isset($_POST['action'])) {
        throw new Exception("Action is required", 400);
    }

    $params = json_decode($_POST['params'], true);
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

            validateServiceOrderData($params);
            $serviceOrder = new ServiceOrder();
            if (isset($params['serviceOrderId'])) {
                $serviceOrder->setId($params['serviceOrderId']);
            }
            $serviceOrder->setOrderNumber($params['orderNumber']);
            $serviceOrder->setOpeningDate($params['openingDate']);
            $serviceOrder->setConsumerName($params['consumerName']);
            $serviceOrder->setConsumerCpf($params['consumerCpf']);
            if (!isset($params['products'])) {
                throw new Exception ("Product is required", 400);
            }
            foreach ($params['products'] as $productId) {
                $product = new Product();
                $product->setId($productId);
                $serviceOrder->addProduct($product);
            }
            $serviceOrder->save();
        } catch (Exception $e) {
            $response["success"] = false;
            $response["message"] = $e->getMessage();
            $response["code"] = $e->getCode() ?: 400;
        } finally {
            echo json_encode($response);
        }
    break;
    case 'delete':
        try {
            $response = [
                "success" => true,
                "code" => 200
            ];

            if (!isset($_POST['params'])) {
                throw new Exception("Params are required", 400);
            }
    
            $params = json_decode($_POST['params'], true);
            if (!isset($params['serviceOrderId'])) {
                throw new Exception("Service Order id is required", 400);
            }

            $serviceOrder = new ServiceOrder();
            $result = $serviceOrder->delete($params['serviceOrderId']);
    
            if (!$result) {
                throw new Exception("Failed to delete service order", 400);
            }
        } catch (Exception $e) {
            $response["success"] = false;
            $response["message"] = $e->getMessage();
            $response["code"] = $e->getCode() ?: 400;
        } finally {
            echo json_encode($response);
        }
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
                throw new Exception("Service Order id is required", 400);
            }
    
            $serviceOrder = new ServiceOrder();
            $response["service_order"] = $serviceOrder->load($params['id'])->toArray();
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

function validateServiceOrderData($data)
{
    if (!isset($data['orderNumber'])) {
        throw new Exception ("Order Number is required", 400);
    }
    if (!isset($data['openingDate'])) {
        throw new Exception ("Opening Date is required", 400);
    }
    if (!isset($data['consumerName'])) {
        throw new Exception ("Consumer Name is required", 400);
    }
    if (!isset($data['consumerCpf'])) {
        throw new Exception ("Consumer Cpf is required", 400);
    }

    if (!isset($data['products']) || count($data['products']) == 0) {
        throw new Exception ("Product id is required", 400);
    }

    return true;
}