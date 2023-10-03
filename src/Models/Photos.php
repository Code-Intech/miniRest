<?php

namespace MiniRest\Models;

use Illuminate\Database\Eloquent\Model;

class Photos extends Model
{
    protected $table = 'tb_foto';
    protected $primaryKey = 'tb_user_idtb_user';
    protected $fillable = [
        'tb_user_idtb_user',
        'Foto_De_Perfil'
    ];
}