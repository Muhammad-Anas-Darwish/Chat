<?php

namespace App\Repositories\ChatMessage;

use App\Repositories\ChatMessage\ChatMessageRepositoryInterface;
use App\Models\ChatMessage;

class ChatMessageRepository implements ChatMessageRepositoryInterface
{
    public function create(array $data)
    {
        $message = ChatMessage::create($data);
        return $message;
    }

    public function getMessages($user1Id, $user2Id)
    {
        return ChatMessage::where(
            function ($query) use ($user1Id, $user2Id) {
                $query->where('sender_id', $user1Id)->where('receiver_id', $user2Id);
            })
            ->orWhere(function ($query) use ($user1Id, $user2Id) {
                $query->where('sender_id', $user2Id)->where('receiver_id', $user1Id);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(30);
    }

    public function getNumberOfUnreadChatMessages($user1Id, $user2Id)
    {
        $count = ChatMessage::where('status', config('choices.message_status')['successfully_sent'])
        ->where(
            function ($query) use ($user1Id, $user2Id) {
                $query->where('sender_id', $user2Id)->where('receiver_id', $user1Id);
        })
        ->count();

        return $count;
    }
}
