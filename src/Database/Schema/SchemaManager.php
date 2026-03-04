<?php

namespace App\Database\Schema;

class SchemaManager
{
    public function run(): void
    {
        $this->createDatabase();

        $migrations = [
            new CreateUserTable(),
            new CreateMovementTable(),
            new CreatePersonalRecordTable(),
        ];

        foreach ($migrations as $migration) {
            $migration->up();
        }
    }

    public function createDatabase(): void
    {
        $pdo = \App\Database\Connection::getWithoutDatabase();

        $database = $_ENV['DB_DATABASE'];

        $pdo->exec("CREATE DATABASE IF NOT EXISTS {$database} 
                CHARACTER SET utf8mb4 
                COLLATE utf8mb4_unicode_ci");
    }
}