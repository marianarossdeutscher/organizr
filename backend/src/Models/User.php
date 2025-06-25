<?php
namespace Src\Models;

class User {
    public ?int $id;
    public string $username;
    public string $email;
    public string $passwordHash;

    public function __construct(array $data = []) {
        $this->id           = $data['id'] ?? null;
        $this->username     = $data['username'] ?? '';
        $this->email        = $data['email'] ?? '';
        $this->passwordHash = $data['password_hash'] ?? '';
    }
}