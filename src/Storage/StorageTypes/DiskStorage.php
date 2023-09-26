<?php

namespace MiniRest\Storage\StorageTypes;

use MiniRest\Storage\AbstractStorage;

class DiskStorage extends AbstractStorage
{
    public function put($path, $contents)
    {
        $fullPath = $this->basePath . '/' . $path;
        file_put_contents($fullPath, $contents);
    }

    public function get($path)
    {
        $fullPath = $this->basePath . '/' . $path;
        if (file_exists($fullPath)) {
            var_dump($fullPath);
            return file_get_contents($fullPath);
        }
        return null;
    }

    public function delete($path)
    {
        $fullPath = $this->basePath . '/' . $path;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}