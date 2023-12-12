<?php

namespace App\Repositories\Contact;

use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use App\Exceptions\ContactAlreadyExistsException;
use App\Repositories\Block\BlockRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\TestRunner\TestResult\Collector;

class ContactRepository extends BaseRepository implements ContactRepositoryInterface
{
    protected $model;
    protected $userRepository;
    protected $blockRepository;

    public function __construct(Contact $model, UserRepositoryInterface $userRepository, BlockRepositoryInterface $blockRepository)
    {
        $this->model = $model;
        $this->userRepository = $userRepository;
        $this->blockRepository = $blockRepository;
    }

    /**
     *
     * @description Get all contacts by user id
     */
    public function getAllByUserId(int $userId): Collection
    {
        return $this->model->where('contact_user1_id', $userId)
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
                'contacts.id',
                'contact_user2_id',
                'name',
                DB::raw('(blocks.id IS NOT NULL AND blocks.blocker_id = '.$userId.') AS is_blocked_by_me'),
                DB::raw('(b2.id IS NOT NULL AND b2.banned_id = '.$userId.') AS is_blocking_me')
                )
            ->get();
    }

    public function getContact(int $user1Id, int $user2Id): Model
    {
        return $this->model->where('contact_user1_id', $user1Id)
            ->where('contact_user2_id', $user2Id)
            ->first();
    }

    public function create(array $data): ?Model
    {
        // if is exisit
        $contact = $this->getContact($data['contact_user1_id'], $data['contact_user2_id']);
        if (!$contact->isEmpty())
            throw new ContactAlreadyExistsException();

        return parent::create($data);
    }
}
