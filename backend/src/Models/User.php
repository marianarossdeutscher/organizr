<?php
namespace Src\Models;

class User {
    public int $id;
    public string $username;
    public string $email;
    public string $passwordHash;

    /**
     * Constrói o modelo a partir de um array associativo.
     * Espera chaves: id, username, email, password_hash (ou passwordHash).
     *
     * @param array $data
     */
    public function __construct(array $data) {
        // Alguns retornos podem usar snake_case
        $this->id = isset($data['id']) ? (int)$data['id'] : 0;
        $this->username = $data['username'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->passwordHash = $data['password_hash'] 
            ?? $data['passwordHash'] 
            ?? '';
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string {
        return $this->passwordHash;
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }
}
