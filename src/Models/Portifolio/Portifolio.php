<?php

namespace MiniRest\Models\Portifolio;

use Illuminate\Database\Eloquent\Model;

class Portifolio extends Model
{
    protected $table = 'tb_portifolio';

    protected $fillable = [
        'Titulo',
        'Capa',
        'Descricao',
        'tb_prestador_idtb_prestador',
        'tb_prestador_tb_user_idtb_user'
    ];
}