<?php

require_once '../../autoload.php';

use App\Models\Client;


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

    return true;
}