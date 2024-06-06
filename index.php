<?php

use App\Backuper;
use App\Directory;
use App\Exceptions\NetworkException;
use Phalcon\Cop\Parser;

require_once __DIR__ . '/vendor/autoload.php';

$_ENV = require 'env.php';

Directory::ensure($_ENV['storage']);
Directory::ensure($_ENV['storage'] . '/files');
Directory::ensure($_ENV['storage'] . '/info');

$parser = new Parser();
$params = $parser->parse($argv);

$backuper = new Backuper();

try {
    match ($parser->get(0)) {
        'run' => $backuper->run(),
        'sync' => $backuper->syncAll(),
        'clean' => $backuper->cleanInfoFiles(),
        'delete' => $backuper->handleDeletions(),
        default => (function () {
            echo 'invalid option, try "php index.php {sync|clean|delete}".';
        })(),
    };
} catch (NetworkException $e) {
    die('network exception');
}

echo PHP_EOL;

