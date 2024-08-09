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
        validateServiceOrderData($params);
        $serviceOrder = new ServiceOrder();
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
    break;
    case 'delete':
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