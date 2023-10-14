<?php

namespace MiniRest\Models\Prestador;

use Illuminate\Database\Eloquent\Model;

class PrestadorHabilidades extends Model
{
    protected $table = 'tb_prestador_habilidade';
    protected $fillable = [
        'tb_prestador_idtb_prestador',
        'tb_prestador_tb_user_idtb_user',
        'tb_habilidades_idtb_habilidades'
    ];
}