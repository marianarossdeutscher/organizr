<?php
namespace Src\Controllers;

use Src\Services\UserService;

class UserController {
    private UserService $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    /**
     * Lista todos os usuários.
     */
    public function index(): void {
        header('Content-Type: application/json');
        echo json_encode($this->service->list());
    }

    /**
     * Lista os dados de um usuário pelo id.
     */
    public function show(int $id): void {
        header('Content-Type: application/json');
        echo json_encode($this->service->getUserById($id));
    }

    /**
     * Cria um novo usuário.
     */
    public function create(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        try {
            $user = $this->service->create($data);
            http_response_code(201);
            echo json_encode($user);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Atualiza os dados de um usuário existente.
     *
     * @param int $id
     */
    public function update(int $id)
    {
        $raw = file_get_contents('php://input');

        $data = json_decode($raw, true);

        if (!is_array($data)) {
            parse_str($raw, $data);
        }

        $dto = [];

        if (isset($data['username'])) {
            $dto['username'] = $data['username'];
        }

        if (isset($data['email'])) {
            $dto['email'] = $data['email'];
        }

        if (isset($data['password_hash'])) {
            $dto['password_hash'] = $data['password_hash'];
        }

        $updated = $this->service->update($id, $dto);

        echo json_encode($updated);
    }

    /**
     * Exclui um usuário pelo ID.
     *
     * @param int $id
     * @return bool
     * @throws \RuntimeException Se usuário não for encontrado
     */
    public function delete(int $id): void 
    {
        header('Content-Type: application/json');

        try {
            $deleted = $this->service->delete($id);
            if ($deleted) {
                http_response_code(204);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Usuário não encontrado.']);
            }
        } catch (\RuntimeException $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}