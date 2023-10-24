<?php

namespace MiniRest\Actions\Servico;

use MiniRest\DTO\Servico\ServicoCreateDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Repositories\Servico\ServicoRepository;
use MiniRest\Models\Servico\Servico;
use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\Repositories\Servico\ServicoHabilidadeRepository;
use MiniRest\Repositories\Servico\ServicoProfissaoRepository;
use MiniRest\Storage\Acl\PublicAcl;
use MiniRest\Storage\S3Storage;
use MiniRest\Storage\UUIDFileName;

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

            // $upload = new S3Storage(new PublicAcl());
            
            // foreach($upload->reArrayFiles($servicoDTO->toArray()['images']) as $file)
            // {
            //     $fileName = UUIDFileName::uuidFileName($file['name']);
            //     $upload->upload('servico/' . $fileName, $file['tmp_name']);

            //     $servicoRepository->storeImages(
            //         $fileName, 
            //         $data['tb_servico_idtb_servico'], 
            //         $data['tb_servico_tb_contratante_idtb_contratante'],
            //         $data['tb_servico_tb_contratante_tb_user_idtb_user'],
            //     );
            // }
            

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
