<?php

namespace MiniRest\Models\Portifolio;

use Illuminate\Database\Eloquent\Model;

class PortifolioImage extends Model
{
    protected $table = 'tb_img_video';

    protected $fillable = [
        'Img',
        'tb_portifolio_idtb_portifolio',
        'tb_portifolio_tb_prestador_idtb_prestador',
        'tb_portifolio_tb_prestador_tb_user_idtb_user'
    ];
}