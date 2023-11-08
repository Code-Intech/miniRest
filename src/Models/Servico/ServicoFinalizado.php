<?php

namespace miniRest\Models\Servico;

use Illuminate\Database\Eloquent\Model;

class ServicoFinalizado extends Model
{
    protected $table = "tb_sv_finalizado";
    protected $fillable = [
        'idtb_sv_finalizado',
        'Data',
        'tb_proposta_idtb_proposta'
    ];
}