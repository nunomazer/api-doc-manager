<?php

namespace App\Services;

use App\Models\Column;
use App\Services\Interfaces\ColumnInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ColumnService implements ColumnInterface
{

    public function store(array $data): Column
    {
        try{
            return Column::create($data);
        } catch (\Exception $e){
            log::error('Store Column Exception');
            log::error($e);
            throw $e;
        }
    }

    public function update(int $id, array $data): Column
    {
        $model = Column::findOrFail($id);

        try{
            return $model->fill($$data)->update();
        } catch (\Exception $e){
            log::error('Update Column Exception');
            log::error($e);
            throw $e;
        }

        return $model;
    }

    public function destroy(int $id): bool
    {
        $model = Column::findOrFail($id);

        try{
            return $model->delete();
        } catch (\Exception $e){
            log::error('Update Column Exception');
            log::error($e);
            throw $e;
        }

    }

    public function getById(int $id): Column
    {
        return Column::findOrFail($id);
    }

    public function getAll(): Collection
    {
        return Column::all();
    }
}
