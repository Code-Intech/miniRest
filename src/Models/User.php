<?php

namespace MiniRest\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'tb_user';
    protected $fillable = [
        'Nome_completo',
        'Data_Nacimento',
        'Genero',
        'Telefone',
        'Email',
        'Senha',
        'CPF',
        'CEP',
        'Rua',
        'Regiao',
        'Bairro',
    ];

    protected $hidden = [
        'Senha'
    ];

}