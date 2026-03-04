<?php

namespace App\Database\Schema;

use App\Database\Connection;

abstract class Migration
{
    protected \PDO $connection;

    public function __construct()
    {
        $this->connection = Connection::get();
    }

    abstract public function up(): void;
}