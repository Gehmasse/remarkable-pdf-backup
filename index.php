<?php

use App\Backuper;

require_once __DIR__ . '/vendor/autoload.php';

const STORAGE = __DIR__ . '/storage';

if (!file_exists(STORAGE)) mkdir(STORAGE, recursive: true);
if (!file_exists(STORAGE . '/files')) mkdir(STORAGE . '/files', recursive: true);
if (!file_exists(STORAGE . '/info')) mkdir(STORAGE . '/info', recursive: true);

$backuper = new Backuper();

($backuper->readDir());