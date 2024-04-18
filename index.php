<?php

use App\Backuper;
use Phalcon\Cop\Parser;

require_once __DIR__ . '/vendor/autoload.php';

$_ENV = require 'env.php';

if (!file_exists($_ENV['storage'])) mkdir($_ENV['storage'], recursive: true);
if (!file_exists($_ENV['storage'] . '/files')) mkdir($_ENV['storage'] . '/files', recursive: true);
if (!file_exists($_ENV['storage'] . '/info')) mkdir($_ENV['storage'] . '/info', recursive: true);

$parser = new Parser();
$params = $parser->parse($argv);

$backuper = new Backuper();

match ($parser->get(0)) {
    'sync' => $backuper->syncAll(),
    'clean' => $backuper->cleanInfoFiles(),
    default => (function () {
        echo 'invalid option, try "sync" or "clean".';
    })(),
};
