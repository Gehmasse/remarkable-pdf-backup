<?php

namespace App;

use App\Exceptions\NetworkException;
use Illuminate\Support\Collection;

class Request
{
    /**
     * @throws NetworkException
     */
    public static function root(): Collection
    {
        $json = file_get_contents('http://10.11.99.1/documents/');

        if ($json === false) {
            throw new NetworkException();
        }

        return collect(json_decode($json))
            ->map(fn(object $file) => File::make($file, ''));
    }

    /**
     * @throws NetworkException
     */
    public static function documents(Folder $folder): Collection
    {
        $json = file_get_contents('http://10.11.99.1/documents/' . $folder->id);

        if ($json === false) {
            throw new NetworkException();
        }

        return collect(json_decode($json))
            ->map(fn(object $file) => File::make($file, $folder->path));
    }

    /**
     * @throws NetworkException
     */
    public static function documentIds(): Collection
    {
        return Request::root()
            ->map(fn(File $file) => $file->idList())
            ->flatten();
    }

    /**
     * @throws NetworkException
     */
    public static function document(Document $document): string
    {
        $pdf = file_get_contents('http://10.11.99.1/download/' . $document->id . '/placeholder');

        if ($pdf === false) {
            throw new NetworkException();
        }

        return $pdf;
    }
}