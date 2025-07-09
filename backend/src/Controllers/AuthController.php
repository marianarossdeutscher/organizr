<?php
namespace Src\Controllers;

use Src\Services\AuthService;
use Exception;

class AuthController {
    private AuthService $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function register(): void
    {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON inválido.');
            }

            if (empty($input['username']) || empty($input['email']) || empty($input['password'])) {
                 throw new Exception('Nome de utilizador, email e palavra-passe são obrigatórios.');
            }
            
            $user = $this->service->register($input);

            http_response_code(201);
            echo json_encode($user);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function login(): void
    {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON inválido.');
            }
            if (!isset($input['email']) || !isset($input['password'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Email e senha são obrigatórios.']);
                return;
            }
            $loginModel = $this->service->login($input['email'], $input['password']);
            http_response_code(200);
            echo json_encode($loginModel);
        } catch (Exception $e) {
            $statusCode = $e->getMessage() === 'Credenciais inválidas.' || $e->getMessage() === 'Usuário não encontrado.' ? 401 : 500;
            http_response_code($statusCode);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }
}