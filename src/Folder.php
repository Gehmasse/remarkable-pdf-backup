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
        Request::documents($this)
            ->each(fn(File $file) => $file->save());
    }

    #[Override]
    public function idList(): Collection
    {
        return Request::documents($this)
            ->map(fn(File $file) => $file->idList())
            ->flatten();
    }
}