<?php
namespace Src\Services;

use Src\Repositories\UserRepository;
use Src\Models\User;
use Firebase\JWT\JWT;

class AuthService {
    private UserRepository $users;

    public function __construct() {
        $this->users = new UserRepository();
    }

    /**
     * Registra um novo usuário.
     *
     * @param array $data Dados de registro (username, email, password)
     * @return User
     * @throws \InvalidArgumentException Se faltar algum campo
     */
    public function register(array $data): User {
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            throw new \InvalidArgumentException('Username, email e senha são obrigatórios.');
        }

        $user = new User([
            'username'      => $data['username'],
            'email'         => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);

        return $this->users->create($user);
    }

    /**
     * Autentica um usuário e retorna um token JWT.
     *
     * @param string $email
     * @param string $password
     * @return string JWT token
     * @throws \RuntimeException Se credenciais inválidas
     */
    public function login(string $email, string $password): string {
        $user = $this->users->findByEmail($email);
        if (!$user) {
            throw new \RuntimeException('Usuário não encontrado.');
        }

        if (!password_verify($password, $user->getPasswordHash())) {
            throw new \RuntimeException('Credenciais inválidas.');
        }

        $now   = time();
        $exp   = $now + 3600;
        $payload = [
            'sub' => $user->getId(),
            'iat' => $now,
            'exp' => $exp,
        ];

        $secret = getenv('JWT_SECRET');
        if (!$secret) {
            throw new \RuntimeException('JWT secret não configurado.');
        }

        return JWT::encode($payload, $secret, 'HS256');
    }
}