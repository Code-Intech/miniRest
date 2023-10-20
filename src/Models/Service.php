<?php

namespace MiniRest\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'tb_servico';
    protected $fillable = [
        'Data_Inicio',
        'Estimativa_de_distancia',
        'Estimativa_Valor',
        'Estimativa_Idade',
        'Remoto_Presencial',
        'Estimativa_de_Termino',
        'Desc',
        'Data_Cadastro_Servico',
        'tb_contratante_idtb_contratante',
        'tb_contratante_tb_user_idtb_user',
        'tb_end_idtb_end',
        'FlgStatus'
    ];
}

?>