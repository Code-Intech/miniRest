<?php

namespace MiniRest\Repositories;
use MiniRest\Models\Service;

class ServiceRepository
{
    protected Service $service;

    public function __construct()
    {
        $this->service = new Service();
    }

    public function getAll()
    {
        return $this->service
            ->select('*')
            ->get();
    }

    public function me(int $serviceId)
    {
        return $this->service->where('idtb_servico', '=', $serviceId)->first();

    }

    public function store(array $service)
    {
        var_dump($service);
        $this->service->create($service);
    }

    public function update(int $id, array $service)
    {
        $this->service->where('idtb_servico','=', $id)->update($service);
    }

    public function remove(int $id, array $service)
    {  
        $this->service->where('idtb_servico', '=', $id)->update($service);
    }
}

?>