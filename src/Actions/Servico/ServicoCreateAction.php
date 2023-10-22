<?php

namespace MiniRest\Actions\Servico;

use MiniRest\DTO\Servico\ServicoCreateDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Repositories\Servico\ServicoRepository;
use MiniRest\Models\Servico\Servico;
use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\Models\Servico\ServicoProfissao;
use MiniRest\Repositories\Servico\ServicoHabilidadeRepository;
use MiniRest\Repositories\Servico\ServicoProfissaoRepository;

class ServicoCreateAction
{
    /**
     * @throws DatabaseInsertException
     */
    public function execute(ServicoCreateDTO $servicoDTO)
    {
        $data = $servicoDTO->toArray();

        DB::beginTransaction();
        try {

            $servicoRepository = new ServicoRepository(new Servico());
            $servico = $servicoRepository->storeServico($data);
            $servicoId = $servico->idtb_servico;

            $profissoes = $data['profissoes'];
            $habilidades = $data['habilidades'];

            if(!empty($profissoes)){
                $servicoProfissaoRepository = new ServicoProfissaoRepository();
                $userId = $data['tb_contratante_tb_user_idtb_user'];
                $contratanteId = $data['tb_contratante_idtb_contratante'];
            
                foreach ($profissoes as $profissaoId) {
                    $servicoProfissaoRepository->storeServicoProfissao($servicoId, $profissaoId, $userId, $contratanteId);
                }
            }

            if(!empty($habilidades)){
                $servicoHabilidadeRepository = new ServicoHabilidadeRepository();
                $userId = $data['tb_contratante_tb_user_idtb_user'];
                $contratanteId = $data['tb_contratante_idtb_contratante'];

                foreach($habilidades as $habilidadeId){
                    $servicoHabilidadeRepository->storeServicoHabilidade($servicoId, $contratanteId, $userId, $habilidadeId);
                }
            }
            

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollback();
            throw new DatabaseInsertException(
                "Erro ao inserir o serviço: " . $exception->getMessage(),
                StatusCode::SERVER_ERROR
            );
        }
    }

}
