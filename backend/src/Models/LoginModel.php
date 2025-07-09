<?php
namespace Src\Models;

class LoginModel {
    public User $user;
    public string $token;

    public function __construct(User $user, string $token) {
        $this->user = $user;
        $this->token = $token;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function getToken(): string {
        return $this->token;
    }

    public function jsonSerialize(): array
    {
        return [
            'token' => $this->getToken(),
            'user'  => $this->getUser()
        ];
    }
}