<?php

namespace App\Database;

class Condition
{
    private array $conditions = [];

    private function __construct(string $column, string $operator, mixed $value)
    {
        $this->conditions[] = [
            'boolean' => 'AND',
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
        ];
    }

    public static function where(string $column, string $operator, mixed $value): self
    {
        return new self($column, $operator, $value);
    }

    public function and(string $column, string $operator, mixed $value): self
    {
        $this->conditions[] = [
            'boolean' => 'AND',
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
        ];

        return $this;
    }

    public function or(string $column, string $operator, mixed $value): self
    {
        $this->conditions[] = [
            'boolean' => 'OR',
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
        ];

        return $this;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function getBindings(): array
    {
        return array_column($this->conditions, 'value');
    }
}