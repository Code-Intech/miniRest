<?php

namespace MiniRest\Repositories\Servico;

use MiniRest\Models\Servico\Servico;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use Illuminate\Database\Capsule\Manager as DB;

class ServicoRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Servico();        
    }

    public function storeServico(array $data)
    {
        try{
            return DB::transaction(function() use($data){
                $servico = $this->model->create($data);
                return $servico;
            });
        } catch(\Exception $e){
            throw new DatabaseInsertException("Erro ao criar serviço.", StatusCode::SERVER_ERROR, $e);
        }
    }
}

?>