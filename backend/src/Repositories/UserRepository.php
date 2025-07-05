<?php
namespace Src\Repositories;

use Src\Config\Database;
use Src\Models\User;
use PDO;

class UserRepository {
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Retorna todos os usuários.
     *
     * @return User[]
     */
    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM users');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn(array $row) => new User($row), $rows);
    }

    /**
     * Persiste um novo usuário e retorna o objeto criado.
     *
     * @param User $user
     * @return User
     */
    public function create(User $user): User
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password_hash) 
            VALUES (:username, :email, :passwordHash) RETURNING *'
        );

        $stmt->execute([
            ':username'      => $user->username,
            ':email'         => $user->email,
            ':passwordHash' => $user->passwordHash
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
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    /**
     * Busca um usuário pelo ID.
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE userid = :userid');
        $stmt->execute([':userid' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    /**
     * Atualiza os dados de um usuário existente.
     *
     * @param User $user
     * @return User
     */
    public function update(User $user): User
    {
        $stmt = $this->db->prepare(
            'UPDATE users
                SET username = :username,
                    email = :email,
                    password_hash = :password_hash
            WHERE userid = :userid
            RETURNING *'
        );

        $stmt->execute([
            ':username'      => $user->username,
            ':email'         => $user->email,
            ':password_hash' => $user->passwordHash,
            ':userid'        => $user->id
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
        $stmt = $this->db->prepare('DELETE FROM users WHERE userid = :userid');
        $stmt->execute([':userid' => $id]);

        return $stmt->rowCount() > 0;
    }
}