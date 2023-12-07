<?php

namespace App\Repositories\Block;

use App\Http\Requests\StoreBlockRequest;
use App\Models\Block;
use Illuminate\Database\Eloquent\Model;

interface BlockRepositoryInterface
{
    public function getBlocks(int $userId): Model;

    public function find(int $blockerId, int $bannedId): ?Model;

    public function create(array $data): ?Model;

    public function delete(int $blockerId, int $bannedId): ?bool;

    public function isBlocked(int $senderId, int $receiverId): ?bool;
}
