<?php
namespace Src\Models;

class User {
    public int $id;
    public string $username;
    public string $email;
    public string $passwordHash;

    public function __construct(array $data) 
    {
        $this->id = isset($data['userid']) ? (int) $data['userid'] : 0;
        $this->username = $data['username'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->passwordHash = $data['password_hash'] 
            ?? $data['passwordHash'] 
            ?? '';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
