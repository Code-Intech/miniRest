<?php

namespace MiniRest\Repositories;

use MiniRest\Models\Professions;

class ProfessionsRepository
{
    protected Professions $profession;
    public function __construct()
    {
        $this->profession = new Professions();
    }

    public function getAll(){
        
        return $this->profession
            ->select('*')
            ->get();
    }
    
    public function find(int $professionId){

        return $this->profession
            ->where('idtb_profissoes', '=', $professionId)
            ->get();
    }

    public function store(array $profession)
    {
        $this->profession
            ->create($profession);
    }

    public function update(int $id, array $profession)
    {
        $this->profession
            ->where('idtb_profissoes', '=', $id)
            ->update($profession);
    }

    public function remove(int $id, array $profession)
    {
        $this->profession
            ->where('idtb_profissoes', '=', $id)
            ->update($profession);
    }
}

?>