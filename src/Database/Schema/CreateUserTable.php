<?php

namespace App\Database\Schema;

use App\Database\Connection;

class CreateUserTable extends Migration
{
    public function up(): void
    {
        $pdo = Connection::get();

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS user (
                id INT NOT NULL,
                name VARCHAR(100) NOT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB
        ");

        $pdo->exec("
            INSERT INTO user (id, name) VALUES
            (1,'Joao'),
            (2,'Jose'),
            (3,'Paulo')
            ON DUPLICATE KEY UPDATE name = VALUES(name)
        ");
    }
}