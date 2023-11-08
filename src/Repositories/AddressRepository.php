<?php

namespace MiniRest\Repositories;

use MiniRest\Models\Address;

class AddressRepository
{
    protected Address $address;
    public function __construct(

    )
    {
        $this->address = new Address();
    }
    public function store(array $address)
    {
        return Address::insertGetId($address);
    }

    public function update(int $id, array $address)
    {
        $this->address->where('idtb_end', '=', $id)->update($address);
    }

    public function byId(int $id)
    {
        return $this->address->where('idtb_end', '=', $id)->first();
    }
}