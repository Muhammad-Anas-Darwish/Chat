<?php

namespace App\Services\ChatMessage;

use App\Repositories\ChatMessage\ChatMessageRepository;

class ChatMessageService
{
    protected $chatMessageRepository;

    public function __construct(ChatMessageRepository $chatMessageRepository)
    {
        $this->chatMessageRepository = $chatMessageRepository;
    }

    public function create(array $data, int $userId)
    {
        $data['sender_id'] = $userId;
        $data['status'] = config('choices.message_status')['successfully_sent'];

        return $this->chatMessageRepository->create($data);
    }

    public function getMessages($user1Id, $user2Id)
    {
        return $this->chatMessageRepository->getMessages($user1Id, $user2Id);
    }

    // public function get(array $columns = ['*'])
    // {
    //     return $this->chatMessageRepository->get($columns);
    // }

    public function getNumberOfUnreadChatMessages($user1Id, $user2Id)
    {
        return $this->chatMessageRepository->getNumberOfUnreadChatMessages($user1Id, $user2Id);
    }
}
