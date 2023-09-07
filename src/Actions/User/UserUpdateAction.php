<?php

namespace MiniRest\Actions\User;

use MiniRest\Http\Controllers\Users\UserCreateDTO;
use MiniRest\Repositories\UserRepository;

class UserUpdateAction
{
    public function __construct()
    {}

    public function execute(int $id, UserCreateDTO $userDTO)
    {
        $user = $userDTO->toArray();
        $user['Senha'] = password_hash($user['Senha'], PASSWORD_DEFAULT);
        (new userRepository())->update($id, $user);
    }
}