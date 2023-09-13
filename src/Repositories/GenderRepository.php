<?php

namespace MiniRest\Repositories;

use MiniRest\Models\Gender;

class GenderRepository
{
    protected Gender $gender;
    public function __construct(

    )
    {
        $this->gender = new Gender();
    }

    public function getAll()
    {
        return $this->gender
            ->select('*')
            ->get();
    }
}