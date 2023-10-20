<?php

namespace App\Services\Contact;

use App\Models\ChatMessage;

interface ContactServiceInterface
{
    public function getAllByUserId(int $userId);
}
