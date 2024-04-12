<?php

namespace App;

use App\Exceptions\UnsupportedFileTypeException;

abstract readonly class File
{

    public bool $bookmarked;
    public string $id;
    public string $name;
    public string $parent;
    public string $path;

    public function __construct(object $file, string $path)
    {
        $this->bookmarked = $file->Bookmarked;
        $this->id = $file->ID;
        $this->name = $file->VissibleName;
        $this->parent = $file->Parent;
        $this->path = $path;
    }

    /**
     * @throws UnsupportedFileTypeException
     */
    public static function make(object $file, string $path): File
    {
        return match ($file->Type) {
            'DocumentType' => new Document($file, $path),
            'CollectionType' => new Folder($file, $path),
            default => throw new UnsupportedFileTypeException($file->Type),
        };
    }

    abstract public function save(): void;
}