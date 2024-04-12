<?php

namespace App\Exceptions;

use Exception;

class UnsupportedFileTypeException extends Exception
{

    public function __construct(string $type)
    {
        parent::__construct('file type ' . $type . ' not supported');
    }
}