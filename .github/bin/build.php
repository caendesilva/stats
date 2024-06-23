<?php

require_once __DIR__.'/vendor/autoload.php';

$function = $argv[1] ?: null;

$commands = ['convert', 'lint'];

if (! in_array($function, $commands)) {
    if ($function) {
        line("Unknown command: $function");
    }
    line('Available commands:');
    foreach ($commands as $command) {
        line("  $command");
    }
    exit(1);
}

exit(call_user_func($function) ?: 0);

function lint(): int
{
    $yaml = file_get_contents('php://stdin');

    try {
        Symfony\Component\Yaml\Yaml::parse($yaml);
    } catch (Symfony\Component\Yaml\Exception\ParseException $exception) {
        line($exception->getMessage());

        return 1;
    }

    line('YAML is valid.');

    return 0;
}

function convert(): int
{
    try {
        $yaml = file_get_contents('php://stdin');
        $data = Symfony\Component\Yaml\Yaml::parse($yaml);
    } catch (Symfony\Component\Yaml\Exception\ParseException $exception) {
        line($exception->getMessage());

        return 1;
    }

    try {
        $json = json_encode($data, JSON_PRETTY_PRINT);
    } catch (Exception $exception) {
        line($exception->getMessage());

        return 1;
    }

    echo $json;

    return 0;
}

function line(string $message): void
{
    echo $message."\n";
}
