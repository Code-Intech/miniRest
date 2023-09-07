<?php

namespace MiniRest\Actions\User;

use MiniRest\Http\Controllers\Users\UserFlgStatusDTO;
use MiniRest\Repositories\UserRepository;

class UserFlgStatusAction
{
    public function __construct()
    {}

    public function execute(int $id, UserFlgStatusDTO $userDTO)
    {
        $user = $userDTO->toArray();
        (new userRepository())->remove($id, $user);
    }
}