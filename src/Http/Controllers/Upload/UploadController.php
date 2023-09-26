<?php

namespace MiniRest\Http\Controllers\Upload;

use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Storage\StorageFactory;

class UploadController
{
    public function upload(Request $request)
    {
        var_dump($request->files('file'));
        $storage = StorageFactory::createStorage('Disk', __DIR__ . '/../../../../storage');
        $storage->put($request->files('file')['name'], file_get_contents($request->files('file')['tmp_name']));
        Response::anyType($storage->get($request->files('file')['name']), $request->files('file')['type']);
    }
}