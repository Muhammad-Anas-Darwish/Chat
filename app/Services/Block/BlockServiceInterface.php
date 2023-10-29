<?php

namespace App\Services\Block;

use App\Models\Block;
use App\Http\Requests\StoreBlockRequest;


interface BlockServiceInterface
{
    public function getBlocks($userId);

    public function create($data);

    public function destroy($userId, $user2Id);
}
