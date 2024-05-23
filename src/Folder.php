<?php

namespace App;

use Illuminate\Support\Collection;
use Override;

readonly class Folder extends File
{
    public function __construct(object $file, string $path)
    {
        parent::__construct($file, $path . '/' . $file->VissibleName);
    }

    #[Override]
    public function save(): void
    {
        $json = file_get_contents('http://10.11.99.1/documents/' . $this->id);

        collect(json_decode($json))
            ->map(fn(object $file) => File::make($file, $this->path))
            ->each(fn(File $file) => $file->save());
    }

    #[Override]
    public function idList(): Collection
    {
        $json = file_get_contents('http://10.11.99.1/documents/' . $this->id);

        return collect(json_decode($json))
            ->map(fn(object $file) => File::make($file, $this->path))
            ->map(fn(File $file) => $file->idList())
            ->flatten();
    }
}