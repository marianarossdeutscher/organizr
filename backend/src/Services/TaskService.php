<?php
namespace Src\Services;

use Src\Repositories\TaskRepository;
use Src\Models\Task;
use InvalidArgumentException;
use RuntimeException;

class TaskService {
    private TaskRepository $tasks;

    public function __construct()
    {
        $this->tasks = new TaskRepository();
    }

    public function getTaskById(int $id, int $userId): Task 
    {
        return $this->tasks->findByIdAndUserId($id, $userId);
    }

    public function listByUser(int $userId): array
    {
        return $this->tasks->findAllByUserId($userId);
    }

    public function create(array $data, int $userId): Task
    {
        if (empty($data['title'])) {
            throw new InvalidArgumentException('O título é obrigatório.');
        }

        if (isset($data['endDate']) && empty($data['endDate'])) {
            $data['endDate'] = null;
        }

        $data['userid'] = $userId;

        $task = new Task($data);
        return $this->tasks->create($task);
    }

    public function update(int $id, array $data, int $userId): Task
    {
        $existing = $this->tasks->findByIdAndUserId($id, $userId);

        if (!$existing) {
            throw new RuntimeException("Tarefa com ID {$id} não encontrada ou não pertence a este utilizador.");
        }
        
        $fieldMapping = [
            'title' => 'title', 'description' => 'description', 'endDate' => 'endDate',
            'priority' => 'priority', 'status' => 'status'
        ];

        foreach ($fieldMapping as $jsonKey => $modelProperty) {
            if (array_key_exists($jsonKey, $data)) {
                if ($jsonKey === 'endDate' && empty($data[$jsonKey])) {
                    $existing->{$modelProperty} = null;
                } else {
                    $existing->{$modelProperty} = $data[$jsonKey];
                }
            }
        }

        return $this->tasks->update($existing, $userId);
    }

    public function delete(int $id, int $userId): bool
    {
        $existing = $this->tasks->findByIdAndUserId($id, $userId);
        if (!$existing) {
            throw new RuntimeException("Tarefa com ID {$id} não encontrada ou não pertence a este utilizador.");
        }

        return $this->tasks->delete($id);
    }
}