<?php

namespace App\Services\Interfaces;

use App\Models\Column;
use Illuminate\Database\Eloquent\Collection;

interface ColumnInterface
{
    public function store(array $data): Column;
    public function update(int $id, array $data): Column;
    public function destroy(int $data): bool;
    public function getById(int $id): Column;
    public function getAll(): Collection;
}
