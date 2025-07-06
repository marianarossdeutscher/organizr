<?php
namespace Src\Models;

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

    /**
     * Retorna o id da tarefa
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Retorna o título da tarefa
     * @return string
    */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Retorna a descrição da tarefa
     * @return string
    */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Retorna o prazo final da tarefa
     * @return string|null
    */
    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    /**
     * Retorna a prioridade da tarefa
     * @return int
    */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Retorna o status da tarefa
     * @return string
    */
    public function getStatus(): string
    {
        return $this->status;
    }
}