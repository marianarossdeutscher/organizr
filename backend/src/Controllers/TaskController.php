<?php
namespace Src\Controllers;

use Src\Services\TaskService;

class TaskController {
    private TaskService $service;

    public function __construct() {
        $this->service = new TaskService();
    }

    /**
     * Lista todas as tarefas.
     */
    public function index(): void {
        header('Content-Type: application/json');
        echo json_encode($this->service->list());
    }

    /**
     * Cria uma nova tarefa.
     */
    public function create(): void {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        try {
            $task = $this->service->create($data);
            http_response_code(201);
            echo json_encode($task);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Atualiza uma tarefa existente.
     *
     * @param int $id
     */
    public function update(int $id): void {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        try {
            $task = $this->service->update($id, $data);
            echo json_encode($task);
        } catch (\RuntimeException $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove uma tarefa pelo ID.
     *
     * @param int $id
     */
    public function delete(int $id): void {
        header('Content-Type: application/json');
        try {
            $deleted = $this->service->delete($id);
            if ($deleted) {
                http_response_code(204);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Tarefa não encontrada.']);
            }
        } catch (\RuntimeException $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}