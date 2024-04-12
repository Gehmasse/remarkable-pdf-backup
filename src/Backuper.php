<?php

namespace App;

use Illuminate\Support\Collection;

class Backuper
{

    public function readDir(): Collection
    {
        $json = file_get_contents('http://10.11.99.1/documents/');

        return collect(json_decode($json))
            ->map(fn(object $file) => File::make($file, ''))
            ->each(fn(File $file) => $file->save());
    }
}