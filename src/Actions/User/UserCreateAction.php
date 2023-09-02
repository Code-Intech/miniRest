<?php

namespace MiniRest\Actions\User;

use MiniRest\Http\Controllers\Users\UserCreateDTO;
use MiniRest\Repositories\UserRepository;
use MiniRest\Services\AuthService;

class UserCreateAction
{
    protected UserRepository $userRepository;
    public function __construct()
    {}

    public function execute(UserCreateDTO $userDTO)
    {
        $validate = $userDTO->validate();
        if (!$validate) return $validate;

        $user = $userDTO->toArray();
        $user['Senha'] = password_hash($user['Senha'], PASSWORD_DEFAULT);
        (new userRepository())->store($user);
    }
}