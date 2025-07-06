<?php
namespace Src\Controllers;

use Src\Services\AuthService;

class AuthController {
    private AuthService $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function register(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $user  = $this->service->register($input);
        echo json_encode($user);
    }

    public function login(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $token = $this->service->login($input['email'], $input['password']);
        echo json_encode(['token' => $token]);
    }
}