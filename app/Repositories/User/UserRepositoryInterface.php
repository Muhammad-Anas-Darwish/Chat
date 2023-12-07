<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function create($data);

    public function where($key, $value);

    public function find(int $id);
}
