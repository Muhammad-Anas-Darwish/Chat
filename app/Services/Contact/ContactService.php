<?php

namespace App\Services\Contact;

use App\Repositories\Contact\ContactRepository;

class ContactService
{
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    // public function get(array $columns = ['*'])
    // {
    //     return $this->contactRepository->get($columns);
    // }

    public function getAllByUserId($userId)
    {
        $contacts =  $this->contactRepository->getAllByUserId($userId);

        return $contacts;
    }
}
