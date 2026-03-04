<?php

namespace App\Database\Schema;

use App\Database\Connection;

class CreateMovementTable extends Migration
{
    public function up(): void
    {
        $pdo = Connection::get();

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS movement (
                id INT NOT NULL,
                name VARCHAR(100) NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB
        ");

        $pdo->exec("
            INSERT INTO movement (id, name) VALUES
            (1,'Deadlift'),
            (2,'Back Squat'),
            (3,'Bench Press')
            ON DUPLICATE KEY UPDATE name = VALUES(name)
        ");
    }
}