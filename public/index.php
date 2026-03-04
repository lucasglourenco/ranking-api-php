<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$routes = require __DIR__ . '/../routes/api.php';

$app = new \App\Core\App($routes);
$app->run();