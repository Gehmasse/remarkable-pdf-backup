<?php

namespace App;

use Illuminate\Support\Collection;
use Override;

readonly class Document extends File
{
    public string $modified;

    public function __construct(object $file, string $path)
    {
        parent::__construct($file, $path);

        $this->modified = $file->ModifiedClient;
    }

    #[Override]
    public function save(): void
    {
        echo '---' . PHP_EOL;

        echo 'processing file ' . $this->path . '/' . $this->name . PHP_EOL;

        if (file_exists($this->filename())) {
            echo 'file exists - ';

            $datesMatch = $this->info()?->modified() === $this->modified;

            echo $datesMatch ? 'dates match - ' : 'dates differ - ';

            if ($datesMatch) {
                echo 'skip' . PHP_EOL;
                return;
            }

            echo 'overwrite' . PHP_EOL;
        }

        echo 'starting download' . PHP_EOL;

        try {
            $pdf = Request::document($this);
        } catch (Exceptions\NetworkException) {
            die('network exception');
        }

        Directory::ensure($this->folder());

        $this->store($pdf);

        $this->storeInfo();

        echo 'finished with success' . PHP_EOL;
    }

    private function folder(): string
    {
        return $_ENV['storage'] . '/files/' . $this->path;
    }

    private function filename(): string
    {
        return $this->folder() . '/' . $this->name . '.pdf';
    }

    private function info(): ?Info
    {
        return Info::make($this->id);
    }

    private function store(string $pdf): void
    {
        file_put_contents($this->filename(), $pdf);
    }

    private function storeInfo(): void
    {
        Info::store($this->id, $this);
    }

    #[Override]
    public function idList(): Collection
    {
        return collect($this->id);
    }
}