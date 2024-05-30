<?php

use App\Backuper;
use Phalcon\Cop\Parser;
use App\Directory;

require_once __DIR__ . '/vendor/autoload.php';

$_ENV = require 'env.php';

Directory::ensure($_ENV['storage']);
Directory::ensure($_ENV['storage'] . '/files');
Directory::ensure($_ENV['storage'] . '/info');

$parser = new Parser();
$params = $parser->parse($argv);

$backuper = new Backuper();

match ($parser->get(0)) {
    'sync' => $backuper->syncAll(),
    'clean' => $backuper->cleanInfoFiles(),
    'delete' => $backuper->handleDeletions(),
    default => (function () {
        echo 'invalid option, try "php index.php {sync|clean|delete}".';
    })(),
};

echo PHP_EOL;

