<?php

namespace App\Repositories\ChatMessage;

use App\Models\ChatMessage;
use App\Http\Requests\StoreChatMessageRequest;

interface ChatMessageRepositoryInterface
{
    public function create(array $request);

    public function getMessages($user1Id, $user2Id);

    public function getNumberOfUnreadChatMessages(int $user1Id, int $user2Id);
}
