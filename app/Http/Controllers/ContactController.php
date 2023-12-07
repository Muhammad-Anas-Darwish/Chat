<?php

namespace App\Http\Controllers;

use App\Exceptions\ContactAlreadyExistsException;
use App\Http\Requests\DeleteContactRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Repositories\ChatMessage\ChatMessageRepositoryInterface;
use App\Repositories\Contact\ContactRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class ContactController extends Controller
{
    protected $contactRepository;
    protected $userRepository;
    protected $chatMessageRepository;

    public function __construct(ContactRepositoryInterface $contactRepository, UserRepositoryInterface $userRepository, ChatMessageRepositoryInterface $chatMessageRepository)
    {
        $this->contactRepository = $contactRepository;
        $this->userRepository = $userRepository;
        $this->chatMessageRepository = $chatMessageRepository;
    }

    public function getContact($contactId)
    {
        // TODO add validate with request
        $userId = Auth::id();

        $contact = $this->contactRepository->findById($contactId);

        if ($contact['contact_user1_id'] !== $userId)
            return response()->json(['error' => 'Unauthorized access'], 403);

        return $contact;
    }

    public function getContacts()
    {
        $userId = Auth::id();
        // TODO edit this code

        $contacts = $this->contactRepository->getAllByUserId($userId);

        collect($contacts)->map(function ($contact) use ($userId) {
            $countContacts = $this->chatMessageRepository->getNumberOfUnreadChatMessages($userId, $contact['contact_user2_id']);
            $contact['numberOfUnreadChatMessages'] = $countContacts;
        });

        return $contacts;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $data = $request->validated();

        $user1Id = Auth::id();
        $user2Id = $this->userRepository->where('username', $data['username'])->select('id')->first()['id'];

        try {
            return $this->contactRepository->create(['contact_user1_id' => $user1Id, 'contact_user2_id' => $user2Id, 'name' => $data['name']]);
        } catch (ContactAlreadyExistsException $excption) {
            return response()->json($excption->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, int $id)
    {
        $data = $request->validated();

        return $this->contactRepository->update($id, $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteContactRequest $request, int $id)
    {
        return $this->contactRepository->deleteById($id);
    }
}
