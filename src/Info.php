<?php

namespace App;

use Illuminate\Support\Collection;

readonly class Info
{
    private function __construct(private string $id)
    {
    }

    public static function make(string $id): ?Info
    {
        if (file_exists(self::staticFilename($id))) {
            return new self($id);
        }

        return null;
    }

    public function data(): object
    {
        return json_decode(file_get_contents($this->filename()));
    }

    public function filename(): string
    {
        return self::staticFilename($this->id);
    }

    private static function staticFilename(string $id): string
    {
        return $_ENV['storage'] . '/info/' . $id . '.json';
    }

    public static function store(string $id, Document $document): void
    {
        file_put_contents(self::staticFilename($id), json_encode($document));
    }

    public function modified(): string
    {
        return $this->data()->modified;
    }

    /**
     * @return Collection<int, self>
     */
    public static function all(): Collection
    {
        return collect(scandir($_ENV['storage'] . '/info'))
            ->filter(fn(string $file) => !str_starts_with($file, '.'))
            ->map(fn(string $file) => explode('.', $file)[0])
            ->map(fn(string $file) => Info::make($file));
    }

    public function id(): string
    {
        return $this->id;
    }

    public function documentExists(): bool
    {
        return file_exists($this->documentFile());
    }

    public function documentFile(): string
    {
        return $_ENV['storage'] . '/files' . $this->data()->path . '/' . $this->data()->name . '.pdf';
    }

    public function deleteInfoFile(): void
    {
        unlink($this->filename());
    }
}