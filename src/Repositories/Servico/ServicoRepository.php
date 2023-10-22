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
            $servicoAll = Servico::select('idtb_servico','Titulo_Servico','Data_Inicio', 'Estimativa_de_distancia', 'Estimativa_Valor', 'Estimativa_Idade', 'Remoto_Presencial', 'Estimativa_de_termino', 'Desc')
                ->where('idtb_servico', $servico->idtb_servico)
                ->first();
            
            $localidade = DB::table('tb_servico')
                ->select('Cidade', 'Estado', 'Bairro')
                ->join('tb_end', 'tb_end.idtb_end', '=', 'tb_servico.tb_end_idtb_end')
                ->where('tb_servico.idtb_servico', $servico->idtb_servico)
                ->first();
            
            $contratante = DB::table('tb_user')
                ->select('Nome_Completo')
                ->join('tb_contratante', 'tb_user.idtb_user', '=', 'tb_contratante.tb_user_idtb_user')
                ->join('tb_servico', 'tb_contratante.idtb_contratante', '=', 'tb_servico.tb_contratante_idtb_contratante')
                ->where('tb_servico.idtb_servico', $servico->idtb_servico)
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
                'contratante' => $contratante,
                'servicoInfo' => $servicoAll,
                'localidade' => $localidade,
                'servicoProfessions' => $profissoes,
                'servicoSkills' => $habilidades,
            ];
        }

        return $data;
    }

    public function find(int|string $servicoId)
    {

        $servicoAll = Servico::select('idtb_servico','Titulo_Servico','Data_Inicio', 'Estimativa_de_distancia', 'Estimativa_Valor', 'Estimativa_Idade', 'Remoto_Presencial', 'Estimativa_de_termino', 'Desc')
                ->where('idtb_servico', $servicoId)
                ->first();
            
        $localidade = DB::table('tb_servico')
            ->select('Cidade', 'Estado', 'Bairro')
            ->join('tb_end', 'tb_end.idtb_end', '=', 'tb_servico.tb_end_idtb_end')
            ->where('tb_servico.idtb_servico', $servicoId)
            ->first();
        
        $contratante = DB::table('tb_user')
            ->select('Nome_Completo')
            ->join('tb_contratante', 'tb_user.idtb_user', '=', 'tb_contratante.tb_user_idtb_user')
            ->join('tb_servico', 'tb_contratante.idtb_contratante', '=', 'tb_servico.tb_contratante_idtb_contratante')
            ->where('tb_servico.idtb_servico', $servicoId)
            ->first();

        $profissoes = DB::table('tb_servico_profissao')
            ->select('Profissao', 'idtb_profissoes', 'Categoria', 'tb_categoria_idtb_categoria')
            ->join('tb_profissoes', 'tb_profissoes.idtb_profissoes', '=', 'tb_servico_profissao.tb_profissoes_idtb_profissoes')
            ->join('tb_categoria', 'tb_categoria.idtb_categoria', '=', 'tb_profissoes.tb_categoria_idtb_categoria')
            ->where('tb_servico_profissao.tb_servico_idtb_servico', $servicoId)
            ->get();
        

        $habilidades = DB::table('tb_servico_habilidade')
        ->select('Habilidade', 'idtb_habilidades')
        ->join('tb_habilidades', 'tb_habilidades.idtb_habilidades', '=', 'tb_servico_habilidade.tb_habilidades_idtb_habilidades')
        ->where('tb_servico_habilidade.tb_servico_idtb_servico', $servicoId)
        ->get();

        return [
            'contratante' => $contratante,
            'servicoInfo' => $servicoAll,
            'localidade' => $localidade,
            'servicoProfessions' => $profissoes,
            'servicoSkills' => $habilidades,
        ];
    }

    public function me(int $userId)
    {
        $servico = Servico::where('tb_contratante_tb_user_idtb_user', $userId)->get();
        $data = [];

        foreach($servico as $servicos){
            $servicoAll = Servico::select('idtb_servico','Titulo_Servico','Data_Inicio', 'Estimativa_de_distancia', 'Estimativa_Valor', 'Estimativa_Idade', 'Remoto_Presencial', 'Estimativa_de_termino', 'Desc')
                ->where('idtb_servico', $servicos->idtb_servico)
                ->first();
            
            $localidade = DB::table('tb_servico')
            ->select('Cidade', 'Estado', 'Bairro')
            ->join('tb_end', 'tb_end.idtb_end', '=', 'tb_servico.tb_end_idtb_end')
            ->where('tb_servico.idtb_servico', $servicos->idtb_servico)
            ->first();
        
            $contratante = DB::table('tb_user')
                ->select('Nome_Completo')
                ->join('tb_contratante', 'tb_user.idtb_user', '=', 'tb_contratante.tb_user_idtb_user')
                ->join('tb_servico', 'tb_contratante.idtb_contratante', '=', 'tb_servico.tb_contratante_idtb_contratante')
                ->where('tb_servico.idtb_servico', $servicos->idtb_servico)
                ->first();

            $profissoes = DB::table('tb_servico_profissao')
                ->select('Profissao', 'idtb_profissoes', 'Categoria', 'tb_categoria_idtb_categoria')
                ->join('tb_profissoes', 'tb_profissoes.idtb_profissoes', '=', 'tb_servico_profissao.tb_profissoes_idtb_profissoes')
                ->join('tb_categoria', 'tb_categoria.idtb_categoria', '=', 'tb_profissoes.tb_categoria_idtb_categoria')
                ->where('tb_servico_profissao.tb_servico_idtb_servico', $servicos->idtb_servico)
                ->get();
            

            $habilidades = DB::table('tb_servico_habilidade')
            ->select('Habilidade', 'idtb_habilidades')
            ->join('tb_habilidades', 'tb_habilidades.idtb_habilidades', '=', 'tb_servico_habilidade.tb_habilidades_idtb_habilidades')
            ->where('tb_servico_habilidade.tb_servico_idtb_servico', $servicos->idtb_servico)
            ->get();

            $data [] = [
                'contratante' => $contratante,
                'servicoInfo' => $servicoAll,
                'localidade' => $localidade,
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

    public function getServicoIdByUserId(int $userId)
    {
        $servico = $this->model->where('tb_contratante_tb_user_idtb_user', $userId)->first();
        if ($servico) {
            return $servico->idtb_servico;
        }
    }
}

?>