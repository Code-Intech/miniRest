<?php

namespace MiniRest\Models;

use Illuminate\Database\Eloquent\Model;

class Contratante extends Model
{
    protected $table = 'tb_contratante';
    protected $fillable =  [
        'tb_user_idtb_user',
        'FlgStatus'
    ];
}

?>