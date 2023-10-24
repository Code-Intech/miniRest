<?php

namespace MiniRest\Actions\Servico;

use MiniRest\DTO\Servico\ServicoUploadImageDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Exceptions\UploadErrorException;
use MiniRest\Storage\Acl\PublicAcl;
use MiniRest\Storage\S3Storage;
use MiniRest\Storage\UUIDFileName;
use MiniRest\Repositories\Servico\ServicoRepository;

class ServicoUploadImageAction
{
    /**
     * @throws DatabaseInsertException
     */
    public function execute(ServicoUploadImageDTO $servicoUploadImageDTO)
    {
        $data = $servicoUploadImageDTO->toArray();
        $servicoRepository = new ServicoRepository();
        
        try{
            $image = UUIDFileName::uuidFileName($data['IMG']['name']);
            $upload = new S3Storage(new PublicAcl());

            $servicoRepository->storeImages(
                $image, 
                $data['tb_servico_idtb_servico'], 
                $data['tb_servico_tb_contratante_idtb_contratante'],
                $data['tb_servico_tb_contratante_tb_user_idtb_user'],
            );
            
            $upload->upload(
                'servico/' . $image,
                $servicoUploadImageDTO->toArray()['IMG']['tmp_name']
            );

        }
        catch(\PDOException $e){
            throw new UploadErrorException($e->getMessage());
        }
    }
    

}

?>