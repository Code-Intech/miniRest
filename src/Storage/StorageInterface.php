<?php

namespace MiniRest\Storage;

interface StorageInterface
{
    public function put($path, $contents);

    public function get($path);

    public function delete($path);
}