<?php

namespace App\Services\Block;

use App\Http\Requests\StoreBlockRequest;
use App\Models\Block;
use App\Repositories\Block\BlockRepository;

class BlockService
{
    protected $blockRepository;

    public function __construct(BlockRepository $blockRepository)
    {
        $this->blockRepository = $blockRepository;
    }

    public function getBlocks($userId)
    {
        return $this->blockRepository->getBlocks($userId);
    }

    public function create($data, $userId)
    {
        return $this->blockRepository->create($userId, $data['banned_id']);
    }

    public function destroy($userId, $user2Id)
    {
        return $this->blockRepository->destroy($userId, $user2Id);
    }
}
