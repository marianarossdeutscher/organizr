<?php

namespace Src\Repositories;

use PDO;

class TaskShareRepository {
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Busca uma tarefa pelo ID e devolve também a lista de compartilhamento
     */
    public function findByTaskId(int $taskId): array
    {
        $stmt = $this->db->prepare(
            'SELECT usr.userid AS id, usr.email
                FROM users usr
                INNER JOIN task_user tusr ON usr.userid = tusr.userid
            WHERE tusr.taskid = :taskid'
        );
        $stmt->execute([':taskid' => $taskId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function share(int $taskId, int $userId): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO task_user (taskid, userid)
             VALUES (:taskid, :userid)
             ON CONFLICT DO NOTHING'
        );
        $stmt->execute([
            ':taskid' => $taskId,
            ':userid' => $userId,
        ]);
    }

    public function unshareNotIn(int $taskId, array $userIds): void
    {
        if (empty($userIds)) {
            $sql = 'DELETE FROM task_user WHERE taskid = ?';
            $params = [$taskId];
        } else {
            $placeholders = implode(',', array_fill(0, count($userIds), '?'));
            $sql = "DELETE FROM task_user WHERE taskid = ? AND userid NOT IN ($placeholders)";
            $params = array_merge([$taskId], $userIds);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }
}