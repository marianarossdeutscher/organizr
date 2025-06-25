<?php
namespace Src\Services;

use Src\Repositories\UserRepository;
use Src\Models\User;

class AuthService {
    private UserRepository $users;

    public function __construct() {
        $this->users = new UserRepository();
    }

    public function register(array $data): User {
        $user = new User([
            'username'     => $data['username'],
            'email'        => $data['email'],
            'password_hash'=> password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
        return $this->users->create($user);
    }

    public function login(string $email, string $password): ?string {
        // TODO: fetch user by email, verify password and return JWT or session token
        return null;
    }
}
