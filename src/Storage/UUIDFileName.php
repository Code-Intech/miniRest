<?php

namespace MiniRest\Storage;

use Ramsey\Uuid\Uuid;

class UUIDFileName
{
    public static function uuidFileName(string $name): string
    {
        $rawFileNane = explode('.', $name);
        $ext = end($rawFileNane);
        return str_replace('.', '-', $rawFileNane[0] = Uuid::uuid4()) . ".$ext";
    }
}