<?php

namespace MiniRest\Actions\Services;

use MiniRest\DTO\AddressCreateDTO;
use MiniRest\DTO\Services\ServiceCreateDTO;
use MiniRest\Repositories\AddressRepository;
use MiniRest\Repositories\ServiceRepository;

class ServiceCreateAction
{
    public function __construct(){}

    public function execute(ServiceCreateDTO $serviceDTO, AddressCreateDTO $addressCreateDTO)
    {
        $address = $addressCreateDTO->toArray();
        $addressId = (new AddressRepository())->store($address);

        $serviceDTO->setAddress($addressId);
        $service = $serviceDTO->toArray();
        (new ServiceRepository())->store($service);
        
    }
}

?>