<?php
namespace Src\Models;

class TaskUser {
    public ?int $id;
    public int $taskId;
    public int $userId;

    public function __construct(array $data = []) {
        $this->id     = $data['id'] ?? null;
        $this->taskId = $data['task_id'] ?? 0;
        $this->userId = $data['user_id'] ?? 0;
    }
}