<?php

namespace App\Repositories\Contact;

interface ContactRepositoryInterface
{
    public function getAllByUserId(int $userId);
}
