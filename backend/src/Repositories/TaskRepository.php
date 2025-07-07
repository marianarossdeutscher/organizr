<?php
namespace Src\Repositories;

use Src\Config\Database;
use Src\Models\Task;
use PDO;

class TaskRepository {
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAllByUserId(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM task WHERE userid = :userid ORDER BY taskid DESC');
        $stmt->execute([':userid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Task::class);
    }

    public function findByIdAndUserId(int $id, int $userId): ?Task
    {
        $stmt = $this->db->prepare('SELECT * FROM task WHERE taskid = :taskid AND userid = :userid');
        $stmt->execute([':taskid' => $id, ':userid' => $userId]);
        $task = $stmt->fetchObject(Task::class);
        return $task ?: null;
    }

    public function create(Task $task): Task
    {
        $stmt = $this->db->prepare(
            'INSERT INTO task (userid, title, description, end_date, priority, status) 
            VALUES (:userid, :title, :description, :end_date, :priority, :status) RETURNING *'
        );

        $stmt->execute([
            ':userid'       => $task->userid,
            ':title'        => $task->title,
            ':description'  => $task->description,
            ':end_date'     => $task->endDate,
            ':priority'     => $task->priority,
            ':status'       => $task->status
        ]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Task($data);
    }

    public function update(Task $task): Task
    {
        $stmt = $this->db->prepare(
            'UPDATE task SET 
                title = :title, description = :description, end_date = :end_date, 
                priority = :priority, status = :status
            WHERE taskid = :taskid AND userid = :userid
            RETURNING *'
        );

        $stmt->execute([
            ':title'       => $task->title,
            ':description' => $task->description,
            ':end_date'    => $task->endDate,
            ':priority'    => $task->priority,
            ':status'      => $task->status,
            ':taskid'      => $task->id,
            ':userid'      => $task->userid
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Task($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM task WHERE taskid = :taskid');
        $stmt->execute([':taskid' => $id]);
        return $stmt->rowCount() > 0;
    }
}