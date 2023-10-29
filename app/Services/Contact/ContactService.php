<?php

namespace App\Services\Contact;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Repositories\Contact\ContactRepository;

class ContactService
{
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function getAllByUserId($userId)
    {
        $contacts =  $this->contactRepository->getAllByUserId($userId);

        foreach ($contacts as $contact) {

        }

        return $contacts;
    }

    public function create($data, $userId)
    {
        $data['contact_user1_id'] = $userId;
        return $this->contactRepository->create($data);
    }

    public function update(Contact $contact, $data)
    {
        return $this->contactRepository->update($contact, $data);
    }

    public function destroy(Contact $contact)
    {
        return $this->contactRepository->destroy($contact);
    }
}
