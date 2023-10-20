<?php

namespace App\Services\ChatMessage;

use App\Models\ChatMessage;

interface ChatMessageServiceInterface
{
    public function create(array $data, int $sender_id);

    public function getMessages($user1Id, $user2Id);

    public function getNumberOfUnreadChatMessages(int $user1Id, int $user2Id);
}
