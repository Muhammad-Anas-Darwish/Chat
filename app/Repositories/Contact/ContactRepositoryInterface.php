<?php

namespace App\Repositories\Contact;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ContactRepositoryInterface
{
    public function getAllByUserId(int $userId): Collection;

    public function getContact(int $user1Id, int $user2Id): Model;

    public function create(array $data): ?Model;
}
