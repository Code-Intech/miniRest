<?php

namespace MiniRest\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'tb_categoria';
    protected $fillable = [
        'Categoria'
    ];
}

?>