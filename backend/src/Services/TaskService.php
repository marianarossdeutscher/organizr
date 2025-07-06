<?php
namespace Src\Services;

use Src\Repositories\TaskRepository;
use Src\Models\Task;

class TaskService {
    private TaskRepository $tasks;

    public function __construct()
    {
        $this->tasks = new TaskRepository();
    }

    /**
     * Retorna todas as tarefas.
     *
     * @return Task[]
     */
    public function list(): array
    {
        return $this->tasks->all();
    }

    /**
     * Retorna os dados de uma tarefa.
     *
     * @return User
     */
    public function getTaskById(int $id): Task 
    {
        return $this->tasks->findById($id);
    }

    /**
     * Cria uma nova tarefa.
     *
     * @param array $data Dados para criação da tarefa.
     * @return Task
     * @throws \InvalidArgumentException Se dados inválidos.
     */
    public function create(array $data): Task
    {
        if (empty($data['title']) || empty($data['description'])) {
            throw new \InvalidArgumentException('Título e descrição são obrigatórios.');
        }

        $task = new Task($data);

        return $this->tasks->create($task);
    }

    /**
     * Atualiza uma tarefa existente.
     *
     * @param int   $id ID da tarefa.
     * @param array $data Dados para atualização.
     * @return Task
     */
    public function update(int $id, array $data): Task
    {
        $existing = $this->tasks->findById($id);

        if (!$existing) {
            throw new \RuntimeException("Tarefa com ID {$id} não encontrada.");
        }

        foreach ([
            'title',
            'description',
            'end_date',
            'priority',
            'status'
        ] as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null) {
                $existing->{$field} = $data[$field];
            }
        }

        return $this->tasks->update($existing);
    }

    /**
     * Remove uma tarefa pelo ID.
     *
     * @param int $id ID da tarefa.
     * @return bool
     * @throws \RuntimeException Se tarefa não for encontrada.
     */
    public function delete(int $id): bool
    {
        $existing = $this->tasks->findById($id);
        if (!$existing) {
            throw new \RuntimeException("Tarefa com ID {$id} não encontrada.");
        }

        return $this->tasks->delete($id);
    }
}
