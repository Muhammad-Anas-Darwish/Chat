<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\BlockedContactException;
use App\Http\Requests\StoreChatMessageRequest;
use App\Repositories\ChatMessage\ChatMessageRepositoryInterface;

class ChatController extends Controller
{
    protected $chatMessageRepository;
    public function __construct(ChatMessageRepositoryInterface $chatMessageRepository)
    {
        $this->chatMessageRepository = $chatMessageRepository;
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
            broadcast(new NewChatMessage($message))->toOthers();
            return $message;
        }
        catch (BlockedContactException $excption) {
            return response()->json($excption->getMessage(), 403); // TODO ?remove this
        }
    }
}
