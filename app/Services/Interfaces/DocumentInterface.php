<?php

namespace App\Services\Interfaces;

use App\Models\Document;
use Illuminate\Database\Eloquent\Collection;

interface DocumentInterface
{
    public function store(array $data): Document;
    public function update(int $id, array $data): Document;
    public function destroy(int $data): bool;
    public function getById(int $id): Document;
    public function getAll(): Collection;
}
