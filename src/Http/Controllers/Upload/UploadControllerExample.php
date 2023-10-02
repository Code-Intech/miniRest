<?php

namespace MiniRest\Http\Controllers\Upload;

use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Storage\S3Storage;

class UploadControllerExample
{
    public function upload(Request $request)
    {
        $file = $request->files('file');
        var_dump($file);

        $storage = new S3Storage();
        $storage->upload("teste/" . $file['name'], $file['tmp_name']);

        Response::json(['calango' => $storage->generatePresignedUrl('teste/'. $file['name'])]);
    }
}