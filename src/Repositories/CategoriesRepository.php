<?php

namespace MiniRest\Repositories;

use MiniRest\Models\Categories;

class CategoriesRepository
{
    protected Categories $category;
    public function __construct()
    {
        $this->category = new Categories();
    }

    public function getAll(){
        
        return $this->category
            ->select('*')
            ->get();
    }
    
    public function find(int $categoryId){

        return $this->category
            ->where('idtb_categoria', '=', $categoryId)
            ->get();
    }

    public function store(array $category)
    {
        $this->category
            ->create($category);
    }

    public function update(int $id, array $category)
    {
        $this->category
            ->where('idtb_categoria', '=', $id)
            ->update($category);
    }

    public function remove(int $id, array $category)
    {
        $this->category
            ->where('idtb_categoria', '=', $id)
            ->update($category);
    }
}

?>