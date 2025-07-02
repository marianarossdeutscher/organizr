<?php
namespace Src\Models;

class Status {
    public ?int $id;
    public string $name;

    public function __construct(array $data = []) {
        $this->id   = $data['taskid'] ?? null;
        $this->name = $data['name'] ?? '';
    }
}