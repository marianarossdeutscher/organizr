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

    /**
     * Persiste um novo usuário e retorna o objeto criado.
     *
     * @param User $user
     * @return User
     */
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

    /**
     * Busca um usuário pelo e-mail.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :e');
        $stmt->execute([':e' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    /**
     * Busca um usuário pelo ID.
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    /**
     * Atualiza os dados de um usuário existente.
     *
     * @param User $user
     * @return User
     */
    public function update(User $user): User {
        $stmt = $this->db->prepare(
            'UPDATE users
             SET username = :u,
                 email = :e,
                 password_hash = :p
             WHERE id = :id
             RETURNING *'
        );
        $stmt->execute([
            ':u'  => $user->username,
            ':e'  => $user->email,
            ':p'  => $user->passwordHash,
            ':id' => $user->id
        ]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return new User($data);
    }

    /**
     * Remove um usuário pelo ID.
     *
     * @param int $id
     * @return bool True se a exclusão ocorrer, false caso contrário.
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}