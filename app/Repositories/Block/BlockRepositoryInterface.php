<?php

namespace App\Repositories\Block;

use App\Http\Requests\StoreBlockRequest;
use App\Models\Block;

interface BlockRepositoryInterface
{
    public function getBlocks($userId);

    public function find($blockerId, $bannedId);

    public function create($blockerId, $bannedId);

    public function destroy($userId, $user2Id);
}
