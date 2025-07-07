<?php
namespace Src\Models;

class Task {
    public ?int $id;
    public int $userid;
    public string $title;
    public ?string $description;
    public ?string $endDate;
    public ?int $priority;
    public string $status;

    public function __construct(array $data = [])
    {
        $this->id          = $data['taskid'] ?? null;
        $this->userid      = $data['userid'] ?? 0;
        $this->title       = $data['title'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->endDate     = $data['end_date'] ?? $data['endDate'] ?? null;
        $this->priority    = $data['priority'] ?? null;
        $this->status      = $data['status'] ?? 'To do';
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}