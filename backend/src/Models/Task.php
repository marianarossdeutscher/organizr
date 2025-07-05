<?php
namespace Src\Models;
date_default_timezone_set('America/Sao_Paulo');

class Task {
    public ?int $id;
    public string $title;
    public string $description;
    public string $endDate;
    public int $priority;
    public string $status;

    public function __construct(array $data = [])
    {
        $this->id           = $data['taskid'] ?? null;
        $this->title        = $data['title'] ?? '';
        $this->description  = $data['description'] ?? '';
        $this->endDate      = $data['end_date'] ?? '';
        $this->priority     = $data['priority'] ?? 0;
        $this->status       = $data['status'] ?? '';
    }
}