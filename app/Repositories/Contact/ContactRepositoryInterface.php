<?php

namespace App\Repositories\Contact;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;

interface ContactRepositoryInterface
{
    public function getAllByUserId(int $userId);

    public function create($data);

    public function update(Contact $contact, $data);

    public function destroy(Contact $contact);
}
