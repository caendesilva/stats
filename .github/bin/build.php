<?php

require_once __DIR__ . '/vendor/autoload.php';

// Get function to call from first argument

$function = $argv[1] ?? null;

$commands = ['convert'];

if (! in_array($function, $commands)) {
    if ($function) {
        echo "Unknown command: $function\n";
    }
    echo "Available commands:\n";
    foreach ($commands as $command) {
        echo "  $command\n";
    }
    exit(1);
}

function convert(): void
{
    $yaml = file_get_contents(__DIR__ . '/../../data.yml');
    $data = Symfony\Component\Yaml\Yaml::parse($yaml);

    echo "Data loaded from 'data.yml':\n";

    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents(__DIR__ . '/../../data.json', $json);

    echo "Data saved to 'data.json':\n";
}
