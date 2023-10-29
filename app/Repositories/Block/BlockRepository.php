<?php

namespace App\Repositories\Block;

use App\Http\Requests\StoreBlockRequest;
use App\Models\Block;

class BlockRepository implements BlockRepositoryInterface
{
    public function getBlocks($userId)
    {
        return Block::where('blocker_id', $userId)->orWhere('banned_id', $userId)->get();
    }

    public function find($blockerId, $bannedId)
    {
        return Block::where('blocker_id', $blockerId)->where('banned_id', $bannedId)->get();
    }

    public function create($blockerId, $bannedId)
    {
        $block = $this->find($blockerId, $bannedId);

        // if is exisit
        if (!$block->isEmpty())
            return $block;

        return Block::create(['blocker_id' => $blockerId, 'banned_id' => $bannedId]);
    }

    public function destroy($userId, $user2Id)
    {
        return Block::where('blocker_id', $userId)->where('banned_id', $user2Id)->delete();
    }
}
