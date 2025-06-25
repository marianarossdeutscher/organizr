<?php
namespace Src\Models;

class Task {
    public ?int $id;
    public string $title;
    public string $description;
    public string $beginDate;
    public string $endDate;
    public int $priority;
    public string $status;

    public function __construct(array $data = []) {
        $this->id          = $data['id'] ?? null;
        $this->title       = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->beginDate   = $data['begin_date'] ?? '';
        $this->endDate     = $data['end_date'] ?? '';
        $this->priority    = $data['priority'] ?? 0;
        $this->status      = $data['status'] ?? '';
    }
}