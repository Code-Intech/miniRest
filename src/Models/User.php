<?php

namespace MiniRest\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'tb_user';
    protected $fillable = [
        'Nome_completo', 'CPF', 'idtb_user'
    ];

    protected $hidden = [
        'Senha'
    ];

}