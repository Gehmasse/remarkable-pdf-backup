<?php

namespace App;

class Directory
{
    public static function ensure(string $path): void
    {
        if (!file_exists($path)) {
            mkdir($path, recursive: true);
        }
    }
}