<?php

namespace App\Services\Contact;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;

interface ContactServiceInterface
{
    public function getAllByUserId(int $userId);

    public function create($data, $userId);

    public function update(Contact $contact, $data);

    public function destroy(Contact $contact);
}
