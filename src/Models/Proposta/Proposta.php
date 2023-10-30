<?php

namespace MiniRest\Models\Proposta;

use Illuminate\Database\Eloquent\Model;

class Proposta extends Model
{
    protected $table = 'tb_proposta';
    protected $primaryKey = 'idtb_proposta';
    protected $fillable = [
        'Proposta_Aceita',
        'Valor_Proposta',
        'Comentario',
        'Data_Proposta',
        'tb_servico_idtb_servico',
        'tb_servico_tb_contratante_idtb_contratante',
        'tb_servico_tb_contratante_tb_user_idtb_user',
        'tb_prestador_idtb_prestador',
        'tb_prestador_tb_user_idtb_user',
        'FlgStatus'
    ];
}
?>