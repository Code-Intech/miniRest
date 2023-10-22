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

    public function updateServico(int $userId, array $data)
    {
        return $this->model
        ->where("tb_contratante_tb_user_idtb_user", $userId)
        ->update(
            [
                'Data_Inicio' => $data['Data_Inicio'],
                'Estimativa_de_distancia' => $data['Estimativa_de_distancia'],
                'Estimativa_Valor' => $data['Estimativa_Valor'],
                'Estimativa_Idade' => $data['Estimativa_Idade'],
                'Remoto_Presencial' => $data['Remoto_Presencial'],
                'Estimativa_de_Termino' => $data['Estimativa_de_Termino'],
                'Desc' => $data['Desc'],
                'Data_Cadastro_Servico' => $data['Data_Cadastro_Servico'],
                'tb_contratante_idtb_contratante' => $data['tb_contratante_idtb_contratante'],
                'tb_contratante_tb_user_idtb_user' => $data['tb_contratante_tb_user_idtb_user'],
                'tb_end_idtb_end' => $data['tb_end_idtb_end'],
            ]
        );
    }
}

?>