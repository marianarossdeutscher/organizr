<?php
namespace Src\Controllers;

use Src\Services\StatusService;

class StatusController {
    private StatusService $service;

    public function __construct() {
        $this->service = new StatusService();
    }

    public function index(): void {
        header('Content-Type: application/json');
        echo json_encode($this->service->list());
    }

    public function create(): void {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        if (empty($input['name'])) {
            http_response_code(422);
            echo json_encode(['error' => 'O campo name é obrigatório']);
            return;
        }
        $status = $this->service->create(['name' => $input['name']]);
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode($status);
    }

    public function update(int $id): void {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        if (empty($input['name'])) {
            http_response_code(422);
            echo json_encode(['error' => 'O campo name é obrigatório']);
            return;
        }
        $updated = $this->service->update($id, ['name' => $input['name']]);
        if (!$updated) {
            http_response_code(404);
            echo json_encode(['error' => 'Status não encontrado']);
            return;
        }
        header('Content-Type: application/json');
        echo json_encode($updated);
    }

    public function delete(int $id): void {
        $deleted = $this->service->delete($id);
        if (!$deleted) {
            http_response_code(404);
            echo json_encode(['error' => 'Status não encontrado']);
            return;
        }
        http_response_code(204);
    }
}