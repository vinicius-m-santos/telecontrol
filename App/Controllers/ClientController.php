<?php

require_once '../../autoload.php';

use App\Models\Client;
use App\Models\ServiceOrder;

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
            
            validateClientData($params);
            $client = new Client();

            if (isset($params['clientId'])) {
                $client->setId($params['clientId']);
            }
            $client->setName($params['name']);
            if (!validateCPF($params['cpf'])) {
                throw new Exception("Invalid cpf", 400);
            }
            $client->setCpf($params['cpf']);
            $client->setAddress($params['address']);
            
            $result = $client->save();
            if (!$result) {
                throw new Exception("Failed to save client", 400);
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
        try {
            $response = [
                "success" => true,
                "code" => 200
            ];

            if (!isset($_POST['params'])) {
                throw new Exception("Params are required", 400);
            }
    
            $params = json_decode($_POST['params'], true);
            if (!isset($params['clientId'])) {
                throw new Exception("Client id is required", 400);
            }

            $serviceOrder = new ServiceOrder();
            $result = $serviceOrder->findByClientId($params['clientId']);
            if ($result && count($result) > 0) {
                throw new Exception("Client has service orders", 400);
            }
    
            $client = new Client();
            $result = $client->delete($params['clientId']);
            if (!$result) {
                throw new Exception("Failed to delete client", 400);
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
                throw new Exception("Client id is required", 400);
            }
    
            $client = new Client();
            $response["client"] = $client->load($params['id'])->toArray();
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

function validateClientData($data)
{
    if (!isset($data['name'])) {
        throw new Exception ("Name is required", 400);
    }
    if (!isset($data['cpf'])) {
        throw new Exception ("Cpf is required", 400);
    }
    if (!isset($data['address'])) {
        throw new Exception ("Address is required", 400);
    }

    if ($clientId = checkDuplicateClient($data['cpf'])) {
        if (isset($data['clientId']) && $clientId == $data['clientId']) {
            return true;
        }
        throw new Exception("Client already exists", 400);
    };

    return true;
}

function validateCPF($cpf)
{
    $cpf = preg_replace('/\D/', '', $cpf);
    
    if (strlen($cpf) != 11) {
        return false;
    }

    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }

    return true;
}

function checkDuplicateClient($cpf)
{
    $client = new Client();
    $result = $client->findByCpf($cpf);
    if (count($result) > 0) {
        return $result['id'];
    } else {
        return false;
    }
}