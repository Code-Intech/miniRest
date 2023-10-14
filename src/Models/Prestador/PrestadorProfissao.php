<?php

namespace MiniRest\Models\Prestador;

use Illuminate\Database\Eloquent\Model;

class PrestadorProfissao extends Model
{
    protected $table = 'tb_prestador_profissao';
    protected $fillable = [
        'Experiencia',
        'tb_profissoes_idtb_profissoes',
        'tb_prestador_idtb_prestador',
        'tb_prestador_tb_user_idtb_user'
    ];
}