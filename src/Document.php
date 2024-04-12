<?php

namespace App;

use Throwable;

readonly class Document extends File
{

    public string $modified;

    public function __construct(object $file, string $path)
    {
        parent::__construct($file, $path);

        $this->modified = $file->ModifiedClient;
    }

    public function save(): void
    {
        echo '---' . PHP_EOL;

        echo 'processing file ' . $this->path . '/' . $this->name . PHP_EOL;

        if (file_exists($this->filename())) {
            echo 'file exists - ';

            $datesMatch = $this->info()->modified === $this->modified;

            if ($datesMatch) {
                echo 'dates match - ';
            } else {
                echo 'dates differ - ';
            }

            if ($datesMatch) {
                echo 'skip' . PHP_EOL;
                return;
            }

            echo 'overwrite' . PHP_EOL;
        }

        echo 'starting download' . PHP_EOL;

        $pdf = $this->downloadFile();

        if($pdf === false) {
            die('network fetch error');
        }

        $this->ensureFolder();

        $this->store($pdf);

        $this->storeInfo();

        echo 'finished with success' . PHP_EOL;

    }

    private function folder(): string
    {
        return STORAGE . '/files/' . $this->path;
    }

    private function filename(): string
    {
        return $this->folder() . '/' . $this->name . ' - ' . $this->id . '.pdf';
    }

    private function info(): bool|object
    {
        return file_exists($this->infoFilename())
            ? json_decode(file_get_contents($this->infoFilename()))
            : false;
    }

    private function infoFilename(): string
    {
        return STORAGE . '/info/' . $this->id . '.json';
    }

    private function ensureFolder(): void
    {
        if (!file_exists($this->folder())) {
            mkdir($this->folder(), recursive: true);
        }
    }

    private function store(false|string $pdf): void
    {
        file_put_contents($this->filename(), $pdf);
    }

    private function storeInfo(): void
    {
        file_put_contents($this->infoFilename(), json_encode($this));
    }

    private function downloadFile(): string|false
    {
        return file_get_contents('http://10.11.99.1/download/' . $this->id . '/placeholder');

    }
}