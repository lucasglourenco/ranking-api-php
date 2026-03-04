<?php

namespace App\DAO;

use App\Database\BaseDAO;
use App\Database\Condition;

class UserDAO extends BaseDAO
{
    protected string $table = 'user u';

    public function findById(int $id): ?array
    {
        return $this
            ->where(
                Condition::where('u.id', '=', $id)
            )
            ->first();
    }

    public function findByName(string $name): ?array
    {
        return $this
            ->where(
                Condition::where('u.name', '=', $name)
            )
            ->first();
    }

    public function all(): array
    {
        return $this
            ->orderBy('u.name')
            ->get();
    }
}