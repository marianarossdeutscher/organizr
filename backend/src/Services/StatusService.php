<?php
namespace Src\Services;

use Src\Repositories\StatusRepository;
use Src\Models\Status;

class StatusService {
    private StatusRepository $statuses;

    public function __construct() {
        $this->statuses = new StatusRepository();
    }

    public function list(): array {
        return $this->statuses->all();
    }

    public function create(array $data): Status {
        $status = new Status(['name' => $data['name']]);
        return $this->statuses->create($status);
    }

    public function update(int $id, array $data): ?Status {
        return $this->statuses->update($id, $data);
    }

    public function delete(int $id): bool {
        return $this->statuses->delete($id);
    }
}