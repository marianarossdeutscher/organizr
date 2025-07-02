<?php
// public/api.php

// 1) carrega o autoloader do Composer e suas config/env
require __DIR__ . '\vendor\autoload.php';

// 2) pega método e parâmetros da request
$method = $_SERVER['REQUEST_METHOD'];
$id     = $_GET['id'] ?? null;

// 3) instancia o controller
use Src\Controllers\TaskController;
$ctrl = new TaskController();

// 4) roteia para o método certo
header('Content-Type: application/json');
switch ($method) {
    case 'GET':
        // se tiver id, pode criar show(); mas aqui lista todas
        $ctrl->index();
        break;

    case 'POST':
        $ctrl->create();
        break;

    case 'PUT':
        // php puro não popula $_PUT, então lemos o input raw
        parse_str(file_get_contents('php://input'), $_PUT);
        // reembaralhe isso num array como seu controller espera
        $ctrl->update((int)$id);
        break;

    case 'DELETE':
        $ctrl->delete((int)$id);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error'=>'Método não permitido']);
        break;
}
