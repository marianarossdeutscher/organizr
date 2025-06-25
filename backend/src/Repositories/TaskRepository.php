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

    public function all(): array {
        $stmt = $this->db->query('SELECT * FROM task');
        return array_map(fn($row) => new Task($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // TODO: add create, findById, update, delete...
}