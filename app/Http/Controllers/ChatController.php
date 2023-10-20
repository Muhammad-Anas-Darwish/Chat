<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreChatMessageRequest;
use App\Http\Requests\UpdateChatMessageRequest;
use App\Services\ChatMessage\ChatMessageService;

class ChatController extends Controller
{
    protected $chatMessageService;

    public function __construct(ChatMessageService $chatMessageService)
    {
        $this->chatMessageService = $chatMessageService;
    }

    public function getMessages($user2Id)
    {
        $user1Id = Auth::id();

        return $this->chatMessageService->getMessages($user1Id, $user2Id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createNewMessage(StoreChatMessageRequest $request)
    {
        $data = $request->validated();

        return $this->chatMessageService->create($data, Auth::id());
    }
}
