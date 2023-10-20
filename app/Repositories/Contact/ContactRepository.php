<?php

namespace App\Repositories\Contact;

use App\Models\Contact;
use App\Models\ChatMessage;
use App\Repositories\ChatMessage\ChatMessageRepository;

class ContactRepository implements ContactRepositoryInterface
{

    // /**
    //  * @description Get All Contacts
    //  *
    //  * @param array $columns
    //  */
    // public function get(array $columns = ['*'])
    // {
    //     return Contact::query()->select($columns)->get();
    // }

    /**
     *
     * @description Get all contacts by user id
     */
    public function getAllByUserId(int $userId)
    {
        return Contact::where('contact_user1_id', $userId)
            ->select('contact_user2_id', 'name')
            ->with('contactUser2')
            ->get();
    }
}
