<?php
namespace Src\Services;

use Src\Repositories\TaskShareRepository;
use Src\Repositories\UserRepository;

class TaskShareService {
    private TaskShareRepository $shareRepo;
    private UserRepository $userRepo;

    public function __construct(
        TaskShareRepository $shareRepo,
        UserRepository $userRepo
    ) {
        $this->shareRepo = $shareRepo;
        $this->userRepo  = $userRepo;
    }

    /**
     * Compartilha uma tarefa com usuários (array de e‑mails).
     */
    public function shareUsers(int $taskId, array $emails): void
    {
        $userIds = [];

        foreach ($emails as $email) {
            $user = $this->userRepo->findByEmail($email);

            if ($user) {
                $userIds[] = $user->getId();
                $this->shareRepo->share($taskId, $user->getId());
            }
        }

        $this->shareRepo->unshareNotIn($taskId, $userIds);
    }

    /**
     * Exibe a lista de emails compartilhados
     */
    public function getSharedWith(int $taskId): array
    {
        $rows = $this->shareRepo->findByTaskId($taskId);

        return array_column($rows, 'email');
    }
}
