<?php

namespace App\Database;

abstract class BaseDAO
{
    protected \PDO $connection;
    protected string $table;

    protected array $selects = [];
    protected array $joins = [];
    protected array $groups = [];
    protected array $wheres = [];
    protected array $bindings = [];
    protected array $orders = [];
    protected ?int $limit = null;
    protected ?int $offset = null;

    public function __construct()
    {
        $this->connection = Connection::get();
    }

    public function insert(array $data): int
    {
        $columns = array_keys($data);
        $params = array_map(fn($col) => ':' . $col, $columns);

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $columns),
            implode(', ', $params)
        );

        $stmt = $this->connection->prepare($sql);

        foreach ($data as $column => $value) {
            $stmt->bindValue(':' . $column, $value);
        }

        $stmt->execute();

        return (int)$this->connection->lastInsertId();
    }

    public function updateById(int $id, array $data): bool
    {
        $set = [];

        foreach ($data as $column => $value) {
            $set[] = "{$column} = :{$column}";
        }

        $sql = sprintf(
            "UPDATE %s SET %s WHERE id = :id",
            $this->table,
            implode(', ', $set)
        );

        $stmt = $this->connection->prepare($sql);

        foreach ($data as $column => $value) {
            $stmt->bindValue(':' . $column, $value);
        }

        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->connection->prepare(
            "DELETE FROM {$this->table} WHERE id = :id"
        );

        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function where(Condition $condition): static
    {
        foreach ($condition->getConditions() as $cond) {

            $cleanColumn = preg_replace('/[^a-zA-Z0-9_]/', '_', $cond['column']);
            $param = ':' . $cleanColumn . count($this->bindings);

            $clause = "{$cond['column']} {$cond['operator']} {$param}";

            if (empty($this->wheres)) {
                $this->wheres[] = $clause;
            } else {
                $this->wheres[] = "{$cond['boolean']} {$clause}";
            }

            $this->bindings[$param] = $cond['value'];
        }

        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $column = preg_replace('/[^a-zA-Z0-9_.]/', '', $column);

        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';

        $this->orders[] = "{$column} {$direction}";

        return $this;
    }

    public function limit(int $limit, ?int $offset = null): static
    {
        $this->limit = $limit;
        $this->offset = $offset;

        return $this;
    }

    public function select(string $field, ?string $alias = null): static
    {
        $field = preg_replace('/[^a-zA-Z0-9_().,* ]/', '', $field);

        if ($alias) {
            $alias = preg_replace('/[^a-zA-Z0-9_]/', '', $alias);
            $this->selects[] = "{$field} AS {$alias}";
        } else {
            $this->selects[] = $field;
        }

        return $this;
    }

    public function addJoin(
        string $table,
        string $condition,
        string $type = 'INNER'
    ): static
    {
        $table = preg_replace('/[^a-zA-Z0-9_.() ]/', '', $table);

        $type = strtoupper($type);

        if (!in_array($type, ['INNER', 'LEFT', 'RIGHT'])) {
            $type = 'INNER';
        }

        $this->joins[] = "{$type} JOIN {$table} ON {$condition}";

        return $this;
    }

    public function groupBy(string $field): static
    {
        $this->groups[] = $field;
        return $this;
    }

    public function get(): array
    {
        $sql = $this->buildSelect();

        $stmt = $this->connection->prepare($sql);

        foreach ($this->bindings as $param => $value) {
            $stmt->bindValue($param, $value);
        }

        $stmt->execute();

        $results = $stmt->fetchAll();

        $this->reset();

        return $results;
    }

    public function first(): ?array
    {
        $this->limit = 1;
        $results = $this->get();

        return $results[0] ?? null;
    }

    protected function buildSelect(): string
    {
        $select = empty($this->selects)
            ? '*'
            : implode(', ', $this->selects);

        $sql = "SELECT {$select} FROM {$this->table}";

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->wheres)) {
            $sql .= ' WHERE ' . implode(' ', $this->wheres);
        }

        if (!empty($this->groups)) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groups);
        }

        if (!empty($this->orders)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orders);
        }

        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;

            if ($this->offset !== null) {
                $sql .= ' OFFSET ' . $this->offset;
            }
        }

        return $sql;
    }

    protected function reset(): void
    {
        $this->selects = [];
        $this->joins = [];
        $this->groups = [];
        $this->wheres = [];
        $this->bindings = [];
        $this->orders = [];
        $this->limit = null;
        $this->offset = null;
    }
}