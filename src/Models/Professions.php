<?php

namespace MiniRest\Models;

use Illuminate\Database\Eloquent\Model;

class Professions extends Model
{
    protected $table = 'tb_profissoes';
    protected $fillable = [
        'Profissao'
    ];
}
?>