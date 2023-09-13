<?php

namespace MiniRest\Actions\User;

use MiniRest\DTO\AddressCreateDTO;
use MiniRest\DTO\UserCreateDTO;
use MiniRest\Repositories\AddressRepository;
use MiniRest\Repositories\UserRepository;

class UserUpdateAction
{
    public function __construct()
    {}

    public function execute(int $id, int $userAddressId, UserCreateDTO $userDTO, AddressCreateDTO $addressCreateDTO)
    {
        $userDTO->setAddress($userAddressId);
        $address = $addressCreateDTO->toArray();
        (new AddressRepository())->update($userAddressId, $address);

        $user = $userDTO->toArray();
        $user['Senha'] = password_hash($user['Senha'], PASSWORD_DEFAULT);
        (new userRepository())->update($id, $user);
    }
}