<?php

namespace MiniRest\Repositories;

use MiniRest\Models\Skills;

class SkillsRepository
{
    protected Skills $skill;
    public function __construct()
    {
        $this->skill = new Skills();
    }

    public function getAll(){
        return $this->skill
            ->select('*')
            ->get();
    }

    public function find(int $skillId){

        return $this->skill
            ->where('idtb_habilidades', '=', $skillId)
            ->get();
    }

    public function store(array $skill)
    {
        $this->skill
            ->create($skill);
    }

    public function update(int $id, array $skill)
    {
        $this->skill
            ->where('idtb_habilidades', '=', $id)
            ->update($skill);
    }

    public function remove(int $id, array $skill)
    {
        $this->skill
            ->where('idtb_habilidades', '=', $id)
            ->update($skill);
    }
}

?>