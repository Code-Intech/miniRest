<?php

namespace MiniRest\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'tb_user';
    protected $fillable = [
        'name', 'email', 'password'
    ];

}