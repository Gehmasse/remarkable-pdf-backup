<?php

use App\Backuper;

require_once __DIR__ . '/vendor/autoload.php';

$_ENV = require 'env.php';

if (!file_exists($_ENV['storage'])) mkdir($_ENV['storage'], recursive: true);
if (!file_exists($_ENV['storage'] . '/files')) mkdir($_ENV['storage'] . '/files', recursive: true);
if (!file_exists($_ENV['storage'] . '/info')) mkdir($_ENV['storage'] . '/info', recursive: true);

$backuper = new Backuper();

($backuper->readDir());