<?php

namespace MiniRest\Actions\User;

use MiniRest\DTO\AddressCreateDTO;
use MiniRest\DTO\UserCreateDTO;
use MiniRest\Repositories\AddressRepository;
use MiniRest\Repositories\UserRepository;

class UserCreateAction
{
    public function __construct()
    {}

    public function execute(UserCreateDTO $userDTO, AddressCreateDTO $addressCreateDTO)
    {
        $address = $addressCreateDTO->toArray();
        $addressId = (new AddressRepository())->store($address);

        $userDTO->setAddress($addressId);
        $user = $userDTO->toArray();
        $user['Senha'] = password_hash($user['Senha'], PASSWORD_DEFAULT);


        (new userRepository())->store($user);
    }
}