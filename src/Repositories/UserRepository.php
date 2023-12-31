<?php

namespace MiniRest\Repositories;

use MiniRest\Models\Photos;
use MiniRest\Models\User;

class UserRepository
{
    protected User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function getAll()
    {
        return $this->user
            ->select('*')
            ->get();
    }

    public function me(int $userId)
    {
        $user = $this->user->where('idtb_user', '=', $userId)
            ->first();

        $address = (new AddressRepository())->byId($user->tb_end_idtb_end);

        $user['address'] = $address;

        return [
            'user' => $user,
        ];
    }

    public function store(array $user)
    {
        $this->user->create($user);
    }

    public function update(int $id, array $user)
    {
        $this->user->where('idtb_user', '=', $id)->update($user);
    }

    public function remove(int $id, array $user)
    {
        $this->user->where('idtb_user', '=', $id)->update($user);
    }
}