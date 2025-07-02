<?php
namespace Src\Repositories;

use Src\Config\Database;
use Src\Models\Task;
use PDO;

class TaskRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Retorna todas as tarefas.
     *
     * @return Task[]
     */
    public function all(): array {
        $stmt = $this->db->query('SELECT * FROM task');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn(array $row) => new Task($row), $rows);
    }

    /**
     * Persiste uma nova tarefa e retorna o objeto criado.
     *
     * @param Task $task
     * @return Task
     */
    public function create(Task $task): Task {
        $stmt = $this->db->prepare(
            'INSERT INTO task (title, description, end_date) VALUES (:title, :description, :end_date) RETURNING *'
        );
        $stmt->execute([
            ':title'   => $task->title,
            ':description'   => $task->description,
            ':end_date' => $task->endDate
        ]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Task($data);
    }

    /**
     * Busca uma tarefa pelo ID.
     *
     * @param int $id
     * @return Task|null
     */
    public function findById(int $id): ?Task {
        $stmt = $this->db->prepare('SELECT * FROM task WHERE taskid = :taskid');
        $stmt->execute([':taskid' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Task($data) : null;
    }

    /**
     * Atualiza uma tarefa existente.
     *
     * @param Task $task
     * @return Task
     */
    public function update(Task $task): Task {
        $stmt = $this->db->prepare(
            'UPDATE task
             SET title = :title,
                description = :description,
                end_date = :end_date
             WHERE taskid = :taskid
             RETURNING *'
        );
        $stmt->execute([
            ':title'   => $task->title,
            ':description'   => $task->description,
            ':end_date' => $task->endDate,
            ':taskid'  => $task->id
        ]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Task($data);
    }

    /**
     * Remove uma tarefa pelo ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM task WHERE taskid = :taskid');
        $stmt->execute([':taskid' => $id]);
        return $stmt->rowCount() > 0;
    }
}