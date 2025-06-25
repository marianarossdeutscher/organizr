<?php
namespace Src\Controllers;

use Src\Services\AuthService;

class UserController {
    private AuthService $service;

    public function __construct() {
        $this->service = new AuthService();
    }

    // TODO: add methods to view/update/delete user
}