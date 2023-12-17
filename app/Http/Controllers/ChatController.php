<?php

namespace App\Http\Controllers;

use App\Enum\MessageStatusEnum;
use App\Events\NewChatMessage;
use App\Events\ReadMessage;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\BlockedContactException;
use App\Http\Requests\StoreChatMessageRequest;
use App\Repositories\ChatMessage\ChatMessageRepositoryInterface;
use App\Repositories\Contact\ContactRepositoryInterface;

class ChatController extends Controller
{
    protected $chatMessageRepository;
    public function __construct(
        protected ContactRepositoryInterface $contactRepository,
        ChatMessageRepositoryInterface $chatMessageRepository
    ) {
        $this->chatMessageRepository = $chatMessageRepository;
    }

    /**
     * Change Message status to read
     */
    public function markMessageAsRead(int $messageId)
    {
        $message = $this->chatMessageRepository->findById($messageId);

        if ($message['receiver_id'] !== Auth::id()) {
            $excption = new BlockedContactException();
            return response()->json($excption->getMessage(), 403);
        }

        // braodcast message read to sender of message
        broadcast(new ReadMessage(['contact_id' => $this->contactRepository->getContact($message['sender_id'], $message['receiver_id'])['id'], 'messageId' => $message['id']]))->toOthers();

        return $this->chatMessageRepository->update($messageId, ['status' => MessageStatusEnum::READ]);
    }

    public function getMessages($user2Id)
    {
        $user1Id = Auth::id();

        return $this->chatMessageRepository->getMessages($user1Id, $user2Id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createNewMessage(StoreChatMessageRequest $request)
    {
        $data = $request->validated();
        $data['sender_id'] = Auth::id();

        try {
            $message = $this->chatMessageRepository->create($data);
            broadcast(new NewChatMessage($message));
            return $message;
        }
        catch (BlockedContactException $excption) {
            return response()->json($excption->getMessage(), 403); // TODO ?remove this
        }
    }
}
