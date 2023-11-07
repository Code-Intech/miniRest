<?php

namespace miniRest\Models\Servico;

use Illuminate\Database\Eloquent\Model;

class ServicoFinalizado extends Model
{
    protected $table = "tb_sv_finalizado";
    protected $primaryKey = "idtb_sv_finalizado";
    protected $fillable = [
        'Data',
        'tb_proposta_idtb_proposta'
    ];
}