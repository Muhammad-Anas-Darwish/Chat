<?php

namespace App\Repositories\ChatMessage;

use App\Enum\MessageStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface ChatMessageRepositoryInterface
{
    public function changeMessagesStatus(int $user1Id, int $user2Id, MessageStatusEnum $status): void;

    public function create(array $data): ?Model;

    public function getMessages(int $user1Id, int $user2Id): LengthAwarePaginator;

    public function getNumberOfUnreadChatMessages(int $user1Id, int $user2Id): int;
}
