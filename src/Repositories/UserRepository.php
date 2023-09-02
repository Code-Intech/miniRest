<?php

namespace MiniRest\Repositories;

use MiniRest\Models\User;

class UserRepository
{
    protected User $user;
    public function __construct(

    )
    {
        $this->user = new User();
    }

    public function getAll()
    {
        return $this->user
            ->select('*')
            ->get();
    }

    public function store(array $user)
    {
        $this->user->create($user);
    }
}