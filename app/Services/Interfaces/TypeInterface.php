<?php

namespace App\Services\Interfaces;

use App\Models\Type;
use Illuminate\Database\Eloquent\Collection;

interface TypeInterface
{
    public function store(array $data): Type;
    public function update(int $id, array $data): Type;
    public function destroy(int $data): bool;
    public function getById(int $id): Type;
    public function getAll(): Collection;
}
