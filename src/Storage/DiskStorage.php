<?php

namespace MiniRest\Storage;

class DiskStorage
{
    private string $basePath;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    public function put($path, $contents)
    {
        $fullPath = $this->basePath . '/' . $path;
        file_put_contents($fullPath, file_get_contents($contents));
    }

    public function get($path)
    {
        $fullPath = $this->basePath . '/' . $path;
        if (file_exists($fullPath)) {
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

    function reArrayFiles($file)
    {
        $file_ary = array();
        $file_count = count($file['name']);
        $file_key = array_keys($file);

        for($i=0;$i<$file_count;$i++)
        {
            foreach($file_key as $val)
            {
                $file_ary[$i][$val] = $file[$val][$i];
            }
        }
        return $file_ary;
    }
}