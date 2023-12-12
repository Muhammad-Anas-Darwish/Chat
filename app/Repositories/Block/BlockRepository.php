<?php

namespace App\Repositories\Block;

use App\Models\Block;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class BlockRepository extends BaseRepository implements BlockRepositoryInterface
{
    protected $model;

    public function __construct(Block $model)
    {
        $this->model = $model;
    }

    public function getBlocks(int $userId): Model
    {
        return $this->model->where('blocker_id', $userId)->orWhere('banned_id', $userId)->get();
    }

    public function find(int $blockerId, int $bannedId): ?Model
    {
        $block = $this->model->where('blocker_id', $blockerId)->where('banned_id', $bannedId)->first();
        return $block;
    }

    public function create(array $data): ?Model
    {
        $block = $this->find($data['blocker_id'], $data['banned_id']);

        if ($block !== null)
            return $block;

        return parent::create($data);
    }

    public function delete(int $blockerId, int $bannedId): ?bool
    {
        return $this->find($blockerId, $bannedId)->delete();
    }

    public function isBlocked(int $blockerId, int $bannedId): ?bool
    {
        return ($this->find($blockerId, $bannedId) !== null);
    }
}
