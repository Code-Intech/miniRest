<?php

namespace MiniRest\Actions\Servico;

use MiniRest\Exceptions\UploadErrorException;
use MiniRest\Http\Request\Request;
use MiniRest\Repositories\Servico\ServicoImgRepository;
use MiniRest\Storage\Acl\PublicAcl;
use MiniRest\Storage\S3Storage;
use MiniRest\Storage\UUIDFileName;
use PDOException;

class ServicoUploadImgAction
{
    /**
     * @throws UploadErrorException
     */
    public function execute($uploadedFile, int $servicoId, $contratanteId, $userId): string
    {
        $storage = new S3Storage(new PublicAcl());
        $name = UUIDFileName::uuidFileName($uploadedFile['name']);

        try {
            (new ServicoImgRepository())->storeServicoImg([
                'tb_servico_idtb_servico' => $servicoId,
                'IMG' => $name,
                'tb_servico_tb_contratante_idtb_contratante' => $contratanteId,
                'tb_servico_tb_contratante_tb_user_idtb_user' => $userId,
            ]);
            $storage->upload("servico_images/" . $name, $uploadedFile);
        } catch (PDOException $exception) {
            throw new UploadErrorException($exception->getMessage());
        }

        return $storage->generatePublicdUrl("servico_images/" . $name);
    }


}


?>