<?php

namespace App;

use Illuminate\Support\Collection;

class Request
{
    public static function root(): Collection
    {
        $json = file_get_contents('http://10.11.99.1/documents/');

        return collect(json_decode($json))
            ->map(fn(object $file) => File::make($file, ''));
    }

    public static function documents(Folder $folder): Collection
    {
        $json = file_get_contents('http://10.11.99.1/documents/' . $folder->id);

        return collect(json_decode($json))
            ->map(fn(object $file) => File::make($file, $folder->path));
    }

    public static function documentIds(): Collection
    {
        return Request::root()
            ->map(fn(File $file) => $file->idList())
            ->flatten();
    }
}