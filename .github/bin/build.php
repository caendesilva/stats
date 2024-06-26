<?php

const BASE_PATH = __DIR__.'/../..';

require_once __DIR__.'/vendor/autoload.php';

$function = $argv[1] ?: null;

$commands = ['convert', 'lint', 'build'];

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

// Commands

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

    $meta = ['meta' => [
        'last_updated' => date('Y-m-d H:i:s T'),
        'source' => 'https://github.com/caendesilva/stats/blob/master/data.yml',
    ]];

    $data = array_merge($meta, $data);

    try {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    } catch (Exception $exception) {
        line($exception->getMessage());

        return 1;
    }

    echo $json;

    return 0;
}

function build(): int
{
    try {
        mkdir(BASE_PATH.'/site');
        copy(BASE_PATH.'/data.json', BASE_PATH.'/site/data.json');

        $template = file_get_contents(__DIR__.'/index.php');

        // Evaluate PHP code
        ob_start();
        eval('?>'.$template);
        $html = ob_get_clean();

        file_put_contents(BASE_PATH.'/site/index.html', $html);
    } catch (Exception $exception) {
        line($exception->getMessage());

        return 1;
    }

    line('Build successful.');

    return 0;
}

// Helpers

function line(string $message): void
{
    echo $message."\n";
}
