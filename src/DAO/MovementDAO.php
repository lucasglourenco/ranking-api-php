<?php

namespace App\DAO;

use App\Database\BaseDAO;
use App\Database\Condition;

class MovementDAO extends BaseDAO
{
    protected string $table = 'movement m';

    public function findById(int $id): ?array
    {
        return $this
            ->where(
                Condition::where('m.id', '=', $id)
            )
            ->first();
    }

    public function findByName(string $name): ?array
    {
        return $this
            ->where(
                Condition::where('m.name', '=', $name)
            )
            ->first();
    }

    public function all(): array
    {
        return $this
            ->orderBy('m.name')
            ->get();
    }
}