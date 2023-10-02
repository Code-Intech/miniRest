<?php

namespace MiniRest\Repositories;

use MiniRest\Models\Photos;
use MiniRest\Models\User;

class UserRepository
{
    protected User $user;
    protected Photos $photos;

    public function __construct()
    {
        $this->user = new User();
        $this->photos = new Photos();
    }

    public function getAll()
    {
        return $this->user
            ->select('*')
            ->get();
    }

    public function me(int $userId)
    {
        return $this->user->where('idtb_user', '=', $userId)->get();
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

    public function storeAvatar(int $id)
    {
        $this->photos
            ->where('idtb_user', '=', $id)
            ->updateOrCreate(
                ['departure' => 'Oakland', 'destination' => 'San Diego'],
            );


    }

}