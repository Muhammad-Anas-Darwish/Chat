<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Contact\ContactService;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Services\ChatMessage\ChatMessageService;

class ContactController extends Controller
{
    protected $contactService;
    protected $chatMessageService;

    public function __construct(ContactService $contactService, ChatMessageService $chatMessageService)
    {
        $this->contactService = $contactService;
        $this->chatMessageService = $chatMessageService;
    }

    public function getContacts(Request $request)
    {
        $userId = Auth::id();

        $contacts = $this->contactService->getAllByUserId($userId);

        collect($contacts)->map(function ($contact) use ($userId) {
            $countContacts = $this->chatMessageService->getNumberOfUnreadChatMessages($userId, $contact['contact_user2_id']);
            $contact['numberOfUnreadChatMessages'] = $countContacts;
        });

        return $contacts;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
