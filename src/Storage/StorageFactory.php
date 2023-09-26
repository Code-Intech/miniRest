<?php

namespace MiniRest\Storage;

use MiniRest\Exceptions\InvalidStorageTypeException;

class StorageFactory
{
    public static function createStorage($storageType, $basePath)
    {
        $className =  "MiniRest\\Storage\\StorageTypes\\" .
            ucfirst($storageType) . 'Storage';

        if (class_exists($className)) {
            return new $className($basePath);
        }

        throw new InvalidStorageTypeException("{$storageType} não encontrado.");
    }
}