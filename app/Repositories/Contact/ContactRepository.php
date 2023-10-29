<?php

namespace App\Repositories\Contact;

use App\Models\Contact;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreContactRequest;
use App\Repositories\ChatMessage\ChatMessageRepository;

class ContactRepository implements ContactRepositoryInterface
{
    /**
     *
     * @description Get all contacts by user id
     */
    public function getAllByUserId(int $userId)
    {
        return Contact::where('contact_user1_id', $userId)
            ->leftJoin('blocks', function ($join) use ($userId) {
                $join->on('blocks.banned_id', '=', 'contacts.contact_user2_id')
                    ->where('blocks.blocker_id', $userId);
            })
            ->leftJoin('blocks as b2', function ($join) use ($userId) {
                $join->on('b2.blocker_id', '=', 'contacts.contact_user2_id')
                    ->where('b2.banned_id', $userId);
            })
            ->with('contactUser2')
            ->select(
                'contact_user2_id',
                'name',
                DB::raw('(blocks.id IS NOT NULL AND blocks.blocker_id = '.$userId.') AS is_blocked_by_me'),
                DB::raw('(b2.id IS NOT NULL AND b2.banned_id = '.$userId.') AS is_blocking_me')
                )
            ->get();
    }

    public function create($data)
    {
        return Contact::create($data);
    }

    public function update(Contact $contact, $data)
    {
        return $contact->update($data);
    }

    public function destroy(Contact $contact)
    {
        return $contact->delete();
    }
}
