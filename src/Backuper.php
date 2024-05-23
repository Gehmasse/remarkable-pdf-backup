<?php

namespace App;

use Illuminate\Support\Collection;

class Backuper
{
    public function syncAll(): Collection
    {
        return Request::root()
            ->each(fn(File $file) => $file->save());
    }

    public function handleDeletions(): void
    {
        $existingIds = Request::documentIds();

        Info::all()->each(function (Info $info) use ($existingIds) {
            if (!$existingIds->contains($info->id())) {
                echo 'deleting ' . $info->id() . ' - ' . $info->documentFile() . PHP_EOL;
                $info->deleteFileAndInfo();
            }
        });
    }

    public function cleanInfoFiles(): void
    {
        Info::all()->each(function (Info $info) {
            if (!$info->documentExists()) {
                echo 'deleting info file ' . $info->id() . ' - ' . $info->documentFile() . PHP_EOL;
                $info->deleteInfoFile();
            }
        });
    }
}