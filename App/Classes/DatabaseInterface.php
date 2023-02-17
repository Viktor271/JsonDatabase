<?php

namespace App\Classes;

interface DatabaseInterface {
    public function createTable(string $tableName, array $columns): bool;
    public function add(array $data): bool;
    public function read(int $id): array;
    public function delete(int $id): bool;
    public function update(int $id, array $data): bool;
}