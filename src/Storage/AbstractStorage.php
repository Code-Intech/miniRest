<?php

namespace MiniRest\Storage;

abstract class AbstractStorage implements StorageInterface
{
    protected string $basePath;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }
}