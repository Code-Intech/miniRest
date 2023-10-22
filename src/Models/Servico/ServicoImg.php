<?php

namespace MiniRest\Models\Servico;

use Illuminate\Database\Eloquent\Model;

class ServicoImg extends Model
{
    protected $table = "tb_img";
    protected $primaryKey = "tb_servico_idtb_servico";
    protected $fillable = [
        'IMG',
        'tb_servico_idtb_sevico',
        'tb_servico_tb_contratante_idtb_contratante',
        'tb_servico_tb_contratante_tb_user_idtb_user'
    ];
}

?>