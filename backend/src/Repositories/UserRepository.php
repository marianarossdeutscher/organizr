<?php
namespace Src\Repositories;

use Src\Config\Database;
use Src\Models\User;
use PDO;

class UserRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(User $user): User {
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password_hash) VALUES (:u, :e, :p) RETURNING *'
        );
        $stmt->execute([
            ':u' => $user->username,
            ':e' => $user->email,
            ':p' => $user->passwordHash
        ]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return new User($data);
    }

    // TODO: add findByEmail, findById, update, delete...
}