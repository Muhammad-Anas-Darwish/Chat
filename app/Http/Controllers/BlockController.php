<?php

namespace App\Http\Controllers;

use App\Events\UpdateContact;
use App\Http\Requests\StoreBlockRequest;
use App\Repositories\Block\BlockRepositoryInterface;
use App\Repositories\Contact\ContactRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    public function __construct(
        protected BlockRepositoryInterface $blockRepository,
        protected ContactRepositoryInterface $contactRepository
    ) { }

    /**
     * Get all blocks of user
     */
    public function getBlocks()
    {
        $userId = Auth::id();
        return $this->blockRepository->getBlocks($userId);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlockRequest $request)
    {
        $data = $request->validated();
        $data['blocker_id'] = Auth::id();

        $block = $this->blockRepository->create($data);

        // broadcast to another peron
        $contact = [
            'id' => $this->contactRepository->getContact($data['banned_id'], $data['blocker_id'])['id'],
            'is_blocked_by_me' => $this->blockRepository->isBlocked($data['banned_id'], $data['blocker_id']) ?? 0,
            'is_blocking_me' => 1,
        ];
        broadcast(new UpdateContact($contact))->toOthers();

        // broadcast to me
        $contact = [
            'id' => $this->contactRepository->getContact($data['blocker_id'], $data['banned_id'])['id'],
            'is_blocked_by_me' => 1,
            'is_blocking_me' => $this->blockRepository->isBlocked($data['blocker_id'], $data['banned_id']) ?? 0,
        ];
        broadcast(new UpdateContact($contact));

        return $block;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $bannedId)
    {
        $userId = Auth::id();
        $block = $this->blockRepository->delete($userId, $bannedId);

        // broadcast to another person
        $contact = [
            'id' => $this->contactRepository->getContact($bannedId, $userId)['id'],
            'is_blocked_by_me' => $this->blockRepository->isBlocked($bannedId, $userId) ?? 0,
            'is_blocking_me' => 0,
        ];
        broadcast(new UpdateContact($contact))->toOthers();

        // broadcast to me
        $contact = [
            'id' => $this->contactRepository->getContact($userId, $bannedId)['id'],
            'is_blocked_by_me' => 0,
            'is_blocking_me' => $this->blockRepository->isBlocked($userId, $bannedId) ?? 0,
        ];
        broadcast(new UpdateContact($contact));

        return $block;
    }
}
