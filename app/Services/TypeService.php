<?php

namespace App\Services;

use App\Http\Requests\TypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\Type;
use App\Services\Interfaces\TypeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class TypeService implements TypeInterface
{

    public function store(array $data): Type
    {
        try{
            return Type::create($data);
        } catch (\Exception $e){
            log::error('Store Type Exception');
            log::error($e);
            throw $e;
        }
    }

    public function update(int $id, array $data): Type
    {
        $model = Type::findOrFail($id);

        try{
            return $model->fill($$data)->update();
        } catch (\Exception $e){
            log::error('Update Type Exception');
            log::error($e);
            throw $e;
        }

        return $model;
    }

    public function destroy(int $id): bool
    {
        $model = Type::findOrFail($id);

        try{
            return $model->delete();
        } catch (\Exception $e){
            log::error('Update Type Exception');
            log::error($e);
            throw $e;
        }

    }

    public function getById(int $id): Type
    {
        return Type::findOrFail($id);
    }

    public function getAll(): Collection
    {
        return Type::all();
    }
}
