#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Database\Schema\SchemaManager;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$schema = new SchemaManager();
$schema->run();

echo "Tabelas criadas com sucesso.\n";