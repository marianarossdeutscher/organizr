<?php
namespace SrcControllers;

use Src\Services\TaskService;

class TaskController {
    private TaskService $service;

    public function __construct() {
        $this->service = new TaskService();
    }

    public function index(): void {
        echo json_encode($this->service->list());
    }

    // TODO: add create, update, delete actions
}