<?php

require_once __DIR__ . '/vendor/autoload.php';

// Get function to call from first argument

$function = $argv[1] ?: null;

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

exit(call_user_func($function) ?: 0);

function convert(): void
{
    $yaml = file_get_contents('php://stdin');
    $data = Symfony\Component\Yaml\Yaml::parse($yaml);

    echo json_encode($data, JSON_PRETTY_PRINT);
}
