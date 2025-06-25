<?php
namespace Src\Services;

use Src\Repositories\TaskRepository;
use Src\Models\Task;

class TaskService {
    private TaskRepository $tasks;

    public function __construct() {
        $this->tasks = new TaskRepository();
    }

    public function list(): array {
        return $this->tasks->all();
    }

    // TODO: create, update, delete methods
}