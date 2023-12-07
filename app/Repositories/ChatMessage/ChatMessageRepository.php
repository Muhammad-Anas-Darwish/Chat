<?php

namespace App\Repositories\ChatMessage;

use App\Enum\MessageStatusEnum;
use App\Models\ChatMessage;
use App\Exceptions\BlockedContactException;
use App\Repositories\BaseRepository;
use App\Repositories\Block\BlockRepositoryInterface;
use App\Repositories\ChatMessage\ChatMessageRepositoryInterface;;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ChatMessageRepository extends BaseRepository implements ChatMessageRepositoryInterface
{
    protected $model;
    protected $blockRepository;

    public function __construct(ChatMessage $model, BlockRepositoryInterface $blockRepository)
    {
        $this->model = $model;
        $this->blockRepository = $blockRepository;
    }

    public function changeMessagesStatus(int $user1Id, int $user2Id, MessageStatusEnum $status): void
    {
        $this->model->where(function($query) {
                $query->where('status', MessageStatusEnum::SENT)
                    ->orWhere('status', MessageStatusEnum::RECEIVED);
            })
            ->where('sender_id', $user2Id)
            ->where('receiver_id', $user1Id)
            ->update(['status' => $status]);
    }

    public function create(array $data): ?Model
    {
        $isBlocked = $this->blockRepository->isBlocked($data['sender_id'], $data['receiver_id']); // error here

        if($isBlocked)
            throw new BlockedContactException();

        $data['status'] = MessageStatusEnum::SENT;
        return parent::create($data);
    }

    public function getMessages($user1Id, $user2Id): LengthAwarePaginator
    {
        $this->changeMessagesStatus($user1Id, $user2Id, MessageStatusEnum::READ);
        return $this->model->where(
            function ($query) use ($user1Id, $user2Id) {
                $query->where('sender_id', $user1Id)->where('receiver_id', $user2Id);
            })
            ->orWhere(function ($query) use ($user1Id, $user2Id) {
                $query->where('sender_id', $user2Id)->where('receiver_id', $user1Id);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(30);
    }

    public function getNumberOfUnreadChatMessages($user1Id, $user2Id): int
    {
        $count = $this->model->where('status', MessageStatusEnum::SENT)
        ->where(
            function ($query) use ($user1Id, $user2Id) {
                $query->where('sender_id', $user2Id)->where('receiver_id', $user1Id);
        })
        ->count();

        return $count;
    }
}
