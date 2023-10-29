<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Http\Requests\StoreBlockRequest;
use App\Http\Requests\UpdateBlockRequest;
use App\Services\Block\BlockService;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    protected $blockService;

    public function __construct(BlockService $blockService)
    {
        $this->blockService = $blockService;
    }

    /**
     * Get all blocks of user
     */
    public function getBlocks()
    {
        $userId = Auth::id();
        return $this->blockService->getBlocks($userId);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlockRequest $request)
    {
        $data = $request->validated();
        $userId = Auth::id();

        return $this->blockService->create($data, $userId);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user2Id)
    {
        $userId = Auth::id();

        return $this->blockService->destroy($userId, $user2Id);
    }
}
