<?php
namespace Src\Controllers;

use Src\Services\TaskService;
use Src\Services\TaskShareService;
use Src\Repositories\TaskShareRepository;
use Src\Repositories\UserRepository;
use Src\Config\Database;

class TaskController {
    private TaskService $service;
    private TaskShareService $shareService;

    public function __construct()
    {
        $this->service = new TaskService();

        $pdo = Database::getConnection();
        $this->shareService = new TaskShareService(
            new TaskShareRepository($pdo),
            new UserRepository($pdo)
        );
    }

    /**
     * Lista todas as tarefas.
     */
    public function index(): void
    {
        header('Content-Type: application/json');
        $tasks = $this->service->list();
        $response = [];

        foreach ($tasks as $task) {
            $response[] = [
                'id'          => $task->getId(),
                'title'       => $task->getTitle(),
                'description' => $task->getDescription(),
                'endDate'     => $task->getEndDate(),
                'priority'    => $task->getPriority(),
                'status'      => $task->getStatus(),
                'shared_with' => $this->shareService->getSharedWith($task->getId()),
            ];
        }

        echo json_encode($response);
    }

    /**
     * Lista os dados de uma tarefa pelo id.
     */
    public function show(int $id): void
    {
        header('Content-Type: application/json');
        try {
            $task = $this->service->getTaskById($id);

            $shared = $this->shareService->getSharedWith($id);

            $out = [
                'id'          => $task->getId(),
                'title'       => $task->getTitle(),
                'description' => $task->getDescription(),
                'endDate'     => $task->getEndDate(),
                'priority'    => $task->getPriority(),
                'status'      => $task->getStatus(),
                'shared_with' => $shared,
            ];

            echo json_encode($out);
        } catch (\RuntimeException $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Cria uma nova tarefa e compartilha com usuários.
     */
    public function create(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $shared = $data['shared_with'] ?? [];
        unset($data['shared_with']);

        try {
            $task = $this->service->create($data);

            $this->shareService->shareUsers($task->id, $shared);

            http_response_code(201);
            echo json_encode($task);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Atualiza os dados da tarefa e seus compartilhamentos.
     */
    public function update(int $id): void
    {
        header('Content-Type: application/json');
        $raw  = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            parse_str($raw, $data);
        }

        $shared = $data['shared_with'] ?? [];
        unset($data['shared_with']);

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

        try {
            $task = $this->service->update($id, $dto);
            $this->shareService->shareUsers($id, $shared);

            $out = [
                'id'          => $task->getId(),
                'title'       => $task->getTitle(),
                'description' => $task->getDescription(),
                'endDate'     => $task->getEndDate(),
                'priority'    => $task->getPriority(),
                'status'      => $task->getStatus(),
                'shared_with' => $this->shareService->getSharedWith($id),
            ];

            echo json_encode($out);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove uma tarefa pelo ID.
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