<?php

namespace MiniRest\Models\Servico;

use Illuminate\Database\Eloquent\Model;

class ServicoUploadImage extends model
{
    protected $table = "tb_img";
    protected $primaryKey = 'idtb_img';
    protected $fillable = [
        "IMG",
        "tb_servico_idtb_servico",
        "tb_servico_tb_contratante_idtb_contratante",
        "tb_servico_tb_contratante_tb_user_idtb_user",
    ];
}

?>