<?php

namespace MiniRest\Models\Prestador;

use Illuminate\Database\Eloquent\Model;

class Prestador extends Model
{
    protected $table = 'tb_prestador';

    protected $fillable = [
        'Valor_Da_Hora',
        'Valor_diaria',
        'Nome_Empresa',
        'CNPJ',
        'tb_user_idtb_user'
    ];

    protected $hidden = [
        'Senha', # precisa desse hidden por conta do getById da proposta e serviço - Senha do user
    ];

}