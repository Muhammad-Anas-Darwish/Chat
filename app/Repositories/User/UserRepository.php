<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function create($data)
    {
        return User::create($data);
    }

    public function where($key, $value)
    {
        return User::where($key, $value);
    }

    public function find(int $id)
    {
        return User::find($id);
    }
}
