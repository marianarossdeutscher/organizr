<?php
namespace Src\Controllers;

use Src\Services\AuthService;
use Src\Repositories\UserRepository;
use Src\Models\User;

class UserController {
    private AuthService $authService;
    private UserRepository $userRepo;

    public function __construct() {
        $this->authService = new AuthService();
        $this->userRepo    = new UserRepository();
    }

    /**
     * Exibe os dados de um usuário.
     *
     * @param int $id
     * @return User
     * @throws \RuntimeException Se usuário não for encontrado
     */
    public function show(int $id): User {
        $user = $this->userRepo->findById($id);
        if (!$user) {
            throw new \RuntimeException("Usuário com ID {$id} não encontrado.");
        }
        return $user;
    }

    /**
     * Atualiza dados de um usuário existente.
     *
     * @param int   $id
     * @param array $data
     * @return User
     * @throws \RuntimeException Se usuário não for encontrado
     * @throws \InvalidArgumentException Se dados inválidos
     */
    public function update(int $id, array $data): User {
        $user = $this->userRepo->findById($id);
        if (!$user) {
            throw new \RuntimeException("Usuário com ID {$id} não encontrado.");
        }

        if (isset($data['username'])) {
            $user->username = $data['username'];
        }
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
        if (isset($data['password'])) {
            $user->passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->userRepo->update($user);
    }

    /**
     * Exclui um usuário pelo ID.
     *
     * @param int $id
     * @return bool
     * @throws \RuntimeException Se usuário não for encontrado
     */
    public function delete(int $id): bool {
        $user = $this->userRepo->findById($id);
        if (!$user) {
            throw new \RuntimeException("Usuário com ID {$id} não encontrado.");
        }
        return $this->userRepo->delete($id);
    }
}