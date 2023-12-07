<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlockRequest;
use App\Repositories\Block\BlockRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    public function __construct(protected BlockRepositoryInterface $blockRepository)
    {
        $this->blockRepository = $blockRepository;
    }

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

        return $this->blockRepository->create($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $bannedId)
    {
        return $this->blockRepository->delete(Auth::id(), $bannedId);
    }
}
