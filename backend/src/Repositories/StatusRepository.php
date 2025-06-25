<?php
namespace Src\Repositories;

use Src\Config\Database;
use Src\Models\Status;
use PDO;

class StatusRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function all(): array {
        $stmt = $this->db->query('SELECT * FROM task_status');
        return array_map(fn($r) => new Status($r), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function create(Status $status): Status {
        $stmt = $this->db->prepare(
            'INSERT INTO task_status (name) VALUES (:n) RETURNING *'
        );
        $stmt->execute([':n' => $status->name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Status($row);
    }

    public function update(int $id, array $data): ?Status {
        $stmt = $this->db->prepare(
            'UPDATE task_status SET name = :n WHERE id = :id RETURNING *'
        );
        $stmt->execute([':n' => $data['name'], ':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Status($row) : null;
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM task_status WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
