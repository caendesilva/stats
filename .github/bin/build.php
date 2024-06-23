<?php

require_once __DIR__ . '/vendor/autoload.php';

if (! file_exists(__DIR__ . '/data.yml')) {
    echo "File 'data.yml' not found, did you run from the root of the repository?";
    exit(1);
}
