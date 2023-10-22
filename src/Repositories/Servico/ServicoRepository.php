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

    public function getAll()
    {
        $servicos = Servico::all();
        $data = [];

        foreach($servicos as $servico){
            $servicoAll = Servico::select('idtb_servico','Data_Inicio', 'Estimativa_de_distancia', 'Estimativa_Valor', 'Estimativa_Idade', 'Remoto_Presencial', 'Estimativa_de_termino', 'Desc')
                ->where('idtb_servico', $servico->idtb_servico)
                ->first();

            $profissoes = DB::table('tb_servico_profissao')
                ->select('Profissao', 'idtb_profissoes', 'Categoria', 'tb_categoria_idtb_categoria')
                ->join('tb_profissoes', 'tb_profissoes.idtb_profissoes', '=', 'tb_servico_profissao.tb_profissoes_idtb_profissoes')
                ->join('tb_categoria', 'tb_categoria.idtb_categoria', '=', 'tb_profissoes.tb_categoria_idtb_categoria')
                ->where('tb_servico_profissao.tb_servico_idtb_servico', $servico->idtb_servico)
                ->get();
            
    
            $habilidades = DB::table('tb_servico_habilidade')
            ->select('Habilidade', 'idtb_habilidades')
            ->join('tb_habilidades', 'tb_habilidades.idtb_habilidades', '=', 'tb_servico_habilidade.tb_habilidades_idtb_habilidades')
            ->where('tb_servico_habilidade.tb_servico_idtb_servico', $servico->idtb_servico)
            ->get();
            

            $data[] = [
                'servicoInfo' => $servicoAll,
                'servicoProfessions' => $profissoes,
                'servicoSkills' => $habilidades,
            ];
        }

        return $data;
    }

    public function storeServico(array $data)
    {
        try{
            return DB::transaction(function() use($data){
                $servico = $this->model->create($data);
                return $servico;
            });
        } catch(\Exception $e){
            var_dump($e);
            throw new DatabaseInsertException("Erro ao criar serviço.", StatusCode::SERVER_ERROR, $e);
        }
    }

    public function updateServico(int $userId, array $data)
    {
        return $this->model
        ->where("tb_contratante_tb_user_idtb_user", $userId)
        ->update(
            [
                'Titulo_Servico' => $data['Titulo_Servico'],
                'Data_Inicio' => $data['Data_Inicio'],
                'Estimativa_de_distancia' => $data['Estimativa_de_distancia'],
                'Estimativa_Valor' => $data['Estimativa_Valor'],
                'Estimativa_Idade' => $data['Estimativa_Idade'],
                'Remoto_Presencial' => $data['Remoto_Presencial'],
                'Estimativa_de_Termino' => $data['Estimativa_de_Termino'],
                'Desc' => $data['Desc'],
                'tb_contratante_idtb_contratante' => $data['tb_contratante_idtb_contratante'],
                'tb_contratante_tb_user_idtb_user' => $data['tb_contratante_tb_user_idtb_user'],
                'tb_end_idtb_end' => $data['tb_end_idtb_end'],
            ]
        );
    }
}

?>