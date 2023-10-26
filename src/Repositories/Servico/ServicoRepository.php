<?php

namespace MiniRest\Repositories\Servico;

use MiniRest\Models\Servico\Servico;
use MiniRest\Models\Servico\ServicoUploadImage;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Exceptions\ServiceNotFoundedException;
use MiniRest\Helpers\StatusCode\StatusCode;
use Illuminate\Database\Capsule\Manager as DB;

class ServicoRepository
{
    private $model;
    private $imagesModel;

    public function __construct()
    {
        $this->model = new Servico();  
        $this->imagesModel = new ServicoUploadImage();
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

    public function updateServico(array $data)
    {
        $servicoId = $data['idtb_servico'];
        try{
            return DB::transaction(function() use($data, $servicoId){
                $servico = $this->model->where('idtb_servico', $servicoId)->update($data);
                return $servico;
            });
        }catch(\Exception $e){
            throw new DatabaseInsertException("Erro ao atualizar serviço.", StatusCode::SERVER_ERROR, $e);
        }
    }

    public function storeImages(string $itenFileName, int $servicoId, int $contratanteId, int $userId)
    {
        return $this->imagesModel->create([
            'IMG' => $itenFileName,
            "tb_servico_idtb_servico" => $servicoId,
            "tb_servico_tb_contratante_idtb_contratante" => $contratanteId,
            "tb_servico_tb_contratante_tb_user_idtb_user"=> $userId,
        ]);
    }

    public function getServicoId(int $servicoId)
    {   
        $servico = $this->model->where('idtb_servico', $servicoId)->first();
        if ($servico) {
            return $servico->idtb_servico;
        }
        else{
            throw new ServiceNotFoundedException("Serviço não encontrado");
        }
    }
}

?>