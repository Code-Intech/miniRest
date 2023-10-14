<?php

namespace MiniRest\Models\Prestador;

use Illuminate\Database\Eloquent\Model;

class PrestadorApresentacao extends Model
{
    protected $table = 'tb_apresentacao';
    protected $fillable = [
        'Apresentacao',
        'tb_prestador_idtb_prestador',
        'tb_prestador_tb_user_idtb_user'
    ];
}