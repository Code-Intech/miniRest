<?php

namespace MiniRest\Models\Servico;

use Illuminate\Database\Eloquent\Model;

class ServicoProfissao extends Model
{
    protected $table = 'tb_servico_profissao';
    protected $fillable = [
        'tb_servico_idtb_servico',
        'tb_servico_tb_contratante_idtb_contratante',
        'tb_servico_tb_contratante_tb_user_idtb_user',
        'tb_profissoes_idtb_profissoes',
    ];
}

?>