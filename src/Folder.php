<?php

namespace App;

use App\Exceptions\NetworkException;
use Illuminate\Support\Collection;
use Override;

readonly class Folder extends File
{
    public function __construct(object $file, string $path)
    {
        parent::__construct($file, $path . '/' . $file->VissibleName);
    }

    /**
     * @throws NetworkException
     */
    #[Override]
    public function save(): void
    {
        Request::documents($this)
            ->each(fn(File $file) => $file->save());
    }

    /**
     * @throws NetworkException
     */
    #[Override]
    public function idList(): Collection
    {
        return Request::documents($this)
            ->map(fn(File $file) => $file->idList())
            ->flatten();
    }
}