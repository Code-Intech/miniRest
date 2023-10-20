<?php

namespace MiniRest\Models;

use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    protected $table = 'tb_habilidades';
    protected $fillable = [
        'idtb_habilidades',
        'Habilidades'
    ];
}

?>