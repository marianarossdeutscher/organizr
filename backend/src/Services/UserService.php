<?php
namespace Src\Services;

use Src\Repositories\UserRepository;
use Src\Models\User;

class UserService {
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    /**
     * Retorna todos os usuários.
     *
     * @return User[]
     */
    public function list(): array 
    {
        return $this->users->all();
    }

    /**
     * Retorna os dados de um usuário.
     *
     * @return User
     */
    public function getUserById(int $id): User 
    {
        return $this->users->findById($id);
    }

    /**
     * Cria um novo usuário.
     *
     * @param array $data Dados para criação do usuário.
     * @return User
     * @throws \InvalidArgumentException Se dados inválidos.
     */
    public function create(array $data): User
    {
        if (empty($data['username']) || empty($data['email']) || empty($data['password_hash'])) {
            throw new \InvalidArgumentException('Nome de usuário, e-mail e senha são obrigatórios.');
        }

        $user = new User($data);

        return $this->users->create($user);
    }

    /**
     * Atualiza um usuário existente.
     *
     * @param int   $id ID do usuário.
     * @param array $data Dados para atualização.
     * @return User
     * @throws \RuntimeException Se o usuário não for encontrado.
     */
    public function update(int $id, array $data): User
    {
        $existing = $this->users->findById($id);

        if (!$existing) {
            throw new \RuntimeException("Usuário com ID {$id} não encontrado.");
        }

        foreach ([
            'username',
            'email',
            'password_hash'
        ] as $field) {
            if (array_key_exists($field, $data)) {
                $existing->{$field} = $data[$field];
            }
        }

        return $this->users->update($existing);
    }

    /**
     * Remove um usuário pelo ID.
     *
     * @param int $id ID do usuário.
     * @return bool
     * @throws \RuntimeException Se o usuário não for encontrado.
     */
    public function delete(int $id): bool
    {
        $existing = $this->users->findById($id);
        if (!$existing) {
            throw new \RuntimeException("Usuário com ID {$id} não encontrado.");
        }

        return $this->users->delete($id);
    }
}
