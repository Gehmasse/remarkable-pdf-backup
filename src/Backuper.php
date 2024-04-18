<?php

namespace App;

use Illuminate\Support\Collection;

class Backuper
{

    public function syncAll(): Collection
    {
        $json = file_get_contents('http://10.11.99.1/documents/');

        return collect(json_decode($json))
            ->map(fn(object $file) => File::make($file, ''))
            ->each(fn(File $file) => $file->save());
    }

    public function handleDeletions(): void
    {

    }

    public function cleanInfoFiles(): void
    {
        Info::all()->each(function (Info $info) {
            if (!$info->documentExists()) {
                echo 'delete info file ' . $info->id() . ' for ' . $info->documentFile() . PHP_EOL;
                $info->deleteInfoFile();
            }
        });
    }
}