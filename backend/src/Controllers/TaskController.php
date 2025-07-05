<?php
namespace Src\Controllers;

use Src\Services\TaskService;

class TaskController {
    private TaskService $service;

    public function __construct()
    {
        $this->service = new TaskService();
    }

    /**
     * Lista todas as tarefas.
     */
    public function index(): void
    {
        header('Content-Type: application/json');
        echo json_encode($this->service->list());
    }

    /**
     * Lista os dados de uma tarefa pelo id.
     */
    public function show(int $id): void
    {
        header('Content-Type: application/json');
        echo json_encode($this->service->getUserById($id));
    }

    /**
     * Cria uma nova tarefa.
     */
    public function create(): void
    {
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
     * Atualiza os dados da tarefa.
     */
    public function update(int $id)
    {
        $raw = file_get_contents('php://input');

        $data = json_decode($raw, true);

        if (!is_array($data)) {
            parse_str($raw, $data);
        }

        $dto = [];

        if (isset($data['title'])) {
            $dto['title'] = $data['title'];
        }

        if (isset($data['description'])) {
            $dto['description'] = $data['description'];
        }

        if (isset($data['end_date'])) {
            $dto['endDate'] = $data['end_date'];
        }

        if (isset($data['priority'])) {
            $dto['priority'] = (int)$data['priority'];
        }

        if (isset($data['status'])) {
            $dto['status'] = $data['status'];
        }

        $updated = $this->service->update($id, $dto);

        echo json_encode($updated);
    }

    /**
     * Remove uma tarefa pelo ID.
     *
     * @param int $id
     */
    public function delete(int $id): void
    {
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