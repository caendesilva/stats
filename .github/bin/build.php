<?php

require_once __DIR__ . '/vendor/autoload.php';

$yaml = file_get_contents(__DIR__ . '/../../data.yml');
$data = Symfony\Component\Yaml\Yaml::parse($yaml);

echo "Data loaded from 'data.yml':\n";

$json = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents(__DIR__ . '/../../data.json', $json);

echo "Data saved to 'data.json':\n";
