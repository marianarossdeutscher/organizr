<?php
namespace Src\Controllers;

use Src\Services\TaskService;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TaskController {
    private TaskService $service;
    private int $userId;

    public function __construct()
    {
        $this->service = new TaskService();
        $this->authenticate();
    }

    /**
     * Verifica o token JWT e extrai o ID do utilizador.
     * Este método é o "porteiro" da sua API de tarefas.
     */
    private function authenticate(): void
    {
        try {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

            if (!$authHeader) {
                throw new Exception("Token de autorização não encontrado.");
            }

            list($jwt) = sscanf($authHeader, 'Bearer %s');

            if (!$jwt) {
                throw new Exception("Formato de token inválido.");
            }

            $secret = $_ENV['JWT_SECRET'] ?? 'your_default_secret';
            $decoded = JWT::decode($jwt, new Key($secret, 'HS256'));
            
            $this->userId = $decoded->sub;
        } catch (Exception $e) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Acesso não autorizado: ' . $e->getMessage()]);

            exit();
        }
    }

    /**
     * Lista apenas as tarefas do utilizador autenticado.
     */
    public function index(): void
    {
        header('Content-Type: application/json');
        $tasks = $this->service->listByUser($this->userId); 
        echo json_encode($tasks);
    }

    /**
     * Lista os dados de um tarefa pelo id.
     */
    public function show(int $id): void {
        header('Content-Type: application/json');
        echo json_encode($this->service->getTaskById($id, $this->userId));
    }

    /**
     * Cria uma tarefa para o utilizador autenticado.
     */
    public function create(): void
    {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
            $task = $this->service->create($data, $this->userId); 
            http_response_code(201);
            echo json_encode($task);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Atualiza uma tarefa, garantindo que ela pertence ao utilizador autenticado.
     */
    public function update(int $id): void
    {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
            $task = $this->service->update($id, $data, $this->userId);
            echo json_encode($task);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Apaga uma tarefa, garantindo que ela pertence ao utilizador autenticado.
     */
    public function delete(int $id): void
    {
        header('Content-Type: application/json');

        try {
            $this->service->delete($id, $this->userId);
            http_response_code(204);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}